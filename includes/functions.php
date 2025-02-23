<?php
// functions.php

/**
 * Load an array from a JSON file.
 * If file doesn't exist or is empty, return an empty array.
 */
function loadJson($path) {
    if (!file_exists($path)) {
        return [];
    }
    $json = file_get_contents($path);
    $data = json_decode($json, true);
    return is_array($data) ? $data : [];
}

/**
 * Save an array to a JSON file, overwriting existing content.
 */
function saveJson($path, $array) {
    file_put_contents($path, json_encode($array, JSON_PRETTY_PRINT));
}

/**
 * Get all webhooks from data/webhooks.json
 */
function getAllWebhooks() {
    return loadJson(__DIR__ . '/../data/webhooks.json');
}

/**
 * Save an array of webhooks to data/webhooks.json
 */
function saveAllWebhooks($webhooks) {
    saveJson(__DIR__ . '/../data/webhooks.json', $webhooks);
}

/**
 * Find a webhook by ID in the array
 */
function findWebhook($id, $webhooks) {
    foreach ($webhooks as $wh) {
        if ($wh['id'] == $id) {
            return $wh;
        }
    }
    return null;
}

/**
 * Send a message to a Discord webhook (with debug info)
 */
function sendDiscordWebhook($webhookUrl, $message) {
    // Prepare the JSON payload
    $payload = [
        'content' => $message,
        // You can also set 'username' or 'avatar_url' if you want to override them
    ];
    $jsonData = json_encode($payload);

    // Initialize cURL
    $ch = curl_init($webhookUrl);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Execute request
    $response = curl_exec($ch);

    // Gather error info and HTTP status code
    $error    = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cURL
    curl_close($ch);

    // Return detailed info for debugging
    return [
        'response' => $response,  // Discord's raw response (often empty on success)
        'error'    => $error,     // cURL error (if any)
        'httpCode' => $httpCode   // HTTP status code (e.g. 204 = success, 400/429 = error)
    ];
}
