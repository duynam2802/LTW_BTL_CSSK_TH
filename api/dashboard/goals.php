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
    $goals = [];

    // Cân nặng
    $weightGoalQuery = "SELECT target_value FROM goals WHERE user_id = :user_id AND goal_type = 'weight' AND is_active = 1 ORDER BY id DESC LIMIT 1";
    $weightGoalStmt = $db->prepare($weightGoalQuery);
    $weightGoalStmt->bindParam(':user_id', $userId);
    $weightGoalStmt->execute();
    $weightGoal = $weightGoalStmt->fetchColumn();
    // Lấy cân nặng hiện tại
    $weightQuery = "SELECT weight FROM health_records WHERE user_id = :user_id ORDER BY measure_date DESC LIMIT 1";
    $weightStmt = $db->prepare($weightQuery);
    $weightStmt->bindParam(':user_id', $userId);
    $weightStmt->execute();
    $currentWeight = $weightStmt->fetchColumn();
    $goals[] = [
        'name' => 'Cân nặng',
        'current' => $currentWeight ? floatval($currentWeight) : null,
        'target' => $weightGoal ? floatval($weightGoal) : null,
        'unit' => 'kg',
        'percentage' => ($weightGoal && $currentWeight) ? round(min(100, abs($currentWeight / $weightGoal) * 100)) : 0
    ];

    // Bài tập/tuần
    $workoutGoalQuery = "SELECT target_value FROM goals WHERE user_id = :user_id AND goal_type = 'workout' AND is_active = 1 ORDER BY id DESC LIMIT 1";
    $workoutGoalStmt = $db->prepare($workoutGoalQuery);
    $workoutGoalStmt->bindParam(':user_id', $userId);
    $workoutGoalStmt->execute();
    $workoutGoal = $workoutGoalStmt->fetchColumn();
    // Đếm số buổi tập tuần này
    $workoutCountQuery = "SELECT COUNT(*) FROM workout_logs WHERE user_id = :user_id AND YEARWEEK(workout_date, 1) = YEARWEEK(CURDATE(), 1)";
    $workoutCountStmt = $db->prepare($workoutCountQuery);
    $workoutCountStmt->bindParam(':user_id', $userId);
    $workoutCountStmt->execute();
    $workoutCount = $workoutCountStmt->fetchColumn();
    $goals[] = [
        'name' => 'Bài tập',
        'current' => intval($workoutCount),
        'target' => $workoutGoal ? intval($workoutGoal) : null,
        'unit' => 'buổi',
        'percentage' => ($workoutGoal && $workoutCount) ? round(min(100, $workoutCount / $workoutGoal * 100)) : 0
    ];

    // Calo/ngày
    $calorieGoalQuery = "SELECT target_value FROM goals WHERE user_id = :user_id AND goal_type = 'calorie' AND is_active = 1 ORDER BY id DESC LIMIT 1";
    $calorieGoalStmt = $db->prepare($calorieGoalQuery);
    $calorieGoalStmt->bindParam(':user_id', $userId);
    $calorieGoalStmt->execute();
    $calorieGoal = $calorieGoalStmt->fetchColumn();
    // Tổng calo hôm nay
    $calorieTodayQuery = "SELECT SUM(calories) FROM nutrition_logs WHERE user_id = :user_id AND DATE(created_at) = CURDATE()";
    $calorieTodayStmt = $db->prepare($calorieTodayQuery);
    $calorieTodayStmt->bindParam(':user_id', $userId);
    $calorieTodayStmt->execute();
    $calorieToday = $calorieTodayStmt->fetchColumn();
    $goals[] = [
        'name' => 'Calo',
        'current' => $calorieToday ? intval($calorieToday) : 0,
        'target' => $calorieGoal ? intval($calorieGoal) : null,
        'unit' => 'kcal',
        'percentage' => ($calorieGoal && $calorieToday) ? round(min(100, $calorieToday / $calorieGoal * 100)) : 0
    ];

    echo json_encode($goals);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
}
?>