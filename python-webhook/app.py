from flask import Flask, request, jsonify
import requests
import os

app = Flask(__name__)

# Set your Discord webhook URL as an environment variable in Render.
DISCORD_WEBHOOK_URL = os.environ.get("https://discord.com/api/webhooks/1341946734376521749/4BQRcnmgZqFvMAfOkMV7GPELprW6xGcXo-YnOE4y515s_TmSsl6Jl69iQHVVx76xMuLn", "https://discord.com/api/webhooks/your_default_url")

@app.route('/send-webhook', methods=['POST'])
def send_webhook():
    data = request.get_json()
    message = data.get("message")
    
    if not message:
        return jsonify({"status": "error", "error": "No message provided"}), 400

    payload = {
        "content": message
    }
    
    response = requests.post(DISCORD_WEBHOOK_URL, json=payload)
    
    if response.status_code == 204:  # Discord returns 204 No Content on success.
        return jsonify({"status": "success"}), 200
    else:
        return jsonify({"status": "error", "error": response.text}), 500

if __name__ == '__main__':
    # Bind to 0.0.0.0 so Render can route traffic to the container.
    port = int(os.environ.get("PORT", 5000))
    app.run(host='0.0.0.0', port=port)

