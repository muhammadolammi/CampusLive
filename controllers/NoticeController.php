<?php
class NoticeController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // GET /campus-api/notices
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM notices ORDER BY created_at DESC");
        $notices = $stmt->fetchAll();
        echo json_encode(["status" => "success", "data" => $notices]);
    }

    // POST /campus-api/notices
    public function create($data) {
        if (empty($data->title) || empty($data->content)) {
            http_response_code(400);
            echo json_encode(["status" => "error", "message" => "Title and content required"]);
            return;
        }

        $stmt = $this->db->prepare("INSERT INTO notices (title, content, priority) VALUES (:title, :content, :priority)");
        $stmt->execute([
            ':title' => $data->title,
            ':content' => $data->content,
            ':priority' => $data->priority ?? 'normal'
        ]);

        http_response_code(201);
        echo json_encode(["status" => "success", "message" => "Notice created"]);
    }
}