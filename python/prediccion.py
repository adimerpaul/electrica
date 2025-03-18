from flask import Flask, request, jsonify
import numpy as np
import tensorflow as tf
import joblib
import pandas as pd
import pymysql
from sqlalchemy import create_engine

app = Flask(__name__)
# **📂 Cargar el modelo entrenado y el scaler**

modelo = tf.keras.models.load_model("modelo_prediccion.h5")  # Si usaste la Red Neuronal
scaler = joblib.load("scaler.pkl")  # Normalizador

# **🔗 Conectar a MySQL**
DB_USER = "root"
DB_PASS = ""
DB_HOST = "localhost"
DB_NAME = "laravel-353031351031"

engine = create_engine(f"mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}/{DB_NAME}")

@app.route('/predecir/<int:id>', methods=['GET'])
def predecir(id):
    try:
        # **🔎 Recuperar datos del poste por ID**
        query = f"""
        SELECT 
            l.nroposte, l.luminaria, l.potencia, l.lat, l.lng, l.distrito,
            DATEDIFF(CURRENT_DATE, IFNULL(MAX(m.fecha), CURRENT_DATE)) AS dias_ultimo_mantenimiento,
            COUNT(m.id) AS total_mantenimientos_12m
        FROM postes l
        LEFT JOIN reclamos m ON l.id = m.poste_id 
            AND m.fecha >= CURDATE() - INTERVAL 24 MONTH
            AND m.fecha <> l.created_at
        WHERE l.estado = 'ACTIVO' AND l.id = {id}
        GROUP BY l.nroposte, l.luminaria, l.potencia, l.lat, l.lng, l.distrito;
        """
        
        df = pd.read_sql(query, engine)
        
        if df.empty:
            return jsonify({"error": "No se encontró el poste con el ID proporcionado."})
        
        # **📌 Convertir datos en formato numpy**
        valores = df.iloc[0].values.reshape(1, -1)
        
        # **✅ Normalizar datos**
        valores_normalizados = scaler.transform(valores)
        
        # **📌 Hacer la predicción**
        prediccion = modelo.predict(valores_normalizados)[0][0]
        probabilidad_falla = round(prediccion * 100, 2)  # Convertir a porcentaje
        
        # **📌 Definir estado**
        estado_predicho = "DADO DE BAJA" if prediccion > 0.5 else "ACTIVO"
        
        return jsonify({
            "id": id,
            "prediccion": estado_predicho,
            "probabilidad_falla": f"{probabilidad_falla}%"
        })
    
    except Exception as e:
        return jsonify({"error": str(e)})

if __name__ == '__main__':
    app.run(debug=True)
