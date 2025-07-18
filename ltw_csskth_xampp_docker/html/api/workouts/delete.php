<?php
header('Content-Type: application/json');
include_once '../config/database.php';

session_start();

// Kiểm tra đăng nhập
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "error" => "Chưa đăng nhập"]);
    exit;
}

$user_id = $_SESSION['user_id'];

// Đọc dữ liệu JSON
$data = json_decode(file_get_contents('php://input'), true);
$workout_id = isset($data['id']) ? (int)$data['id'] : 0;

if (!$workout_id) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Thiếu id buổi tập"]);
    exit;
}

$database = new Database();
$conn = $database->getConnection();

try {
    // Chỉ xoá workout của user đang đăng nhập
    $stmt = $conn->prepare("DELETE FROM workout_logs WHERE id = ? AND user_id = ?");
    $stmt->execute([$workout_id, $user_id]);
    if ($stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Đã xoá buổi tập"]);
    } else {
        echo json_encode(["success" => false, "error" => "Không tìm thấy hoặc không có quyền xoá"]);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
} 