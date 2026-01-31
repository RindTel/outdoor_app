<?php
require_once '../../config.php';
require_once __DIR__ . '/../../classes/TicketOrder.php';

if (!isLoggedIn()) {
    header('Location: ../../Login/login.php');
    exit;
}

$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
$order = $orderId > 0 ? TicketOrder::findById($orderId) : null;

if (!$order) {
    die('Order not found.');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Confirmed</title>
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../Home/home.css">
    <style>
        .receipt-container {
            max-width: 500px;
            margin: 100px auto;
            background: #2b2e33;
            color: #efe6d8;
            border: 4px solid #1e2024;
            padding: 30px;
            text-align: center;
            font-family: 'Press Start 2P', monospace;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
        .receipt-header {
            margin-bottom: 30px;
            border-bottom: 2px dashed #555;
            padding-bottom: 20px;
        }
        .receipt-header h1 {
            color: #9be7c4;
            font-size: 18px;
            margin-bottom: 10px;
        }
        .receipt-details {
            text-align: left;
            font-size: 10px;
            line-height: 2;
            margin-bottom: 30px;
        }
        .receipt-row {
            display: flex;
            justify-content: space-between;
        }
        .receipt-total {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 2px solid #555;
            font-size: 14px;
            color: #9be7c4;
        }
        .btn-home {
            display: inline-block;
            text-decoration: none;
            background: #5f7f87;
            color: #fff;
            padding: 12px 20px;
            font-size: 10px;
            border: 2px solid #000;
            transition: transform 0.1s;
        }
        .btn-home:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<body>

<div class="receipt-container">
    <div class="receipt-header">
        <h1>ORDER CONFIRMED!</h1>
        <p style="font-size: 10px; color: #aaa;">Thank you for your purchase.</p>
    </div>

    <div class="receipt-details">
        <div class="receipt-row">
            <span>Order ID:</span>
            <span>#<?php echo $order['id']; ?></span>
        </div>
        <div class="receipt-row">
            <span>Place:</span>
            <span><?php echo htmlspecialchars($order['place_title']); ?></span>
        </div>
        <div class="receipt-row">
            <span>Date:</span>
            <span><?php echo htmlspecialchars($order['visit_date']); ?></span>
        </div>
        <div class="receipt-row">
            <span>Name:</span>
            <span><?php echo htmlspecialchars($order['buyer_name']); ?></span>
        </div>
        <div class="receipt-row">
            <span>Email:</span>
            <span><?php echo htmlspecialchars($order['buyer_email']); ?></span>
        </div>
        <div class="receipt-row">
            <span>Quantity:</span>
            <span><?php echo $order['quantity']; ?></span>
        </div>
         <div class="receipt-row">
            <span>Price/Ticket:</span>
            <span>$<?php echo number_format($order['unit_price'], 2); ?></span>
        </div>
        
        <div class="receipt-row receipt-total">
            <span>TOTAL:</span>
            <span>$<?php echo number_format($order['total_price'], 2); ?></span>
        </div>
    </div>

    <div style="margin-bottom: 20px; font-size: 8px; color: #888;">
        Please show this receipt at the entrance.
    </div>

    <a href="../Home/home.php" class="btn-home">RETURN TO HOME</a>
</div>

</body>
</html>
