<?php
class ShuttleController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // GET /api/shuttles
    public function getAll() {
        $query = "SELECT * FROM shuttle_locations";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $shuttles = $stmt->fetchAll();

        echo json_encode(["status" => "success", "data" => $shuttles]);
    }

    // POST /api/shuttles/update
    public function updateLocation($data) {
        if (!isset($data->id, $data->latitude, $data->longitude)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Incomplete GPS payload"]);
            return;
        }

        $query = "UPDATE shuttle_locations 
                  SET latitude = :lat, longitude = :lng, speed = :speed 
                  WHERE id = :id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':lat' => $data->latitude,
            ':lng' => $data->longitude,
            ':speed' => $data->speed ?? 0.0,
            ':id' => $data->id
        ]);

        echo json_encode(["status" => "success", "message" => "Location updated"]);
    }
}