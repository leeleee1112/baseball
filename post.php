<?php
include 'db.php'; // 데이터베이스 연결

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    // 게시글을 DB에 삽입
    $stmt = $pdo->prepare("INSERT INTO posts (user_id, title, content) VALUES (:user_id, :title, :content)");
    $stmt->execute(['user_id' => $user_id, 'title' => $title, 'content' => $content]);

    header("Location: index.php");
    exit;
} else {
    echo "Post submission failed.";
}
?>
