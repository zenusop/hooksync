<?php
// dashboard.php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/includes/functions.php';

$webhooks = getAllWebhooks();
$message  = '';

// Handle Create (Add Webhook)
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $name = $_POST['name'] ?? '';
    $url  = $_POST['url'] ?? '';

    // Basic validation
    if (!empty($name) && !empty($url)) {
        // Generate a unique ID (could be time-based, random, etc.)
        $id = time();
        $webhooks[] = [
            'id'   => $id,
            'name' => $name,
            'url'  => $url
        ];
        saveAllWebhooks($webhooks);
        $message = "Webhook created successfully.";
    } else {
        $message = "Name and URL cannot be empty.";
    }
}

// Handle Update
if (isset($_POST['action']) && $_POST['action'] === 'update') {
    $id   = $_POST['id'] ?? '';
    $name = $_POST['name'] ?? '';
    $url  = $_POST['url'] ?? '';

    foreach ($webhooks as &$wh) {
        if ($wh['id'] == $id) {
            $wh['name'] = $name;
            $wh['url']  = $url;
            saveAllWebhooks($webhooks);
            $message = "Webhook updated successfully.";
            break;
        }
    }
    unset($wh); // break reference
}

// Handle Delete
if (isset($_GET['delete'])) {
    $deleteId = $_GET['delete'];
    $webhooks = array_filter($webhooks, function($wh) use ($deleteId) {
        return $wh['id'] != $deleteId;
    });
    saveAllWebhooks($webhooks);
    $message = "Webhook deleted successfully.";
}

// Handle Send Message
if (isset($_POST['action']) && $_POST['action'] === 'send') {
    $id     = $_POST['webhook_id'] ?? '';
    $msg    = $_POST['message'] ?? '';
    $count  = (int)($_POST['count'] ?? 1);
    $delay  = (int)($_POST['delay'] ?? 0);

    // Validate count/delay
    if ($count < 1) $count = 1;
    if ($delay < 0) $delay = 0;

    // Find the selected webhook
    $wh = findWebhook($id, $webhooks);
    if (!$wh) {
        $message = "Webhook not found.";
    } else {
        // Send the message multiple times
        for ($i = 0; $i < $count; $i++) {
            $resp = sendDiscordWebhook($wh['url'], $msg);

            // --- Debug lines (remove or comment out after troubleshooting) ---
            echo "<pre>";
            echo "Response: " . htmlspecialchars($resp['response']) . "\n";
            echo "Error: " . htmlspecialchars($resp['error']) . "\n";
            echo "HTTP Code: " . htmlspecialchars($resp['httpCode']) . "\n";
            echo "</pre>";
            // --- End Debug lines ---

            // Sleep only if not the last message
            if ($i < $count - 1) {
                sleep($delay);
            }
        }
        $message = "Message(s) sent successfully.";
    }
}

// Reload the updated webhooks list
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

    <h2>Existing Webhooks</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>URL</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($webhooks as $wh): ?>
            <tr>
                <td><?php echo htmlspecialchars($wh['id']); ?></td>
                <td><?php echo htmlspecialchars($wh['name']); ?></td>
                <td><?php echo htmlspecialchars($wh['url']); ?></td>
                <td>
                    <!-- Inline Edit Form -->
                    <form style="display:inline;" method="post" action="dashboard.php">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $wh['id']; ?>">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($wh['name']); ?>">
                        <input type="text" name="url" value="<?php echo htmlspecialchars($wh['url']); ?>">
                        <button type="submit">Save</button>
                    </form>
                    &nbsp;|&nbsp;
                    <!-- Delete Link -->
                    <a href="dashboard.php?delete=<?php echo $wh['id']; ?>" 
                       onclick="return confirm('Delete this webhook?');">
                       Delete
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <h2>Create New Webhook</h2>
    <form method="post" action="dashboard.php">
        <input type="hidden" name="action" value="create">
        <label for="name">Name:</label>
        <input type="text" name="name" id="name" required>

        <label for="url">URL:</label>
        <input type="text" name="url" id="url" required>

        <button type="submit">Add</button>
    </form>

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
