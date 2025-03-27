# Importar librerías
from sqlalchemy import create_engine
import pandas as pd
import numpy as np
import tensorflow as tf
from tensorflow import keras
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler, LabelEncoder
import joblib
import matplotlib.pyplot as plt
from sklearn.metrics import roc_curve, roc_auc_score, confusion_matrix, ConfusionMatrixDisplay

# **1️⃣ Conectar a MySQL usando SQLAlchemy**
DB_USER = "alejandro"
DB_PASS = "Alej%4024"
DB_HOST = "192.168.154.20"
DB_NAME = "alumbrado"

engine = create_engine(f"mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}/{DB_NAME}")

# **2️⃣ Cargar datos desde MySQL**
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

# **3️⃣ Verificar tipos de datos**
print(df.dtypes)

# **4️⃣ Reemplazar valores nulos**
df.fillna(0, inplace=True)

# **5️⃣ Convertir texto a números**
encoder_nroposte = LabelEncoder()
df["nroposte"] = encoder_nroposte.fit_transform(df["nroposte"])

encoder_luminaria = LabelEncoder()
df["luminaria"] = encoder_luminaria.fit_transform(df["luminaria"])

encoder_potencia = LabelEncoder()
df["potencia"] = encoder_potencia.fit_transform(df["potencia"])

encoder_distrito = LabelEncoder()
df["distrito"] = encoder_distrito.fit_transform(df["distrito"])

# **Guardar los encoders**
joblib.dump(encoder_nroposte, "encoder_nroposte.pkl")
joblib.dump(encoder_luminaria, "encoder_luminaria.pkl")
joblib.dump(encoder_potencia, "encoder_potencia.pkl")
joblib.dump(encoder_distrito, "encoder_distrito.pkl")

# **6️⃣ Seleccionar características (X) y variable objetivo (y)**
X = df.drop(columns=["id", "estado_actual"])
y = df["estado_actual"]

# **7️⃣ Normalizar solo valores numéricos**
scaler = StandardScaler()
X = scaler.fit_transform(X)

# **Guardar el scaler**
joblib.dump(scaler, "scaler.pkl")

# **8️⃣ Dividir en entrenamiento (80%) y prueba (20%)**
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# **9️⃣ Crear la Red Neuronal**
model = keras.Sequential([
    keras.layers.Dense(16, activation="relu", input_shape=(X_train.shape[1],)),  
    keras.layers.Dense(8, activation="relu"),
    keras.layers.Dense(1, activation="sigmoid")  # Salida binaria: 0 o 1
])

model.compile(optimizer="adam", loss="binary_crossentropy", metrics=["accuracy"])

# ** Entrenar el modelo**
model.fit(X_train, y_train, epochs=50, batch_size=16, validation_data=(X_test, y_test))

# ** Guardar el modelo**
try:
    model.save("modelo_prediccion.h5")
    print("✅ Modelo guardado correctamente")
except Exception as e:
    print(f"❌ Error al guardar el modelo: {e}")

# ** Evaluación del modelo**
loss, accuracy = model.evaluate(X_test, y_test)
print(f" Precisión del modelo: {accuracy * 100:.2f}%")

# ** Generar Curva ROC y Matriz de Confusión**
model = keras.models.load_model("modelo_prediccion.h5")
scaler = joblib.load("scaler.pkl")

y_scores = model.predict(X_test).flatten()
fpr, tpr, thresholds = roc_curve(y_test, y_scores)
roc_auc = roc_auc_score(y_test, y_scores)

plt.figure()
plt.plot(fpr, tpr, label=f'AUC-ROC = {roc_auc:.2f}')
plt.plot([0, 1], [0, 1], 'k--')
plt.xlabel('Tasa de Falsos Positivos (FPR)')
plt.ylabel('Tasa de Verdaderos Positivos (TPR)')
plt.title('Curva ROC')
plt.legend(loc='lower right')
plt.show()

y_pred = (y_scores > 0.5).astype(int)
cm = confusion_matrix(y_test, y_pred)
disp = ConfusionMatrixDisplay(confusion_matrix=cm)
disp.plot()
plt.title('Matriz de Confusión')
plt.show()
