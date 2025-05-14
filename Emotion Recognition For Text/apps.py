from flask import Flask, render_template, request, redirect, url_for
import joblib
import pandas as pd
import numpy as np
from datetime import datetime
import altair as alt
import pymysql

# إعداد الاتصال بقاعدة البيانات
db = pymysql.connect(
    host="localhost",
    user="root",
    password="",
    database="SQL_Database_edoc"
)
cursor = db.cursor()


# Load Model
pipe_lr = joblib.load(open(r"C:\xampp\htdocs\HelthCare-System-main mostfa\Emotion Recognition For Text\model\text_emotion.pkl", "rb"))

# Track Utils
emotions_emoji_dict = {"anger": "😠", "disgust": "🤮", "fear": "😨😱", "happy": "🤗", "joy": "😂", "neutral": "😐", "sad": "😔", "sadness": "😔", "shame": "😳", "surprise": "😮"}

# Flask Setup
app = Flask(__name__)

# Function to predict emotions
def predict_emotions(docx):
    results = pipe_lr.predict([docx])
    return results[0]

def get_prediction_proba(docx):
    results = pipe_lr.predict_proba([docx])
    return results

@app.route('/', methods=['GET', 'POST'])
def home():
    if request.method == 'POST':
        # استلام البيانات من النموذج
        patient_id = request.form['Patient_ID']
        patient_name = request.form['patient_name']
        visit_date = request.form['visit_date']
        patient_feedback = request.form['patient_feedback']
        service_rating = request.form['service_rating']
        need_support = request.form['need_support']
        overall_experience = request.form['overall_experience']
        wait_time = request.form['wait_time']
        staff_courtesy = request.form['staff_courtesy']
        facilities_rating = request.form['facilities_rating']
        would_recommend = request.form['would_recommend']
        doctor_name = request.form['doctor']

        # تطبيق خوارزمية التنبؤ
        prediction = predict_emotions(patient_feedback)
        probability = get_prediction_proba(patient_feedback)
        confidence = float(np.max(probability))

        try:
            # إدخال بيانات التقييم في جدول `results`
            cursor.execute("""
                INSERT INTO results (patient_id, service_rating, need_support, overall_experience,
                                     wait_time, staff_courtesy, facilities_rating, would_recommend,
                                     feedback, prediction, confidence, doctor)
                VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)
            """, (patient_id, service_rating, need_support, overall_experience, wait_time,
                  staff_courtesy, facilities_rating, would_recommend, patient_feedback,
                  prediction, confidence, doctor_name))
            db.commit()

        except Exception as e:
            db.rollback()
            return f"Error: {str(e)}"

        # إعادة التوجيه إلى صفحة الشكر
        return redirect(url_for('thank_you', 
                                patient_name=patient_name, 
                                prediction=prediction, 
                                confidence=confidence))

    return render_template('index.html')

@app.route('/thank_you')
def thank_you():
    patient_name = request.args.get('patient_name')
    prediction = request.args.get('prediction')
    confidence = request.args.get('confidence')
    return render_template('thank_you.html', 
                           patient_name=patient_name, 
                           prediction=prediction, 
                           confidence=confidence)


if __name__ == '__main__':
    app.run(debug=True)
