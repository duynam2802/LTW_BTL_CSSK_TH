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

    if (
        !isset($data['bedtime'], $data['wakeTime'], $data['sleepDate'], $data['quality'])
        || empty($data['bedtime']) || empty($data['wakeTime']) || empty($data['sleepDate'])
    ) {
        http_response_code(400);
        echo json_encode(["error" => "Thiếu hoặc sai định dạng dữ liệu giấc ngủ"]);
        exit;
    }

    $bedtime = $data['bedtime'];
    $wake_time = $data['wakeTime'];
    $sleep_date = $data['sleepDate'];
    $quality = (int)$data['quality'];
    $notes = trim($data['notes'] ?? '');

    // Debug log
    error_log("Received quality value: " . $data['quality'] . " (type: " . gettype($data['quality']) . ")");
    error_log("Converted quality value: " . $quality . " (type: " . gettype($quality) . ")");

    if ($quality < 1 || $quality > 10) {
        http_response_code(400);
        echo json_encode([
            "error" => "Chất lượng ngủ phải từ 1 đến 10", 
            "received_value" => $data['quality'],
            "converted_value" => $quality,
            "data_type" => gettype($data['quality'])
        ]);
        exit;
    }

    // 🧠 Tính duration (số giờ ngủ)
    $bed = new DateTime($bedtime);
    $wake = new DateTime($wake_time);
    if ($wake <= $bed) {
        $wake->modify('+1 day');
    }
    $interval = $bed->diff($wake);
    $duration = round($interval->h + $interval->i / 60, 2); // VD: 7.5 giờ

    // ✅ Chuẩn bị và thực thi câu lệnh
    $stmt = $conn->prepare("
        INSERT INTO sleep_logs (user_id, sleep_date, bedtime, wake_time, quality, notes)
        VALUES (:user_id, :sleep_date, :bedtime, :wake_time, :quality, :notes)
    ");

    $result = $stmt->execute([
        ':user_id' => $user_id,
        ':sleep_date' => $sleep_date,
        ':bedtime' => $bedtime,
        ':wake_time' => $wake_time,
        ':quality' => $quality,
        ':notes' => $notes
    ]);

    if ($result) {
        echo json_encode(["success" => true, "message" => "Đã thêm giấc ngủ thành công"]);
    } else {
        throw new Exception("Không thể thêm dữ liệu giấc ngủ");
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
