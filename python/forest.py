import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import StandardScaler, LabelEncoder
import joblib

# 1. Cargar datos desde tu fuente (reemplaza con tu método de carga)
# Por ejemplo, desde un archivo CSV:
# df = pd.read_csv("tu_archivo_datos.csv")

# O desde una base de datos MySQL (como en tu código original):
from sqlalchemy import create_engine

DB_USER = "root"
DB_PASS = ""
DB_HOST = "localhost"
DB_NAME = "laravel-353031351031"

engine = create_engine(f"mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}/{DB_NAME}")

query = """
SELECT 
    l.id, l.nroposte, l.luminaria, l.potencia, l.lat, l.lng, l.distrito,
    DATEDIFF(CURRENT_DATE, IFNULL(MAX(m.fecha), CURRENT_DATE)) AS dias_ultimo_mantenimiento,
    COUNT(m.id) AS total_mantenimientos_12m,
    CASE 
        WHEN MAX(m.estado) = 'EN ESPERA' THEN 1
        WHEN MAX(m.estado) = 'FALLA' THEN 1
        ELSE 0 
    END AS estado_actual
FROM postes l
LEFT JOIN reclamos m ON l.id = m.poste_id 
    AND m.fecha >= CURDATE() - INTERVAL 24 MONTH
    AND m.fecha <> l.created_at
WHERE l.estado = 'ACTIVO'
GROUP BY l.id, l.nroposte, l.luminaria, l.potencia, l.lat, l.lng, l.distrito;
"""

df = pd.read_sql(query, engine)

# 2. Preprocesamiento de datos (como en tu código original)
df.fillna(0, inplace=True)

encoder_nroposte = LabelEncoder()
df["nroposte"] = encoder_nroposte.fit_transform(df["nroposte"])

encoder_luminaria = LabelEncoder()
df["luminaria"] = encoder_luminaria.fit_transform(df["luminaria"])

encoder_potencia = LabelEncoder()
df["potencia"] = encoder_potencia.fit_transform(df["potencia"])

encoder_distrito = LabelEncoder()
df["distrito"] = encoder_distrito.fit_transform(df["distrito"])

joblib.dump(encoder_nroposte, "encoder_nroposte.pkl")
joblib.dump(encoder_luminaria, "encoder_luminaria.pkl")
joblib.dump(encoder_potencia, "encoder_potencia.pkl")
joblib.dump(encoder_distrito, "encoder_distrito.pkl")

X = df.drop(columns=["id", "estado_actual"])
y = df["estado_actual"]

scaler = StandardScaler()
X = scaler.fit_transform(X)

joblib.dump(scaler, "scaler.pkl")

# 3. Dividir datos en entrenamiento y prueba
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 4. Crear y entrenar el modelo de Bosque Aleatorio
model = RandomForestClassifier(n_estimators=100, random_state=42)  # Puedes ajustar los hiperparámetros
model.fit(X_train, y_train)

# 5. Guardar el modelo
joblib.dump(model, 'random_forest_model.pkl')

# 6. Evaluación del modelo (opcional)
from sklearn.metrics import accuracy_score

y_pred = model.predict(X_test)
accuracy = accuracy_score(y_test, y_pred)
print(f"Precisión del modelo: {accuracy * 100:.2f}%")

# Ejemplo de como cargar el modelo guardado.
loaded_model = joblib.load('random_forest_model.pkl')

# Ejemplo de como cargar el scaler guardado.
loaded_scaler = joblib.load('scaler.pkl')

# Ejemplo de como cargar los encoders guardados
loaded_encoder_nroposte = joblib.load('encoder_nroposte.pkl')
loaded_encoder_luminaria = joblib.load('encoder_luminaria.pkl')
loaded_encoder_potencia = joblib.load('encoder_potencia.pkl')
loaded_encoder_distrito = joblib.load('encoder_distrito.pkl')