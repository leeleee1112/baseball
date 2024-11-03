<?php
include 'db.php'; // 데이터베이스 연결 파일

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // 세션이 시작되지 않았다면 시작
}

// 게시글 목록 가져오기
try {
    $stmt = $pdo->prepare("SELECT posts.id AS post_id, posts.title, posts.content, posts.created_at, posts.user_id, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
    $stmt->execute();
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "Error fetching posts: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Site</title>
</head>
<body>
    <h1>Community Site</h1>

    <!-- 게시글 작성 폼 -->
    <?php if (isset($_SESSION['user_id'])): ?>
        <form action="post.php" method="post">
            <input type="text" name="title" placeholder="Post Title" required>
            <textarea name="content" placeholder="Write your post here..." required></textarea>
            <button type="submit">Add Post</button>
        </form>
    <?php else: ?>
        <p>Please log in to create a post.</p>
    <?php endif; ?>

    <!-- 게시글 목록 -->
    <?php foreach ($posts as $post): ?>
        <div>
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
            <p>Posted by <?= htmlspecialchars($post['username']) ?> on <?= $post['created_at'] ?></p>

            <!-- 게시글 삭제 버튼 -->
            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $post['user_id']): ?>
                <form action="delete_post.php" method="post" onsubmit="return confirm('Are you sure you want to delete this post?');">
                    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                    <button type="submit">Delete Post</button>
                </form>
            <?php endif; ?>

            <!-- 댓글 목록 가져오기 -->
            <?php
            try {
                $stmt = $pdo->prepare("SELECT comments.id AS comment_id, comments.content, comments.created_at, comments.user_id, users.username FROM comments JOIN users ON comments.user_id = users.id WHERE comments.post_id = :post_id ORDER BY comments.created_at ASC");
                $stmt->execute(['post_id' => $post['post_id']]);
                $comments = $stmt->fetchAll();
            } catch (PDOException $e) {
                echo "Error fetching comments: " . $e->getMessage();
                exit;
            }
            ?>

            <!-- 댓글 표시 -->
            <?php foreach ($comments as $comment): ?>
                <div style="margin-left: 20px;">
                    <p><?= htmlspecialchars($comment['username']) ?>: <?= nl2br(htmlspecialchars($comment['content'])) ?></p>
                    <p><small><?= $comment['created_at'] ?></small></p>

                    <!-- 댓글 삭제 버튼 -->
                    <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $comment['user_id']): ?>
                        <form action="delete_comment.php" method="post" onsubmit="return confirm('Are you sure you want to delete this comment?');">
                            <input type="hidden" name="comment_id" value="<?= $comment['comment_id'] ?>">
                            <button type="submit">Delete Comment</button>
                        </form>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>

            <!-- 댓글 작성 폼 -->
            <?php if (isset($_SESSION['user_id'])): ?>
                <form action="comment.php" method="post" style="margin-left: 20px;">
                    <input type="hidden" name="post_id" value="<?= $post['post_id'] ?>">
                    <textarea name="content" placeholder="Write your comment here..." required></textarea>
                    <button type="submit">Add Comment</button>
                </form>
            <?php else: ?>
                <p>Please log in to comment.</p>
            <?php endif; ?>
        </div>
        <hr>
    <?php endforeach; ?>
</body>
</html>
