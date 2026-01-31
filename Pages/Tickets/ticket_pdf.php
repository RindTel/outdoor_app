<?php
require_once '../../config.php';
require_once __DIR__ . '/../../classes/TicketOrder.php';


require_once '../../lib/fpdf.php';

$orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
if ($orderId <= 0) {
    http_response_code(400);
    echo 'Invalid order id';
    exit;
}

$order = TicketOrder::findById($orderId);

if (!$order) {
    http_response_code(404);
    echo 'Order not found';
    exit;
}

// Build PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(0, 10, 'Outdoor App Ticket', 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', '', 12);

$pdf->Cell(40, 8, 'Order ID:', 0, 0);
$pdf->Cell(0, 8, $order['id'], 0, 1);

$pdf->Cell(40, 8, 'Place:', 0, 0);
$pdf->Cell(0, 8, $order['place_name'], 0, 1);

$pdf->Cell(40, 8, 'Location:', 0, 0);
$pdf->Cell(0, 8, $order['location'], 0, 1);

$pdf->Cell(40, 8, 'User:', 0, 0);
$pdf->Cell(0, 8, $order['username'] ?? 'Guest', 0, 1);

$pdf->Cell(40, 8, 'Visit date:', 0, 0);
$pdf->Cell(0, 8, $order['visit_date'], 0, 1);

$pdf->Cell(40, 8, 'Quantity:', 0, 0);
$pdf->Cell(0, 8, $order['quantity'], 0, 1);

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 10);
$pdf->MultiCell(0, 6, "Please bring this PDF (printed or on your phone) when you visit the location.\nThank you for using Outdoor App!");

// Force download
$filename = 'ticket_order_' . $order['id'] . '.pdf';
$pdf->Output('D', $filename);
exit;

