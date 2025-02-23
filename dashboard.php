<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/includes/functions.php';

$webhooks = getAllWebhooks();
$message = '';

// Handle Create
if (isset($_POST['action']) && $_POST['action'] === 'create') {
$name = $_POST['name'] ?? '';
$url = $_POST['url'] ?? '';

// Basic validation
if (!empty($name) && !empty($url)) {
// Create a new ID
$id = time(); // or any unique generator
$webhooks[] = [
'id' => $id,
'name' => $name,
'url' => $url
];
saveAllWebhooks($webhooks);
$message = "Webhook created successfully.";
}
}

// Handle Update
if (isset($_POST['action']) && $_POST['action'] === 'update') {
$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$url = $_POST['url'] ?? '';

foreach ($webhooks as &$wh) {
if ($wh['id'] == $id) {
$wh['name'] = $name;
$wh['url'] = $url;
saveAllWebhooks($webhooks);
$message = "Webhook updated.";
break;
}
}
}

// Handle Delete
if (isset($_GET['delete'])) {
$deleteId = $_GET['delete'];
$webhooks = array_filter($webhooks, function($wh) use ($deleteId) {
return $wh['id'] != $deleteId;
});
saveAllWebhooks($webhooks);
$message = "Webhook deleted.";
}

// Handle Send Message
if (isset($_POST['action']) && $_POST['action'] === 'send') {
    $id     = $_POST['webhook_id'] ?? '';
    $msg    = $_POST['message'] ?? '';
    $count  = (int)($_POST['count'] ?? 1);
    $delay  = (int)($_POST['delay'] ?? 0);

    // Basic checks
    if ($count < 1) $count = 1;
    if ($delay < 0) $delay = 0;

    // Find the webhook in the JSON
    $wh = findWebhook($id, $webhooks);
    if (!$wh) {
        $message = "Webhook not found.";
    } else {
        // Send the message multiple times, with a delay
        for ($i = 0; $i < $count; $i++) {
            $resp = sendDiscordWebhook($wh['url'], $msg);
            // Optional: handle $resp if needed

            // Sleep if not the last message
            if ($i < $count - 1) {
                sleep($delay);
            }
        }
        $message = "Message(s) sent successfully.";
    }
}

// Reload updated data
$webhooks = getAllWebhooks();
?>
<!DOCTYPE html>
<html>
<head>
    <title>HookSync Dashboard</title>
</head>
<body>
    <h1>HookSync Dashboard</h1>
    <p><a href="logout.php">Logout</a></p>
    <?php if (!empty($message)): ?>
        <p style="color:green;"><?php echo htmlspecialchars($message); ?></p>
    <?php endif; ?>

    <!-- Existing table for listing/editing/deleting webhooks goes here... -->

    <h2>Send Message</h2>
    <form method="post" action="dashboard.php">
        <input type="hidden" name="action" value="send">
        
        <label for="webhook_id">Select Webhook:</label>
        <select name="webhook_id" id="webhook_id" required>
            <option value="">--Choose Webhook--</option>
            <?php foreach ($webhooks as $wh): ?>
                <option value="<?php echo htmlspecialchars($wh['id']); ?>">
                    <?php echo htmlspecialchars($wh['name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label for="message">Message Content:</label>
        <input type="text" name="message" id="message" required>
        <br><br>

        <label for="count">Number of Messages:</label>
        <input type="number" name="count" id="count" value="1" min="1">
        <br><br>

        <label for="delay">Delay (seconds):</label>
        <input type="number" name="delay" id="delay" value="0" min="0">
        <br><br>

        <button type="submit">Send</button>
    </form>
</body>
</html>
