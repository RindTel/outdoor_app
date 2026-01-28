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

        $stmt = $conn->prepare("INSERT INTO tickets 
            (user_id, place_id, visit_date, quantity)
            VALUES (?, ?, ?, ?)");
        $stmt->bind_param(
            'iisi',
            $userId,
            $placeId,
            $visitDate,
            $quantity
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
        $stmt = $conn->prepare("SELECT t.*, p.name as place_name, p.description as place_description, p.location, p.image as place_image, u.username 
            FROM tickets t 
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
}

