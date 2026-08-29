<?php
class BuildingController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // GET /campus-api/buildings (supports ?q=name or ?category=Faculty)
    public function getAll() {
        $query = "SELECT * FROM buildings WHERE 1=1";
        $params = [];

        if (!empty($_GET['q'])) {
            $query .= " AND (name LIKE :q OR description LIKE :q)";
            $params[':q'] = '%' . $_GET['q'] . '%';
        }

        if (!empty($_GET['category'])) {
            $query .= " AND category = :category";
            $params[':category'] = $_GET['category'];
        }

        $query .= " ORDER BY name ASC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $buildings = $stmt->fetchAll();

        echo json_encode([
            "status" => "success",
            "count" => count($buildings),
            "data" => $buildings
        ]);
    }

    // GET /campus-api/buildings/{id}
    public function getById($id) {
        $query = "SELECT * FROM buildings WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        $building = $stmt->fetch();

        if ($building) {
            echo json_encode(["status" => "success", "data" => $building]);
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Building not found"]);
        }
    }
}