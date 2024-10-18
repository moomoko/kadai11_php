<?php
session_start();

// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=ranking_db;charset=utf8', 'root', '');
// } catch (PDOException $e) {
//     echo 'データベース接続失敗: ' . $e->getMessage();
//     exit();
// }

try {
    $pdo = new PDO('mysql:host=mysql3101.db.sakura.ne.jp;dbname=momodai_homayasan;charset=utf8', '', '');
} catch (PDOException $e) {
    echo 'データベース接続失敗: ' . $e->getMessage();
    exit();
}

// ユーザーがログインしているか確認
if (isset($_SESSION['user_id']) && isset($_SESSION['username'])) {
    $content = $_POST['content'];
    $username = $_SESSION['username']; // ユーザー名をセッションから取得

    // データベースに投稿を追加
    $stmt = $pdo->prepare("INSERT INTO posts (username, content, created_at, user_id) VALUES (:username, :content, NOW(), :user_id)");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':user_id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    // リダイレクト
    header('Location: community.php');
    exit();
} else {
    echo "ログインしてください。";
}
?>


