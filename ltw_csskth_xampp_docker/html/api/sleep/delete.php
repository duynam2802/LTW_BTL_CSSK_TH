<?php
// Prevent any output before headers
error_reporting(E_ALL);
ini_set('display_errors', 0);

header('Content-Type: application/json; charset=utf-8');
include_once '../config/database.php';
session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    http_response_code(401);
    echo json_encode(['message' => 'Người dùng chưa đăng nhập']);
    exit;
}

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    if (!$conn) {
        throw new Exception("Không thể kết nối cơ sở dữ liệu");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => "Lỗi kết nối cơ sở dữ liệu: " . $e->getMessage()]);
    exit;
}

try {
    $input = file_get_contents('php://input');
    if (!$input) {
        throw new Exception("Không có dữ liệu đầu vào");
    }
    
    $data = json_decode($input, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception("Dữ liệu JSON không hợp lệ: " . json_last_error_msg());
    }

    if (!isset($data['id']) || empty($data['id'])) {
        http_response_code(400);
        echo json_encode(["error" => "Thiếu ID dữ liệu giấc ngủ"]);
        exit;
    }

    $sleep_id = (int)$data['id'];

    // Kiểm tra xem dữ liệu có thuộc về user hiện tại không
    $check_stmt = $conn->prepare("SELECT id FROM sleep_logs WHERE id = ? AND user_id = ?");
    $check_stmt->execute([$sleep_id, $user_id]);
    
    if (!$check_stmt->fetch()) {
        http_response_code(404);
        echo json_encode(["error" => "Không tìm thấy dữ liệu giấc ngủ"]);
        exit;
    }

    // Xóa dữ liệu
    $stmt = $conn->prepare("DELETE FROM sleep_logs WHERE id = ? AND user_id = ?");
    $result = $stmt->execute([$sleep_id, $user_id]);

    if ($result && $stmt->rowCount() > 0) {
        echo json_encode(["success" => true, "message" => "Đã xóa dữ liệu giấc ngủ thành công"]);
    } else {
        throw new Exception("Không thể xóa dữ liệu giấc ngủ");
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
} 