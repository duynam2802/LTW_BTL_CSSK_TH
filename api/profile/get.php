<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit();
}

include_once '../config/database.php';

try {
    $database = new Database();
    $db = $database->getConnection();
    $userId = $_SESSION['user_id'];

    // Lấy thông tin user
    $query = "SELECT id, full_name, email, age, gender FROM users WHERE id = :id LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        http_response_code(404);
        echo json_encode(['message' => 'User not found']);
        exit();
    }

    // Lấy thông tin sức khỏe mới nhất
    $healthQuery = "SELECT weight, height FROM health_records WHERE user_id = :user_id ORDER BY measure_date DESC LIMIT 1";
    $healthStmt = $db->prepare($healthQuery);
    $healthStmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $healthStmt->execute();
    $health = $healthStmt->fetch(PDO::FETCH_ASSOC);

    $user['current_weight'] = $health['weight'] ?? null;
    $user['height'] = $health['height'] ?? null;
    if ($user['current_weight'] && $user['height']) {
        $bmi = $user['current_weight'] / pow($user['height'] / 100, 2);
        $user['bmi'] = round($bmi, 1);
    } else {
        $user['bmi'] = null;
    }

    echo json_encode($user);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
} 