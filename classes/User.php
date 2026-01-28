<?php

require_once __DIR__ . '/../config.php';

class User
{
    public int $id;
    public string $username;
    public string $email;
    public string $passwordHash;
    public string $role;

    public function __construct(int $id, string $username, string $email, string $passwordHash, string $role = 'user')
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            $row['username'],
            $row['email'],
            $row['password'],
            $row['role'] ?? 'user'
        );
    }

    public static function findByUsername(string $username): ?self
    {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, username, email, password, role FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        $user = null;
        if ($row = $result->fetch_assoc()) {
            $user = self::fromRow($row);
        }

        $stmt->close();
        $conn->close();

        return $user;
    }

    public static function create(string $username, string $email, string $password, string $role = 'user'): self
    {
        $conn = getDBConnection();
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hash, $role);
        $stmt->execute();

        $id = $stmt->insert_id;

        $stmt->close();
        $conn->close();

        return new self($id, $username, $email, $hash, $role);
    }
}

