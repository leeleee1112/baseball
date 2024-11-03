<?php
include 'db.php'; // 데이터베이스 연결 파일

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];

    try {
        // 사용자 권한 확인: 본인의 게시글만 삭제할 수 있음
        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :post_id AND user_id = :user_id");
        $stmt->execute(['post_id' => $post_id, 'user_id' => $user_id]);
        $post = $stmt->fetch();

        if ($post) {
            // 게시글과 관련된 댓글 삭제
            $stmt = $pdo->prepare("DELETE FROM comments WHERE post_id = :post_id");
            $stmt->execute(['post_id' => $post_id]);

            // 게시글 삭제
            $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :post_id");
            $stmt->execute(['post_id' => $post_id]);

            header("Location: index.php"); // 메인 페이지로 리다이렉트
            exit;
        } else {
            echo "You are not authorized to delete this post.";
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Failed to delete post.";
}
?>
