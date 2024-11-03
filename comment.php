<?php
include 'db.php'; // 데이터베이스 연결 파일 포함

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    try {
        // 댓글을 DB에 삽입
        $stmt = $pdo->prepare("INSERT INTO comments (post_id, user_id, content) VALUES (:post_id, :user_id, :content)");
        $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id, 'content' => $content]);

        header("Location: index.php"); // 댓글 추가 후 메인 페이지로 리다이렉트
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage(); // 오류 메시지 출력
    }
} else {
    echo "Failed to add comment. Please try again.";
}
?>
