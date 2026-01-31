<?php
require_once '../../config.php';

if (!isLoggedIn() || !isAdmin()) {
    header('Location: ../../Login/login.php');
    exit;
}

require_once __DIR__ . '/../../classes/ContactMessage.php';
require_once __DIR__ . '/../../classes/Place.php';
require_once __DIR__ . '/../../classes/User.php';

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
    $title = trim($_POST['place_title'] ?? '');
    $description = trim($_POST['place_description'] ?? '');
    $image = trim($_POST['place_image'] ?? '');
    $posX = (int)($_POST['place_x'] ?? 0);
    $posY = (int)($_POST['place_y'] ?? 0);
    $price = (float)($_POST['place_price'] ?? 0);

    if ($name && $title && $description) {
        try {
            Place::create(
                $name,
                $title,
                $description,
                $image ?: null,
                $posX,
                $posY,
                $price
            );
            $placeMessage = 'Place created.';
        } catch (Throwable $e) {
            $placeMessage = 'Error creating place: ' . $e->getMessage();
        }
    } else {
        $placeMessage = 'Please fill in required fields (Name, Title, Description).';
    }
}

$contactMessages = ContactMessage::all();
$places = Place::all();
$users = User::all();


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
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Home/home.css">
    <style>
        .admin-wrapper {
            max-width: 1200px;
            margin: 100px auto 40px auto; 
            color: #efe6d8;
            font-family: 'Press Start 2P', monospace;
            padding: 0 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            border-bottom: 4px solid #1e2024;
            padding-bottom: 20px;
        }
        .admin-header h1 {
            font-size: 20px;
            text-shadow: 2px 2px 0 #000;
        }
        .admin-section {
            background: #2b2e33;
            padding: 24px;
            margin-bottom: 32px;
            border: 4px solid #1e2024;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }
        .admin-section h2 {
            font-size: 16px;
            margin-bottom: 20px;
            color: #5f7f87;
            text-transform: uppercase;
            border-bottom: 2px solid #1e2024;
            padding-bottom: 10px;
        }
        table.admin-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 10px;
            margin-bottom: 20px;
        }
        table.admin-table th,
        table.admin-table td {
            border: 2px solid #1e2024;
            padding: 10px;
            text-align: left;
            background: #26282b;
        }
        table.admin-table th {
            background: #1f2224;
            color: #9be7c4;
            text-shadow: 1px 1px 0 #000;
        }
        .admin-form label {
            display: block;
            font-size: 10px;
            margin-top: 12px;
            color: #cfcfcf;
        }
        .admin-form input,
        .admin-form textarea {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background: #1b1d20;
            border: 2px solid #000;
            color: #efe6d8;
            font-family: inherit;
            font-size: 10px;
            box-sizing: border-box;
            border-radius: 4px;
        }
        .admin-form button {
            margin-top: 20px;
            padding: 12px 20px;
            background: #5f7f87;
            border: 2px solid #1e2024;
            color: white;
            font-family: inherit;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .admin-form button:hover {
            background: #7ca5ad;
            transform: translateY(-2px);
        }
        .admin-flash {
            padding: 10px;
            background: rgba(155, 231, 196, 0.1);
            border: 1px solid #9be7c4;
            color: #9be7c4;
            margin-bottom: 15px;
            font-size: 10px;
        }
    </style>
</head>
<body>

<nav>
    <div class="nav-links">
        <a href="../Home/home.php#home">Home</a>
        <a href="../Home/home.php#map">Map</a>
        <a href="../Home/home.php#about">About</a>
        <a href="../Home/home.php#contact">Contact</a>
    </div>

    <div class="auth">
        <button class="auth-btn"><?php echo htmlspecialchars(getCurrentUsername()); ?> (Admin) ▾</button>
        <div class="auth-dropdown">
            <a href="../../Pages/Tickets/my_tickets.php">My Tickets</a>
            <a href="../../api/logout.php">Logout</a>
        </div>
    </div>
</nav>

<div class="admin-wrapper">
    <div class="admin-header">
        <h1>Dashboard</h1>
    </div>

    
    <div class="admin-section">
        <h2>Users</h2>
        <?php if (empty($users)): ?>
            <p style="font-size: 10px;">No users found.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                        <tr>
                            <td><?php echo (int)$u->id; ?></td>
                            <td><?php echo htmlspecialchars($u->username); ?></td>
                            <td><?php echo htmlspecialchars($u->email); ?></td>
                            <td><?php echo htmlspecialchars($u->role); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    
    <div class="admin-section">
        <h2>Locations</h2>
        <?php if ($placeMessage): ?>
            <div class="admin-flash"><?php echo htmlspecialchars($placeMessage); ?></div>
        <?php endif; ?>
        
        <?php if (empty($places)): ?>
            <p style="font-size: 10px;">No places found.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Title</th>
                        <th>Pos (X,Y)</th>
                        <th>Price</th>
                        <th>Image</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($places as $p): ?>
                        <tr>
                            <td><?php echo (int)$p->id; ?></td>
                            <td><?php echo htmlspecialchars($p->name); ?></td>
                            <td><?php echo htmlspecialchars($p->title); ?></td>
                            <td><?php echo $p->position_x . ', ' . $p->position_y; ?></td>
                            <td>$<?php echo number_format($p->ticket_price, 2); ?></td>
                            <td><?php echo htmlspecialchars($p->image ?? '-'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <h3>Add New Location</h3>
        <form method="post" class="admin-form">
            <input type="hidden" name="place_form" value="1">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div>
                    <label>Unique Name (for URL)</label>
                    <input name="place_name" type="text" placeholder="e.g. Cinema" required>

                    <label>Display Title</label>
                    <input name="place_title" type="text" placeholder="e.g. Retro Cinema" required>
                    
                    <label>Image Filename</label>
                    <input name="place_image" type="text" placeholder="image.png">
                </div>
                <div>
                     <label>Position X</label>
                    <input name="place_x" type="number" value="0">
                    
                    <label>Position Y</label>
                    <input name="place_y" type="number" value="0">
                    
                    <label>Ticket Price</label>
                    <input name="place_price" type="number" step="0.01" value="0.00">
                </div>
            </div>

            <label>Description</label>
            <textarea name="place_description" rows="4" required></textarea>

            <button type="submit">Create Location</button>
        </form>
    </div>

    
    <div class="admin-section">
        <h2>Contact Messages</h2>
        <?php if (empty($contactMessages)): ?>
            <p style="font-size: 10px;">No messages received.</p>
        <?php else: ?>
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contactMessages as $m): ?>
                        <tr>
                            <td><?php echo date('Y-m-d', strtotime($m->createdAt)); ?></td>
                            <td><?php echo htmlspecialchars($m->name); ?></td>
                            <td><?php echo htmlspecialchars($m->email); ?></td>
                            <td><?php echo nl2br(htmlspecialchars($m->message)); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    
    <div class="admin-section">
        <h2>Site Content</h2>
        <?php if ($contentMessage): ?>
            <div class="admin-flash"><?php echo htmlspecialchars($contentMessage); ?></div>
        <?php endif; ?>
        <form method="post" class="admin-form">
            <input type="hidden" name="content_form" value="1">

            <label>Hero Title</label>
            <input name="hero_title" type="text" value="<?php echo htmlspecialchars($heroContent['title'] ?? ''); ?>">

            <label>Hero Body</label>
            <textarea name="hero_body"><?php echo htmlspecialchars($heroContent['body'] ?? ''); ?></textarea>

            <label>About Title</label>
            <input name="about_title" type="text" value="<?php echo htmlspecialchars($aboutContent['title'] ?? ''); ?>">

            <label>About Body</label>
            <textarea name="about_body"><?php echo htmlspecialchars($aboutContent['body'] ?? ''); ?></textarea>

            <button type="submit">Update Content</button>
        </form>
    </div>

</div>

</body>
</html>
