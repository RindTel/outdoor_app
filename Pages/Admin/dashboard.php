<?php
require_once '../../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../../Login/login.php');
    exit;
}

require_once __DIR__ . '/../../classes/ContactMessage.php';
require_once __DIR__ . '/../../classes/Place.php';

$contentMessage = '';
$placeMessage = '';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content_form'])) {
    $heroTitle = trim($_POST['hero_title'] ?? '');
    $heroBody  = trim($_POST['hero_body'] ?? '');
    $aboutTitle = trim($_POST['about_title'] ?? '');
    $aboutBody  = trim($_POST['about_body'] ?? '');

    $conn = getDBConnection();

    $stmt = $conn->prepare("INSERT INTO site_content (slug, title, body) VALUES ('home_hero', ?, ?)
        ON DUPLICATE KEY UPDATE title = VALUES(title), body = VALUES(body)");
    $stmt->bind_param('ss', $heroTitle, $heroBody);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO site_content (slug, title, body) VALUES ('about_text', ?, ?)
        ON DUPLICATE KEY UPDATE title = VALUES(title), body = VALUES(body)");
    $stmt->bind_param('ss', $aboutTitle, $aboutBody);
    $stmt->execute();
    $stmt->close();

    $conn->close();
    $contentMessage = 'Content saved.';
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_form'])) {
    $name = trim($_POST['place_name'] ?? '');
    $description = trim($_POST['place_description'] ?? '');
    $location = trim($_POST['place_location'] ?? '');
    $image = trim($_POST['place_image'] ?? '');

    if ($name && $description && $location) {
        try {
            Place::create(
                $name,
                $description,
                $location,
                $image ?: null
            );
            $placeMessage = 'Place created.';
        } catch (Throwable $e) {
            $placeMessage = 'Error creating place: ' . $e->getMessage();
        }
    } else {
        $placeMessage = 'Please fill in all required fields.';
    }
}


$contactMessages = ContactMessage::all();
$places = Place::all();


$conn = getDBConnection();
$heroContent = null;
$aboutContent = null;

$stmt = $conn->prepare("SELECT slug, title, body FROM site_content WHERE slug IN ('home_hero','about_text')");
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    if ($row['slug'] === 'home_hero') {
        $heroContent = $row;
    } elseif ($row['slug'] === 'about_text') {
        $aboutContent = $row;
    }
}
$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="../Home/home.css">
    <style>
        .admin-wrapper {
            max-width: 1100px;
            margin: 40px auto;
            color: #efe6d8;
            font-family: 'Press Start 2P', monospace;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        .admin-header h1 {
            font-size: 16px;
        }
        .admin-header a {
            color: #efe6d8;
            text-decoration: none;
            font-size: 10px;
        }
        .admin-section {
            background: #2b2e33;
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 4px;
        }
        .admin-section h2 {
            font-size: 13px;
            margin-bottom: 10px;
        }
        table.admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        table.admin-table th,
        table.admin-table td {
            border: 1px solid #444;
            padding: 6px 4px;
            text-align: left;
        }
        table.admin-table th {
            background: #1f2224;
        }
        .admin-tag {
            display: inline-block;
            padding: 2px 4px;
            border-radius: 3px;
            font-size: 8px;
            background: #5f7f87;
            border: 1px solid #232629;
        }
        .admin-form label {
            display: block;
            font-size: 9px;
            margin-top: 8px;
        }
        .admin-form input,
        .admin-form textarea {
            width: 100%;
            padding: 6px;
            margin-top: 2px;
            background: #1f2224;
            border: 1px solid #444;
            color: #efe6d8;
            font-family: inherit;
            font-size: 9px;
            box-sizing: border-box;
        }
        .admin-form textarea {
            min-height: 70px;
        }
        .admin-form button {
            margin-top: 10px;
            padding: 6px 10px;
            background: #5f7f87;
            border: 2px solid #232629;
            color: #efe6d8;
            font-family: inherit;
            font-size: 10px;
            cursor: pointer;
        }
        .admin-note {
            font-size: 8px;
            margin-top: 6px;
        }
        .admin-flash {
            font-size: 9px;
            margin-bottom: 6px;
            color: #9be7c4;
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <div class="admin-header">
            <h1>Admin Dashboard</h1>
            <div>
                <span class="admin-tag">Admin: <?php echo htmlspecialchars(getCurrentUsername() ?? ''); ?></span>
                &nbsp;
                <a href="../Home/home.php">← Back to site</a>
                &nbsp;
                <a href="../../api/logout.php">Logout</a>
            </div>
        </div>

        <div class="admin-section">
            <h2>Homepage / About Content</h2>
            <?php if ($contentMessage): ?>
                <div class="admin-flash"><?php echo htmlspecialchars($contentMessage); ?></div>
            <?php endif; ?>
            <form method="post" class="admin-form">
                <input type="hidden" name="content_form" value="1">

                <label for="hero_title">Hero title</label>
                <input id="hero_title" name="hero_title" type="text"
                       value="<?php echo htmlspecialchars($heroContent['title'] ?? ''); ?>">

                <label for="hero_body">Hero subtitle / body</label>
                <textarea id="hero_body" name="hero_body"><?php echo htmlspecialchars($heroContent['body'] ?? ''); ?></textarea>

                <label for="about_title">About title</label>
                <input id="about_title" name="about_title" type="text"
                       value="<?php echo htmlspecialchars($aboutContent['title'] ?? ''); ?>">

                <label for="about_body">About text</label>
                <textarea id="about_body" name="about_body"><?php echo htmlspecialchars($aboutContent['body'] ?? ''); ?></textarea>

                <button type="submit">Save Content</button>
                <div class="admin-note">
                    This content is what you see on the homepage hero and About section.
                </div>
            </form>
        </div>

        <div class="admin-section">
            <h2>Contact Messages</h2>
            <?php if (empty($contactMessages)): ?>
                <p style="font-size: 9px;">No contact messages yet.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>User ID</th>
                            <th>Created At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contactMessages as $m): ?>
                            <tr>
                                <td><?php echo (int)$m->id; ?></td>
                                <td><?php echo htmlspecialchars($m->name); ?></td>
                                <td><?php echo htmlspecialchars($m->email); ?></td>
                                <td><?php echo nl2br(htmlspecialchars($m->message)); ?></td>
                                <td><?php echo $m->userId !== null ? (int)$m->userId : '-'; ?></td>
                                <td><?php echo htmlspecialchars($m->createdAt); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <div class="admin-section">
            <h2>Places (Content Items)</h2>
            <?php if (empty($places)): ?>
                <p style="font-size: 9px;">No places found.</p>
            <?php else: ?>
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Location</th>
                            <th>Image</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($places as $p): ?>
                            <tr>
                                <td><?php echo (int)$p->id; ?></td>
                                <td><?php echo htmlspecialchars($p->name); ?></td>
                                <td><?php echo htmlspecialchars(substr($p->description, 0, 50)) . '...'; ?></td>
                                <td><?php echo htmlspecialchars($p->location); ?></td>
                                <td><?php echo htmlspecialchars($p->image ?? '-'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <p style="font-size: 8px; margin-top: 8px;">
                All places are shown here. You can add new places using the form below.
            </p>

            <?php if ($placeMessage): ?>
                <div class="admin-flash"><?php echo htmlspecialchars($placeMessage); ?></div>
            <?php endif; ?>

            <form method="post" class="admin-form">
                <input type="hidden" name="place_form" value="1">

                <label for="place_name">Place name (unique)</label>
                <input id="place_name" name="place_name" type="text" required>

                <label for="place_description">Description</label>
                <textarea id="place_description" name="place_description" required></textarea>

                <label for="place_location">Location</label>
                <input id="place_location" name="place_location" type="text" required>

                <label for="place_image">Image filename (e.g. Balcony1.png)</label>
                <input id="place_image" name="place_image" type="text">

                <button type="submit">Add Place</button>
                <div class="admin-note">
                    Newly created places will appear on the website.
                </div>
            </form>
        </div>
    </div>
</body>
</html>

