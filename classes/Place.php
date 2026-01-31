<?php

require_once __DIR__ . '/../config.php';

class Place
{
    public int $id;
    public string $name;
    public string $title;
    public string $description;
    public ?string $image;
    public int $position_x;
    public int $position_y;
    public float $ticket_price;

    public function __construct(
        int $id,
        string $name,
        string $title,
        string $description,
        ?string $image,
        int $position_x,
        int $position_y,
        float $ticket_price
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->title = $title;
        $this->description = $description;
        $this->image = $image;
        $this->position_x = $position_x;
        $this->position_y = $position_y;
        $this->ticket_price = $ticket_price;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            $row['name'],
            $row['title'],
            $row['description'],
            $row['image'] ?? null,
            (int)$row['position_x'],
            (int)$row['position_y'],
            (float)$row['ticket_price']
        );
    }

    public static function all(): array
    {
        $conn = getDBConnection();
        $result = $conn->query("SELECT * FROM places ORDER BY name");
        $places = [];

        while ($row = $result->fetch_assoc()) {
            $places[] = self::fromRow($row);
        }

        $conn->close();
        return $places;
    }

    public static function findByName(string $name): ?self
    {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM places WHERE name = ?");
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        $place = null;
        if ($row = $result->fetch_assoc()) {
            $place = self::fromRow($row);
        }
        $stmt->close();
        $conn->close();
        return $place;
    }

    public static function findById(int $id): ?self
    {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $place = null;
        if ($row = $result->fetch_assoc()) {
            $place = self::fromRow($row);
        }
        $stmt->close();
        $conn->close();
        return $place;
    }

    public static function create(
        string $name,
        string $title,
        string $description,
        ?string $image,
        int $position_x,
        int $position_y,
        float $ticket_price
    ): self {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO places (name, title, description, image, position_x, position_y, ticket_price) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiid", $name, $title, $description, $image, $position_x, $position_y, $ticket_price);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        $conn->close();

        return new self($id, $name, $title, $description, $image, $position_x, $position_y, $ticket_price);
    }
}
