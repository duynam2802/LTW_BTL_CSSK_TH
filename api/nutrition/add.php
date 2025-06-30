<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['message' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
    exit();
}

include_once '../config/database.php';

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['foodName']) || !isset($input['calories'])) {
    http_response_code(400);
    echo json_encode(['message' => 'Tên món ăn và calo là bắt buộc']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    $userId = $_SESSION['user_id'];
    
    $query = "INSERT INTO nutrition_logs (user_id, meal_type, food_name, quantity, calories, created_at) 
              VALUES (:user_id, :meal_type, :food_name, :quantity, :calories, NOW())";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $userId);
    $stmt->bindParam(':meal_type', $input['mealType']);
    $stmt->bindParam(':food_name', $input['foodName']);
    $stmt->bindParam(':quantity', $input['quantity']);
    $stmt->bindParam(':calories', $input['calories']);
    
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Đã thêm món ăn thành công'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['message' => 'Không thể thêm món ăn']);
    }
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
}
?>