from flask import Flask, request, jsonify
from sqlalchemy import create_engine
import pandas as pd
from sklearn.preprocessing import StandardScaler, LabelEncoder
import joblib

app = Flask(__name__)

#  Conectar a MySQL
DB_USER = "root"
DB_PASS = ""
DB_HOST = "localhost"
DB_NAME = "laravel-353031351031"

engine = create_engine(f"mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}/{DB_NAME}")

#  Cargar el modelo, scaler y encoders
modelo = joblib.load("logistic_regression_model.pkl") #Cambiar el modelo
scaler = joblib.load("scaler.pkl")
encoder_nroposte = joblib.load('encoder_nroposte.pkl')
encoder_luminaria = joblib.load('encoder_luminaria.pkl')
encoder_potencia = joblib.load('encoder_potencia.pkl')
encoder_distrito = joblib.load('encoder_distrito.pkl')

#  Columnas utilizadas en el entrenamiento
columnas_entrenamiento = [
    "nroposte", "luminaria", "potencia", "lat", "lng", "distrito",
    "dias_ultimo_mantenimiento", "total_mantenimientos_12m"
]

@app.route("/predecir", methods=["GET"])
def predecir():
    try:
        poste_id = request.args.get("id")
        if not poste_id:
            return jsonify({"error": "Debe proporcionar un ID"}), 400

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

        if df.empty:
            return jsonify({"error": "No se encontró el poste"}), 404

        # Convertir datos categóricos a numéricos usando los encoders cargados
        df["nroposte"] = encoder_nroposte.transform(df["nroposte"])
        df["luminaria"] = encoder_luminaria.transform(df["luminaria"])
        df["potencia"] = encoder_potencia.transform(df["potencia"])
        df["distrito"] = encoder_distrito.transform(df["distrito"])

        df_scaled = scaler.transform(df[columnas_entrenamiento])

        prediccion = modelo.predict(df_scaled)
        probabilidad_falla = prediccion[0] * 100 if hasattr(modelo, "predict_proba") else prediccion[0] * 100
        estado_predicho = "ACTIVO" if probabilidad_falla < 50 else "DADO DE BAJA"

        return jsonify({
            "poste_id": poste_id,
            "probabilidad_falla": f"{probabilidad_falla:.2f}%",
            "estado_predicho": estado_predicho
        })

    except Exception as e:
        return jsonify({"error": str(e)}), 500

if __name__ == "__main__":
    app.run(debug=True, port=5000)