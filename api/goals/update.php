<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

include_once '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);
if (!$input) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Thiếu dữ liệu mục tiêu']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    $userId = $_SESSION['user_id'];

    $goals = [
        'weight' => isset($input['weightGoal']) ? $input['weightGoal'] : null,
        'workout' => isset($input['workoutGoal']) ? $input['workoutGoal'] : null,
        'calorie' => isset($input['calorieGoal']) ? $input['calorieGoal'] : null
    ];
    $units = [
        'weight' => 'kg',
        'workout' => 'buổi',
        'calorie' => 'kcal'
    ];

    foreach ($goals as $type => $value) {
        if ($value !== null) {
            // Check if goal exists
            $check = $db->prepare("SELECT id FROM goals WHERE user_id = :user_id AND goal_type = :goal_type AND is_active = 1");
            $check->execute(['user_id' => $userId, 'goal_type' => $type]);
            if ($row = $check->fetch(PDO::FETCH_ASSOC)) {
                // Update
                $update = $db->prepare("UPDATE goals SET target_value = :target, updated_at = NOW() WHERE id = :id");
                $update->execute(['target' => $value, 'id' => $row['id']]);
            } else {
                // Insert
                $insert = $db->prepare("INSERT INTO goals (user_id, goal_type, target_value, unit, is_active, created_at, updated_at) VALUES (:user_id, :goal_type, :target, :unit, 1, NOW(), NOW())");
                $insert->execute([
                    'user_id' => $userId,
                    'goal_type' => $type,
                    'target' => $value,
                    'unit' => $units[$type]
                ]);
            }
        }
    }
    echo json_encode(['success' => true, 'message' => 'Đã cập nhật mục tiêu cá nhân']);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
} 