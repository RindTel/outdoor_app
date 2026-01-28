<?php

require_once __DIR__ . '/../config.php';

class Place
{
    public int $id;
    public string $name;
    public string $description;
    public string $location;
    public ?string $image;

    public function __construct(
        int $id,
        string $name,
        string $description,
        string $location,
        ?string $image
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->location = $location;
        $this->image = $image;
    }

    public static function fromRow(array $row): self
    {
        return new self(
            (int)$row['id'],
            $row['name'],
            $row['description'],
            $row['location'],
            $row['image'] ?? null
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
        string $description,
        string $location,
        ?string $image
    ): self {
        $conn = getDBConnection();
        $stmt = $conn->prepare("INSERT INTO places (name, description, location, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $description, $location, $image);
        $stmt->execute();
        $id = $stmt->insert_id;
        $stmt->close();
        $conn->close();

        return new self($id, $name, $description, $location, $image);
    }
}
