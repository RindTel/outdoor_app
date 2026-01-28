<?php

require_once __DIR__ . '/../config.php';

class ContactMessage
{
    public int $id;
    public string $name;
    public string $email;
    public string $message;
    public ?int $userId;
    public string $createdAt;

    public function __construct(int $id, string $name, string $email, string $message, ?int $userId, string $createdAt)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->message = $message;
        $this->userId = $userId;
        $this->createdAt = $createdAt;
    }

    public static function create(string $name, string $email, string $message, ?int $userId = null): self
    {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, message, user_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $name, $email, $message, $userId);
        $stmt->execute();

        $id = $stmt->insert_id;
        $stmt->close();

        $stmt = $conn->prepare("SELECT id, name, email, message, user_id, created_at FROM contact_messages WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        $stmt->close();
        $conn->close();

        return new self(
            (int)$row['id'],
            $row['name'],
            $row['email'],
            $row['message'],
            $row['user_id'] !== null ? (int)$row['user_id'] : null,
            $row['created_at']
        );
    }

    public static function all(): array
    {
        $conn = getDBConnection();
        $result = $conn->query("SELECT id, name, email, message, user_id, created_at FROM contact_messages ORDER BY created_at DESC");
        $messages = [];

        while ($row = $result->fetch_assoc()) {
            $messages[] = new self(
                (int)$row['id'],
                $row['name'],
                $row['email'],
                $row['message'],
                $row['user_id'] !== null ? (int)$row['user_id'] : null,
                $row['created_at']
            );
        }

        $conn->close();
        return $messages;
    }
}

