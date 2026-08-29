<?php
$requestedFile = __DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
if (php_sapi_name() === 'cli-server' && is_file($requestedFile)) {
    return false;
}
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/controllers/BuildingController.php';
require_once __DIR__ . '/controllers/ShuttleController.php';
require_once __DIR__ . '/controllers/NoticeController.php';
require_once __DIR__ . '/controllers/MaterialController.php';

$database = new Database();
$db = $database->getConnection();

$uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
$segments = explode('/', $uri);

// Remove base folder name if running under localhost/campus-api
if (!empty($segments) && $segments[0] === 'campus-api') {
    array_shift($segments);
}

$resource = $segments[0] ?? null;
$id = $segments[1] ?? null;
$method = $_SERVER['REQUEST_METHOD'];

switch ($resource) {
    case 'buildings':
        $controller = new BuildingController($db);
        if ($method === 'GET' && $id) {
            $controller->getById($id);
        } elseif ($method === 'GET') {
            $controller->getAll();
        }
        break;

    case 'shuttles':
        $controller = new ShuttleController($db);
        if ($method === 'GET') {
            $controller->getAll();
        } elseif ($method === 'POST' && $id === 'update') {
            $data = json_decode(file_get_contents("php://input"));
            $controller->updateLocation($data);
        }
        break;

    case 'notices':
        $controller = new NoticeController($db);
        if ($method === 'GET') {
            $controller->getAll();
        } elseif ($method === 'POST') {
            $data = json_decode(file_get_contents("php://input"));
            $controller->create($data);
        }
        break;

    case 'materials':
        $controller = new MaterialController($db);
        if ($method === 'GET') {
            $controller->getAll();
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "Endpoint not found"]);
        break;
}