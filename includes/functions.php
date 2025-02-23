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
