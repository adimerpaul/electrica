import pandas as pd
from sklearn.model_selection import train_test_split
from sklearn.ensemble import RandomForestClassifier
from sklearn.preprocessing import StandardScaler, LabelEncoder
import joblib
from sqlalchemy import create_engine
import matplotlib.pyplot as plt
from sklearn.metrics import accuracy_score, precision_score, recall_score, f1_score, roc_curve, roc_auc_score, confusion_matrix, ConfusionMatrixDisplay


# 1. Cargar datos desde MySQL
DB_USER = "root"
DB_PASS = ""
DB_HOST = "localhost"
DB_NAME = "electrica"

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

# 2. Preprocesamiento de datos
df.fillna(0, inplace=True)

encoder_nroposte = LabelEncoder()
df["nroposte"] = encoder_nroposte.fit_transform(df["nroposte"])

encoder_luminaria = LabelEncoder()
df["luminaria"] = encoder_luminaria.fit_transform(df["luminaria"])

encoder_potencia = LabelEncoder()
df["potencia"] = encoder_potencia.fit_transform(df["potencia"])

encoder_distrito = LabelEncoder()
df["distrito"] = encoder_distrito.fit_transform(df["distrito"])

# Guardar los codificadores para uso futuro
joblib.dump(encoder_nroposte, "encoder_nroposte.pkl")
joblib.dump(encoder_luminaria, "encoder_luminaria.pkl")
joblib.dump(encoder_potencia, "encoder_potencia.pkl")
joblib.dump(encoder_distrito, "encoder_distrito.pkl")

# Definir características (X) y variable objetivo (y)
X = df.drop(columns=["id", "estado_actual"])
y = df["estado_actual"]

# Escalar los datos
scaler = StandardScaler()
X = scaler.fit_transform(X)

# Guardar el escalador para su uso futuro
joblib.dump(scaler, "scaler.pkl")

# 3. Dividir los datos en conjunto de entrenamiento y prueba
X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.2, random_state=42)

# 4. Crear y entrenar el modelo de Bosque Aleatorio
model = RandomForestClassifier(n_estimators=100, random_state=42)
model.fit(X_train, y_train)

# 5. Guardar el modelo entrenado
joblib.dump(model, 'random_forest_model.pkl')

# 6. Evaluación del modelo
y_pred = model.predict(X_test)
accuracy = accuracy_score(y_test, y_pred)
print(f"Precisión del modelo: {accuracy * 100:.2f}%")

# 7. Generar Curva ROC y Matriz de Confusión
y_scores = model.predict_proba(X_test)[:, 1]  # Probabilidades para la clase positiva

# Calcular la Curva ROC y AUC
fpr, tpr, thresholds = roc_curve(y_test, y_scores)
roc_auc = roc_auc_score(y_test, y_scores)
print(f"AUC-ROC: {roc_auc:.2f}")

# Graficar la Curva ROC
plt.figure()
plt.plot(fpr, tpr, label=f'AUC-ROC = {roc_auc:.2f}')
plt.plot([0, 1], [0, 1], 'k--')
plt.xlabel('Tasa de Falsos Positivos (FPR)')
plt.ylabel('Tasa de Verdaderos Positivos (TPR)')
plt.title('Curva ROC')
plt.legend(loc='lower right')
plt.show()

# Calcular la Matriz de Confusión
cm = confusion_matrix(y_test, y_pred)
disp = ConfusionMatrixDisplay(confusion_matrix=cm)
disp.plot()
plt.title('Matriz de Confusión')
plt.show()

# 8. Evaluación de métricas adicionales
precision = precision_score(y_test, y_pred)
recall = recall_score(y_test, y_pred)
f1 = f1_score(y_test, y_pred)

print(f"Precisión: {precision:.2f}")
print(f"Recall: {recall:.2f}")
print(f"F1-Score: {f1:.2f}")