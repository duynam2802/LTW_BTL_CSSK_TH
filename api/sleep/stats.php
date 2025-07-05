<?php
// sleep/stats.php
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
    $stmt = $conn->prepare("SELECT 
        AVG(
            CASE 
                WHEN wake_time >= bedtime THEN 
                    TIMESTAMPDIFF(MINUTE, bedtime, wake_time)/60
                ELSE 
                    TIMESTAMPDIFF(MINUTE, bedtime, DATE_ADD(wake_time, INTERVAL 1 DAY))/60
            END
        ) AS avg_duration,
        AVG(quality) AS avg_quality,
        SEC_TO_TIME(AVG(TIME_TO_SEC(bedtime))) AS avg_bedtime,
        SEC_TO_TIME(AVG(TIME_TO_SEC(wake_time))) AS avg_waketime
        FROM sleep_logs
        WHERE user_id = ? AND sleep_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)");
    $stmt->execute([$user_id]);
    $avg = $stmt->fetch(PDO::FETCH_ASSOC);

    $response = [
        "average" => [
            "duration" => $avg['avg_duration'] ? round($avg['avg_duration'], 1) : 0,
            "quality" => $avg['avg_quality'] ? round($avg['avg_quality'], 1) : 0,
            "bedtime" => $avg['avg_bedtime'] ? substr($avg['avg_bedtime'], 0, 5) : '--',
            "wakeTime" => $avg['avg_waketime'] ? substr($avg['avg_waketime'], 0, 5) : '--',
            "qualityText" => ($avg['avg_quality'] >= 7 ? 'Tốt' : ($avg['avg_quality'] >= 5 ? 'Ổn định' : 'Cần cải thiện'))
        ],
        "bedtimeAdvice" => ($avg['avg_bedtime'] && substr($avg['avg_bedtime'], 0, 2) > 23 ? "Ngủ quá muộn" : "Giờ ngủ ổn"),
        "wakeAdvice" => ($avg['avg_waketime'] && substr($avg['avg_waketime'], 0, 2) < 6 ? "Thức dậy hơi sớm" : "Ổn định")
    ];

    echo json_encode($response);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["error" => $e->getMessage()]);
}
