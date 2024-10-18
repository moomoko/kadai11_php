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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("UPDATE posts SET content = :content WHERE id = :id");
    $stmt->bindParam(':content', $content, PDO::PARAM_STR);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    
    if ($stmt->execute()) {
        header('Location: community.php');
    } else {
        echo "編集に失敗しました。";
    }
} else {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $post = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>投稿の編集</title>
</head>
<body>
    <h2>投稿を編集</h2>
    <form action="edit_post.php" method="POST">
        <textarea name="content"><?php echo htmlspecialchars($post['content']); ?></textarea>
        <input type="hidden" name="id" value="<?php echo $post['id']; ?>">
        <button type="submit">更新する</button>
    </form>
</body>
</html>
