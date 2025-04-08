from sqlalchemy import create_engine
import pandas as pd
import numpy as np
from tensorflow import keras
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense
from sklearn.model_selection import train_test_split
from sklearn.preprocessing import StandardScaler, LabelEncoder
from sklearn.linear_model import LogisticRegression
from sklearn.tree import DecisionTreeClassifier
from sklearn.ensemble import RandomForestClassifier
from sklearn.svm import SVC
import joblib
import matplotlib.pyplot as plt
from sklearn.metrics import (
    accuracy_score, precision_score, recall_score, f1_score,
    roc_curve, roc_auc_score, confusion_matrix, ConfusionMatrixDisplay
)

# 1️⃣ Conectar a MySQL usando SQLAlchemy
DB_USER = "root"
DB_PASS = ""
DB_HOST = "localhost"
DB_NAME = "electrica"

engine = create_engine(f"mysql+pymysql://{DB_USER}:{DB_PASS}@{DB_HOST}/{DB_NAME}")

# 2️⃣ Cargar datos desde MySQL
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

# 3️⃣ Preprocesamiento
df.fillna(0, inplace=True)
for col in ["nroposte", "luminaria", "potencia", "distrito"]:
    encoder = LabelEncoder()
    df[col] = encoder.fit_transform(df[col])
    joblib.dump(encoder, f"encoder_{col}.pkl")

X = df.drop(columns=["id", "estado_actual"])
y = df["estado_actual"]
scaler = StandardScaler()
X = scaler.fit_transform(X)
joblib.dump(scaler, "scaler.pkl")
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 4️⃣ Modelo de Red Neuronal
nn_model = Sequential([
    Dense(16, activation="relu", input_shape=(X_train.shape[1],)),  
    Dense(8, activation="relu"),
    Dense(1, activation="sigmoid")
])
nn_model.compile(optimizer="adam", loss="binary_crossentropy", metrics=["accuracy"])
nn_model.fit(X_train, y_train, epochs=50, batch_size=16, validation_data=(X_test, y_test))
nn_model.save("modelo_prediccion.h5")

# 5️⃣ Evaluar los 5 modelos
modelos = {
    "Red Neuronal": nn_model,
    "Logistic Regression": LogisticRegression(),
    "Decision Tree": DecisionTreeClassifier(),
    "Random Forest": RandomForestClassifier(),
    "SVM": SVC(probability=True)
}
resultados = {}

plt.figure(figsize=(10, 8))
for nombre, modelo in modelos.items():
    if nombre == "Red Neuronal":
        y_scores = modelo.predict(X_test).flatten()
    else:
        modelo.fit(X_train, y_train)
        y_scores = modelo.predict_proba(X_test)[:, 1]

    y_pred = (y_scores > 0.5).astype(int)
    acc = accuracy_score(y_test, y_pred)
    prec = precision_score(y_test, y_pred)
    rec = recall_score(y_test, y_pred)
    f1 = f1_score(y_test, y_pred)
    auc_val = roc_auc_score(y_test, y_scores)
    fpr, tpr, _ = roc_curve(y_test, y_scores)
    cm = confusion_matrix(y_test, y_pred)

    resultados[nombre] = {
        "Accuracy": acc,
        "Precision": prec,
        "Recall": rec,
        "F1-Score": f1,
        "AUC-ROC": auc_val,
        "Matriz de Confusión": cm
    }
    plt.plot(fpr, tpr, label=f'{nombre} (AUC = {auc_val:.2f})')

plt.plot([0, 1], [0, 1], 'k--')
plt.xlabel('Tasa de Falsos Positivos')
plt.ylabel('Tasa de Verdaderos Positivos')
plt.title('Curva ROC comparativa')
plt.legend(loc='lower right')
plt.grid(True)
plt.tight_layout()
plt.show()

resultados_texto = "\n"
for modelo, metricas in resultados.items():
    resultados_texto += f"\n📌 Modelo: {modelo}\n"
    for k, v in metricas.items():
        if k != "Matriz de Confusión":
            resultados_texto += f"{k}: {v:.2f}\n"
    resultados_texto += f"Matriz de Confusión:\n{metricas['Matriz de Confusión']}\n"

resultados_texto[:2000]  # Mostrar parte inicial del texto en respuesta

