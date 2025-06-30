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
    
    // Weight loss goal (example)
    $weightQuery = "SELECT weight FROM health_records WHERE user_id = :user_id ORDER BY measure_date DESC LIMIT 2";
    $weightStmt = $db->prepare($weightQuery);
    $weightStmt->bindParam(':user_id', $userId);
    $weightStmt->execute();
    
    $weights = $weightStmt->fetchAll(PDO::FETCH_ASSOC);
    if (count($weights) >= 2) {
        $currentWeight = $weights[0]['weight'];
        $previousWeight = $weights[1]['weight'];
        $weightLoss = $previousWeight - $currentWeight;
        $targetLoss = 2.0; // 2kg target
        
        $goals[] = [
            'name' => 'Giảm cân',
            'current' => round($weightLoss, 1),
            'target' => $targetLoss,
            'unit' => 'kg',
            'percentage' => min(100, round(($weightLoss / $targetLoss) * 100))
        ];
    }
    
    // Workout goal
    $workoutQuery = "SELECT COUNT(*) as count FROM workout_logs WHERE user_id = :user_id AND YEARWEEK(workout_date) = YEARWEEK(NOW())";
    $workoutStmt = $db->prepare($workoutQuery);
    $workoutStmt->bindParam(':user_id', $userId);
    $workoutStmt->execute();
    
    $workoutResult = $workoutStmt->fetch(PDO::FETCH_ASSOC);
    $workoutCount = $workoutResult['count'];
    
    $goals[] = [
        'name' => 'Bài tập',
        'current' => $workoutCount,
        'target' => 5,
        'unit' => 'buổi',
        'percentage' => min(100, round(($workoutCount / 5) * 100))
    ];
    
    // Water intake goal (example)
    $goals[] = [
        'name' => 'Uống nước',
        'current' => 1.8,
        'target' => 2.5,
        'unit' => 'L',
        'percentage' => 72
    ];
    
    echo json_encode($goals);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
}
?>