<?php
require_once '../../config.php';
require_once __DIR__ . '/../../classes/TicketOrder.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../Home/home.php');
    exit;
}

if (!isLoggedIn()) {
    
    header('Location: ../../Login/login.php');
    exit;
}

$placeId = isset($_POST['place_id']) ? (int)$_POST['place_id'] : 0;
$visitDate = trim($_POST['visit_date'] ?? '');
$quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;

if ($placeId <= 0 || !$visitDate || $quantity <= 0) {
    
    
    die('Invalid input parameters. <a href="javascript:history.back()">Go Back</a>');
}

try {
    $orderId = TicketOrder::createFromForm($placeId, $visitDate, $quantity);
    header('Location: success.php?order_id=' . $orderId);
    exit;
} catch (Exception $e) {
    die('Error processing order: ' . htmlspecialchars($e->getMessage()) . ' <a href="javascript:history.back()">Go Back</a>');
}
