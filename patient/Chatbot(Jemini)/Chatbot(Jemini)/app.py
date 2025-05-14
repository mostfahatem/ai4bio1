from flask import Flask, render_template, request, jsonify
import google.generativeai as genai
import os

app = Flask(__name__)

# Configure the API key
GOOGLE_API_KEY = "AIzaSyCBLrECZ4a2XYZP-JdQlLT-USBUnIErrdo"
os.environ["GOOGLE_API_KEY"] = GOOGLE_API_KEY
genai.configure(api_key=GOOGLE_API_KEY)

# Initialize the generative model
model = genai.GenerativeModel("gemini-pro")
chat = model.start_chat()

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/send_message', methods=['POST'])
def send_message():
    user_input = request.json['message']
    if user_input.strip():
        response = chat.send_message(user_input)
        return jsonify({'response': response.text})
    return jsonify({'response': ''})

if __name__ == '__main__':
    app.run(host="127.0.0.1", port=9000)
