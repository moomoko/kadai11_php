<?php
session_start();
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    echo "<div class='admin-alert'>";
    echo "<h1>管理者画面です</h1>";
    echo "<p>この画面は管理者のみ利用可能です。</p>";
    echo "<img src='./img/badcopy.png' alt='警告' class='admin-image'>";
    echo "</div>";
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// ユーザー一覧を取得
$stmt = $pdo->prepare("SELECT * FROM users ORDER BY id DESC");
$stmt->execute();
$users = $stmt->fetchAll();

// 投稿一覧を取得
$stmt = $pdo->prepare("SELECT posts.id, posts.content, posts.created_at, users.username FROM posts JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll();

// ユーザー削除処理
if (isset($_POST['delete_user_id'])) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_POST['delete_user_id'], PDO::PARAM_INT);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}

// 投稿削除処理
if (isset($_POST['delete_post_id'])) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = :id");
    $stmt->bindParam(':id', $_POST['delete_post_id'], PDO::PARAM_INT);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}

// 日常投稿処理
if (isset($_POST['submit_daily_post']) && !empty($_POST['daily_content'])) {
    $stmt = $pdo->prepare("INSERT INTO daily_posts (content, created_at) VALUES (:content, NOW())");
    $stmt->bindParam(':content', $_POST['daily_content'], PDO::PARAM_STR);
    $stmt->execute();
    header("Location: admin_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>管理者ダッシュボード</title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <div class="admin-container">
        <h1>管理者ダッシュボード</h1>

        <h2>ユーザー管理</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>ユーザー名</th>
                <th>メール</th>
                <th>操作</th>
            </tr>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user['id']); ?></td>
                <td><?php echo htmlspecialchars($user['username']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <form method="post" action="admin_dashboard.php">
                        <input type="hidden" name="delete_user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit">削除</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h2>投稿管理</h2>
        <table>
            <tr>
                <th>投稿ID</th>
                <th>ユーザー名</th>
                <th>投稿内容</th>
                <th>日付</th>
                <th>操作</th>
            </tr>
            <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo htmlspecialchars($post['id']); ?></td>
                <td><?php echo htmlspecialchars($post['username']); ?></td>
                <td><?php echo htmlspecialchars($post['content']); ?></td>
                <td><?php echo htmlspecialchars($post['created_at']); ?></td>
                <td>
                    <form method="post" action="admin_dashboard.php">
                        <input type="hidden" name="delete_post_id" value="<?php echo $post['id']; ?>">
                        <button type="submit">削除</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>

        <h2>キャラクターの日常投稿</h2>
        <form method="post" action="admin_dashboard.php">
            <textarea name="daily_content" placeholder="キャラクターの日常を入力してください" required></textarea>
            <button type="submit" name="submit_daily_post">投稿</button>
        </form>
    </div>
</body>
</html>




