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
    $stmt = $conn->prepare("
        SELECT id, sleep_date, bedtime, wake_time, 
               ROUND(
                   CASE 
                       WHEN wake_time >= bedtime THEN 
                           TIMESTAMPDIFF(MINUTE, bedtime, wake_time)/60
                       ELSE 
                           TIMESTAMPDIFF(MINUTE, bedtime, DATE_ADD(wake_time, INTERVAL 1 DAY))/60
                   END, 1
               ) as duration,
               quality, notes
        FROM sleep_logs 
        WHERE user_id = ? 
        ORDER BY sleep_date DESC, id DESC 
        LIMIT 20
    ");
    $stmt->execute([$user_id]);
    $history = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($history);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
