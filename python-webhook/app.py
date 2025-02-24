from flask import Flask, request, jsonify
from flask_cors import CORS
import requests
import os

app = Flask(__name__)
CORS(app)  # This enables CORS for all routes

DISCORD_WEBHOOK_URL = os.environ.get("DISCORD_WEBHOOK_URL", "https://discord.com/api/webhooks/your_default_url")

@app.route('/send-webhook', methods=['POST'])
def send_webhook():
    data = request.get_json()
    message = data.get("message")
    
    if not message:
        return jsonify({"status": "error", "error": "No message provided"}), 400

    payload = {"content": message}
    response = requests.post(DISCORD_WEBHOOK_URL, json=payload)
    
    if response.status_code == 204:
        return jsonify({"status": "success"}), 200
    else:
        return jsonify({"status": "error", "error": response.text}), 500

if __name__ == '__main__':
    port = int(os.environ.get("PORT", 5000))
    app.run(host='0.0.0.0', port=port)
