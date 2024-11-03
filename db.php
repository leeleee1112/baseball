<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // 세션이 시작되지 않았다면 시작
}

$host = 'localhost';
$dbname = 'community_db'; // 사용 중인 데이터베이스 이름으로 변경
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
    exit;
}
?>
