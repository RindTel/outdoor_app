<?php

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/Place.php';

class TicketOrder
{
    public static function createFromForm(
        int $placeId,
        string $visitDate,
        int $quantity
    ): int {
        $conn = db_connect();

        $place = Place::findById($placeId);
        if (!$place) {
            $conn->close();
            throw new RuntimeException('Selected place not found.');
        }

        $userId = getCurrentUserId();
        if (!$userId) {
            $conn->close();
            throw new RuntimeException('You must be logged in to book tickets.');
        }

        
        $stmtUser = $conn->prepare("SELECT username, email FROM users WHERE id = ?");
        $stmtUser->bind_param("i", $userId);
        $stmtUser->execute();
        $userRes = $stmtUser->get_result();
        $user = $userRes->fetch_assoc();
        $stmtUser->close();

        if (!$user) {
            $conn->close();
            throw new RuntimeException('User not found.');
        }

        $buyerName = $user['username'];
        $buyerEmail = $user['email'];
        $unitPrice = $place->ticket_price;
        $totalPrice = $unitPrice * $quantity;

        
        $stmt = $conn->prepare("INSERT INTO ticket_orders 
            (place_id, buyer_name, buyer_email, visit_date, quantity, unit_price, total_price, user_id)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            'isssidii',
            $placeId,
            $buyerName,
            $buyerEmail,
            $visitDate,
            $quantity,
            $unitPrice,
            $totalPrice,
            $userId
        );

        if (!$stmt->execute()) {
            $stmt->close();
            $conn->close();
            throw new RuntimeException('Could not save order. Please try again.');
        }

        $orderId = $stmt->insert_id;
        $stmt->close();
        $conn->close();

        return $orderId;
    }

    public static function findById(int $id): ?array
    {
        $conn = db_connect();
        
        $stmt = $conn->prepare("SELECT t.*, p.name as place_name, p.title as place_title, p.description as place_description, p.image as place_image, u.username 
            FROM ticket_orders t 
            JOIN places p ON t.place_id = p.id 
            LEFT JOIN users u ON t.user_id = u.id 
            WHERE t.id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = null;
        if ($row = $result->fetch_assoc()) {
            $order = $row;
        }
        $stmt->close();
        $conn->close();
        return $order;
    }
    public static function getAllByUserId(int $userId): array
    {
        $conn = db_connect();
        $stmt = $conn->prepare("SELECT t.*, p.name as place_name, p.title as place_title, p.image as place_image 
            FROM ticket_orders t 
            JOIN places p ON t.place_id = p.id 
            WHERE t.user_id = ? 
            ORDER BY t.created_at DESC");
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $tickets = [];
        while ($row = $result->fetch_assoc()) {
            $tickets[] = $row;
        }
        
        $stmt->close();
        $conn->close();
        return $tickets;
    }
}

