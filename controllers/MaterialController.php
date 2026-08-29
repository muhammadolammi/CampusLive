<?php
class MaterialController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // GET /campus-api/materials?department=Statistics
    public function getAll() {
        $query = "SELECT * FROM materials WHERE 1=1";
        $params = [];

        if (!empty($_GET['department'])) {
            $query .= " AND department = :dept";
            $params[':dept'] = $_GET['department'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        $materials = $stmt->fetchAll();

        echo json_encode(["status" => "success", "data" => $materials]);
    }
}