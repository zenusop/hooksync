document.getElementById('webhookForm').addEventListener('submit', function(e) {
  e.preventDefault();
  var message = this.message.value;

  // Replace with your deployed Python service URL:
  var pythonServiceURL = 'https://your-python-service.onrender.com/send-webhook';

  fetch(pythonServiceURL, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ message: message })
  })
  .then(response => response.json())
  .then(data => {
    document.getElementById('result').innerText = data.status;
  })
  .catch(err => {
    document.getElementById('result').innerText = 'Error: ' + err;
  });
});

