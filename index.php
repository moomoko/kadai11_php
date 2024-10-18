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
if (isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT points FROM users WHERE id = :id");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();

    $points = $user['points'];
    $username = $_SESSION['username'];
} else {
    $points = 0;
    $username = "ゲスト";
}

// 最新の投稿だけでなく、すべての投稿を取得する
$stmt = $pdo->prepare("SELECT * FROM daily_posts ORDER BY created_at DESC");
$stmt->execute();
$daily_posts = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Darumadrop+One&display=swap" rel="stylesheet">
    <title>ももだいのほめやさん</title>
</head>
<body>
    <header class="header">
        <div class="logo">ももだいのほめやさん</div>
        <div class="header-right">
            <div class="welcome-message">ようこそ、<?php echo $username; ?>さん</div>
            <div class="kind-points"><span id="user-points"><?php echo $points; ?>pt</span></div>
            <div class="login-icon">
                <a href="javascript:void(0);" onclick="toggleLoginMenu()"><img src="./img/login_icon.png" alt="ログイン"></a>
            </div>
        </div>
        <div id="login-menu" class="menu" style="display: none;">
            <ul>
                <li><a href="login.php">ログイン/とうろく</a></li>
                <li><a href="view_ranking.php">やさしいランキング</a></li>
                <li><a href="admin_dashboard.php">かんり</a></li>
                <li><a href="logout.php">ログアウト</a></li>
            </ul>
        </div>
    </header>

    <main>
        <div class="character-posts">
            <?php if ($daily_posts): ?>
                <?php foreach ($daily_posts as $post): ?>
                    <div class="post">
                        <p><?php echo htmlspecialchars($post['content']); ?></p>
                        <small><?php echo htmlspecialchars($post['created_at']); ?></small>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>まだ日常が投稿されていません。</p>
            <?php endif; ?>
        </div>
    </main>

    <footer class="footer">
        <ul class="footer-menu">
            <li><a href="index.php"><img src="./img/home_icon.png" alt="ホーム"></a></li>
            <li><a href="community.php"><img src="./img/community_icon.png" alt="コミュニティ"></a></li>
            <li><a href="game.php"><img src="./img/talk_icon.png" alt="トーク"></a></li>
            <li><a href="talk.php"><img src="./img/game_icon.png" alt="ゲーム"></a></li>
            <li><a href="shop.php"><img src="./img/shop_icon.png" alt="ショップ"></a></li>
        </ul>
    </footer>

    <script>
        function toggleLoginMenu() {
            var menu = document.getElementById('login-menu');
            menu.style.display = menu.style.display === "none" ? "block" : "none";
        }
    </script>
</body>
</html>
