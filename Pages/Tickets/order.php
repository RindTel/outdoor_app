<?php
require_once '../../config.php';
require_once __DIR__ . '/../../classes/TicketOrder.php';

require_once __DIR__ . '/../../classes/Place.php';

if (!isLoggedIn()) {
    header('Location: ../../Login/login.php');
    exit;
}

$conn = db_connect();


$places = Place::all();

$errors = [];
$successOrderId = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $placeId = isset($_POST['place_id']) ? (int)$_POST['place_id'] : 0;
    $visitDate = trim($_POST['visit_date'] ?? '');
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

    if ($placeId <= 0) {
        $errors[] = 'Please select a place.';
    }
    if ($visitDate === '') {
        $errors[] = 'Visit date is required.';
    }
    if ($quantity <= 0) {
        $errors[] = 'Quantity must be at least 1.';
    }

    if (!$errors) {
        try {
            $successOrderId = TicketOrder::createFromForm(
                $placeId,
                $visitDate,
                $quantity
            );
            header('Location: ticket_pdf.php?order_id=' . $successOrderId);
            exit;
        } catch (RuntimeException $e) {
            $errors[] = $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Tickets</title>
    <link rel="stylesheet" href="../Home/home.css">
    <style>
        .ticket-container {
            max-width: 600px;
            margin: 40px auto;
            background: #2b2e33;
            color: #efe6d8;
            padding: 24px;
            border-radius: 4px;
            font-family: 'Press Start 2P', monospace;
        }
        .ticket-container h1 {
            font-size: 18px;
            margin-bottom: 18px;
        }
        .ticket-form label {
            display: block;
            margin-top: 10px;
            font-size: 10px;
        }
        .ticket-form input,
        .ticket-form select {
            width: 100%;
            padding: 8px;
            margin-top: 4px;
            background: #2f3236;
            border: 2px solid #1f2224;
            color: #efe6d8;
            font-family: inherit;
            font-size: 11px;
        }
        .ticket-form button {
            margin-top: 18px;
            padding: 10px 16px;
            background: #5f7f87;
            border: 3px solid #232629;
            color: #efe6d8;
            cursor: pointer;
            font-family: inherit;
            font-size: 12px;
        }
        .errors {
            margin: 10px 0;
            color: #ff8c8c;
            font-size: 10px;
        }
        .errors li {
            margin-bottom: 4px;
        }
        .back-link {
            font-size: 10px;
            margin-bottom: 10px;
        }
        .back-link a {
            color: #efe6d8;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="back-link">
            <a href="../Home/home.php">&larr; Back to Home</a>
        </div>
        <h1>Order Tickets</h1>

        <?php if ($errors): ?>
            <ul class="errors">
                <?php foreach ($errors as $e): ?>
                    <li><?php echo htmlspecialchars($e); ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

            <form method="post" class="ticket-form">
            <label for="place_id">Place</label>
            <select name="place_id" id="place_id" required>
                <option value="">-- Select a place --</option>
                <?php foreach ($places as $p): ?>
                    <option value="<?php echo (int)$p->id; ?>">
                        <?php 
                            echo htmlspecialchars($p->name);
                            echo ' - ';
                            echo htmlspecialchars($p->location);
                        ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="visit_date">Visit Date</label>
            <input type="date" name="visit_date" id="visit_date" required>

            <label for="quantity">Tickets (people)</label>
            <input type="number" name="quantity" id="quantity" min="1" value="1" required>

            <button type="submit">Book Tickets &amp; Get PDF</button>
        </form>
    </div>
</body>
</html>
