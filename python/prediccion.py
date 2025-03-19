from flask import Flask, request, jsonify
from sqlalchemy import create_engine
import pandas as pd
import numpy as np
import tensorflow as tf
from tensorflow import keras
from sklearn.preprocessing import StandardScaler, LabelEncoder
import joblib

app = Flask(__name__)

# **🔗 Conectar a MySQL**
DB_USER = "root"
DB_PASS = ""
DB_HOST = "localhost"
DB_NAME = "laravel-353031351031"

engine = create_engine(f"mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}/{DB_NAME}")

# 🔹 Cargar el modelo y el scaler
modelo = keras.models.load_model("modelo_prediccion.h5")
scaler = joblib.load("scaler.pkl")

# 🔹 Columnas utilizadas en el entrenamiento (deben coincidir con el scaler)
columnas_entrenamiento = [
    "nroposte", "luminaria", "potencia", "lat", "lng", "distrito",
    "dias_ultimo_mantenimiento", "total_mantenimientos_12m"
]

# 🔥 Endpoint para predecir el estado de un poste por ID
@app.route("/predecir", methods=["GET"])
def predecir():
    try:
        # Obtener el ID del parámetro GET
        poste_id = request.args.get("id")
        if not poste_id:
            return jsonify({"error": "Debe proporcionar un ID"}), 400

        # 🔍 Consultar el poste en la base de datos
        query = f"""
        SELECT 
            l.nroposte, l.luminaria, l.potencia, l.lat, l.lng,l.distrito,
            DATEDIFF(CURRENT_DATE, IFNULL(MAX(m.fecha), CURRENT_DATE)) AS dias_ultimo_mantenimiento,
            COUNT(m.id) AS total_mantenimientos_12m
        FROM postes l
        LEFT JOIN reclamos m ON l.id = m.poste_id 
            AND m.fecha >= CURDATE() - INTERVAL 24 MONTH
            AND m.fecha <> l.created_at
        WHERE l.estado = 'ACTIVO' AND l.id = {poste_id}
        GROUP BY l.nroposte, l.luminaria, l.potencia, l.lat, l.lng,l.distrito;
        """
        df = pd.read_sql(query, engine)

        # Si no se encontró el poste, devolver error
        if df.empty:
            return jsonify({"error": "No se encontró el poste"}), 404

        # Convertir datos categóricos a numéricos
        encoder = LabelEncoder()
        df["nroposte"] = encoder.fit_transform(df["nroposte"])
        df["luminaria"] = encoder.fit_transform(df["luminaria"])
        df["potencia"] = encoder.fit_transform(df["potencia"])
        df["distrito"] = encoder.fit_transform(df["distrito"])

        # Normalizar los datos
        df_scaled = scaler.transform(df[columnas_entrenamiento])

        # 🔮 Realizar la predicción
        prediccion = modelo.predict(df_scaled)
        probabilidad_falla = prediccion[0][0] * 100  # Convertir a porcentaje
        estado_predicho = "ACTIVO" if probabilidad_falla < 50 else "DADO DE BAJA"

        # 🔄 Devolver el resultado en JSON
        return jsonify({
            "poste_id": poste_id,
            "probabilidad_falla": f"{probabilidad_falla:.2f}%",
            "estado_predicho": estado_predicho
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500

# 🔥 Ejecutar la API en el puerto 5000
if __name__ == "__main__":
    app.run(debug=True, port=5000)