<?php
require_once '../../config.php';
require_once __DIR__ . '/../../classes/TicketOrder.php';

if (!isLoggedIn()) {
    header('Location: ../../Login/login.php');
    exit;
}

$userId = getCurrentUserId();
$tickets = TicketOrder::getAllByUserId($userId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tickets</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Home/home.css">
    <style>
        .tickets-container {
            max-width: 800px;
            margin: 40px auto;
            padding: 20px;
        }
        .page-title {
            color: #efe6d8;
            font-family: 'Press Start 2P', monospace;
            font-size: 24px;
            margin-bottom: 30px;
            text-align: center;
            text-shadow: 4px 4px 0 #000;
        }
        .ticket-card {
            background: #2b2e33;
            border: 4px solid #000;
            margin-bottom: 20px;
            display: flex;
            box-shadow: 8px 8px 0 rgba(0,0,0,0.5);
            transition: transform 0.2s;
        }
        .ticket-card:hover {
            transform: translate(-2px, -2px);
            box-shadow: 10px 10px 0 rgba(0,0,0,0.5);
        }
        .ticket-image {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-right: 4px solid #000;
            background: #000;
        }
        .ticket-info {
            padding: 20px;
            flex-grow: 1;
            color: #efe6d8;
            font-family: 'Press Start 2P', monospace;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .ticket-place {
            color: #9be7c4;
            font-size: 14px;
            margin-bottom: 15px;
        }
        .ticket-detail {
            font-size: 10px;
            margin-bottom: 8px;
            line-height: 1.5;
        }
        .ticket-price {
            font-size: 12px;
            color: #ff9d00;
            margin-top: 10px;
        }
        .no-tickets {
            text-align: center;
            color: #888;
            font-family: 'Press Start 2P', monospace;
            font-size: 12px;
            margin-top: 50px;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            font-family: 'Press Start 2P', monospace;
            font-size: 10px;
            color: #efe6d8;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <?php include '../Home/navbar.php'; ?>
    
    <div class="tickets-container">
        <h1 class="page-title">MY TICKETS</h1> <br><br>

        <?php if (empty($tickets)): ?>
            <div class="no-tickets">
                <p>No tickets found.</p>
                <br>
                <a href="../Home/home.php" style="color: #9be7c4;">Browse Locations</a>
            </div>
        <?php else: ?>
            <?php foreach ($tickets as $t): ?>
                <div class="ticket-card">
                    <?php if (!empty($t['place_image'])): ?>
                        <img src="../Home/Locations/<?php echo htmlspecialchars($t['place_image']); ?>" alt="Place" class="ticket-image">
                    <?php else: ?>
                        <div class="ticket-image" style="background: #444;"></div>
                    <?php endif; ?>
                    
                    <div class="ticket-info">
                        <div class="ticket-place"><?php echo htmlspecialchars($t['place_title'] ?? $t['place_name']); ?></div>
                        <div class="ticket-detail">
                            ORDER #<?php echo $t['id']; ?><br>
                            VISIT DATE: <?php echo htmlspecialchars($t['visit_date']); ?><br>
                            QUANTITY: <?php echo $t['quantity']; ?>
                        </div>
                        <div class="ticket-price">
                            TOTAL: $<?php echo number_format($t['total_price'], 2); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
        
        <br>
        <a href="../Home/home.php" class="back-link">&lt; BACK TO HOME</a>
    </div>

</body>
</html>
