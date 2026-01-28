<?php
require_once '../config.php';
require_once __DIR__ . '/../classes/Place.php';

header('Content-Type: application/json');

$conn = getDBConnection();

if (isset($_GET['place'])) {
    
    $placeName = $_GET['place'];
    $place = Place::findByName($placeName);

    if ($place) {
       
        $detailsStmt = $conn->prepare("SELECT detail_key, detail_value FROM place_details WHERE place_id = ?");
        $detailsStmt->bind_param("i", $place->id);
        $detailsStmt->execute();
        $detailsResult = $detailsStmt->get_result();
        
        $details = [];
        while ($row = $detailsResult->fetch_assoc()) {
            $value = $row['detail_value'];
            $decoded = json_decode($value, true);
            $details[$row['detail_key']] = $decoded !== null ? $decoded : $value;
        }
        $detailsStmt->close();

        $response = [
            'id' => $place->id,
            'name' => $place->name,
            'description' => $place->description,
            'location' => $place->location,
            'image' => $place->image,
            'details' => $details
        ];

        echo json_encode($response);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Place not found']);
    }
} else {
    
    $places = Place::all();
    $response = [];
    foreach ($places as $p) {
        $response[] = [
            'id' => $p->id,
            'name' => $p->name,
            'description' => $p->description,
            'location' => $p->location,
            'image' => $p->image
        ];
    }
    echo json_encode($response);
}

$conn->close();
?>
