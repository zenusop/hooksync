<?php
// send_webhook.php

// Get the message from the POST request.
$message = $_POST['message'] ?? '';

if (empty($message)) {
    echo json_encode(['status' => 'error', 'error' => 'No message provided']);
    exit;
}

// Set the Python service URL (adjust to your deployed URL).
$pythonServiceURL = 'https://hooksync.onrender.com/send-webhook';

// Prepare the data payload.
$data = json_encode(['message' => $message]);

// Initialize cURL.
$ch = curl_init($pythonServiceURL);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Content-Length: ' . strlen($data)
]);

$response = curl_exec($ch);
curl_close($ch);

// Output the response.
header('Content-Type: application/json');
echo $response;

