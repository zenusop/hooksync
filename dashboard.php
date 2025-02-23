<?php
// dashboard.php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

require_once __DIR__ . '/includes/functions.php';

$webhooks = getAllWebhooks();

// Handle Create
if (isset($_POST['action']) && $_POST['action'] === 'create') {
    $name = $_POST['name'] ?? '';
    $url  = $_POST['url'] ?? '';

    // Basic validation
    if (!empty($name) && !empty($url)) {
        // Create a new ID
        $id = time(); // or any unique generator
        $webhooks[] = [
            'id'   => $id,
            'name' => $name,
            'url'  => $url
        ];
        saveAllWebhooks($webhooks);
        $message = "Webhook created successfully.";
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
                    <!-- Edit form (inline for brevity) -->
                    <form style="display:inline;" method="post" action="dashboard.php">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="id" value="<?php echo $wh['id']; ?>">
                        <input type="text" name="name" value="<?php echo htmlspecialchars($wh['name']); ?>">
                        <input type="text" name="url" value="<?php echo htmlspecialchars($wh['url']); ?>">
                        <button type="submit">Save</button>
                    </form>
                    &nbsp;|&nbsp;
                    <a href="dashboard.php?delete=<?php echo $wh['id']; ?>" 
                       onclick="return confirm('Delete this webhook?');">Delete</a>
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
</body>
</html>

