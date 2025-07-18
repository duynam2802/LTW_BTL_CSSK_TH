<?php
require_once __DIR__ . "/api/config/database.php";


$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "✅ Kết nối thành công với MySQL từ check.php!";
} else {
    echo "❌ Kết nối thất bại.";
}
?>
