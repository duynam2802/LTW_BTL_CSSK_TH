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
if (!$input || !isset($input['fullName']) || !isset($input['email'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Thiếu thông tin bắt buộc']);
    exit();
}

try {
    $database = new Database();
    $db = $database->getConnection();
    $userId = $_SESSION['user_id'];
    $fullName = trim($input['fullName']);
    $email = trim($input['email']);
    $age = isset($input['age']) ? (int)$input['age'] : null;
    $gender = isset($input['gender']) ? $input['gender'] : null;

    $query = "UPDATE users SET full_name = :full_name, email = :email, age = :age, gender = :gender WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':full_name', $fullName);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':age', $age);
    $stmt->bindParam(':gender', $gender);
    $stmt->bindParam(':id', $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        $_SESSION['full_name'] = $fullName;
        $_SESSION['email'] = $email;
        echo json_encode(['success' => true, 'message' => 'Đã cập nhật thông tin cá nhân']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Không thể cập nhật thông tin']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
} 