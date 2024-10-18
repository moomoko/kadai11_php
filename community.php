<?php
session_start(); // セッション開始

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

// 投稿をデータベースから取得
$stmt = $pdo->prepare("SELECT * FROM posts ORDER BY created_at DESC");
$stmt->execute();
$posts = $stmt->fetchAll();

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

    <!-- ユーザー名、やさしいポイント、ログインアイコン -->
    <div class="header-right">
        <div class="welcome-message">
            ようこそ、<?php echo $username; ?>さん
        </div>

        <div class="kind-points">
            <span id="user-points"><?php echo $points; ?>pt</span>
        </div>

        <div class="login-icon">
            <a href="javascript:void(0);" onclick="toggleLoginMenu()">
                <img src="./img/login_icon.png" alt="ログイン">
            </a>
        </div>
    </div>

    <!-- ログインメニュー -->
    <div id="login-menu" class="menu" style="display: none;">
        <ul>
            <li><a href="login.php">ログイン/とうろく</a></li>
            <li><a href="view_ranking.php">やさしいランキング</a></li>
        </ul>
    </div>
</header>

<main>
    <!-- 投稿一覧 -->
    <div class="posts">
        <?php foreach ($posts as $post): ?>
            <div class="post">
                <div class="post-header">
                    <!-- 名前と日付部分を一つの div にまとめる -->
                    <div class="post-header-left">
                        <span class="post-username"><?php echo htmlspecialchars($post['username']); ?></span>
                        <span class="post-time"><?php echo htmlspecialchars($post['created_at']); ?></span>
                    </div>

                    <!-- 3点リーダーメニュー -->
                    <div class="post-menu">
                        <button class="menu-button" onclick="toggleMenu(<?php echo $post['id']; ?>)">⋮</button>
                        <div class="menu-content" id="menu-<?php echo $post['id']; ?>" style="display: none;">
                            <a href="edit_post.php?id=<?php echo $post['id']; ?>">編集</a><br>
                            <a href="delete_post.php?id=<?php echo $post['id']; ?>">削除</a>
                        </div>
                    </div>
                </div>
                <div class="post-content">
                    <p><?php echo htmlspecialchars($post['content']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- プラスボタンで新規投稿フォームを開く -->
    <div class="plus-button" onclick="togglePostForm()">+</div>

    <div id="post-form" style="display: none;">
        <form action="insert_post.php" method="POST">
            <textarea name="content" placeholder="投稿内容を入力"></textarea>
            <button type="submit">投稿</button>
        </form>
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
    // 3点リーダーメニューを表示/非表示に切り替える関数
    function toggleMenu(id) {
        var menu = document.getElementById('menu-' + id);
        if (menu.style.display === "none") {
            menu.style.display = "block";
        } else {
            menu.style.display = "none";
        }
    }

    // 画面外をクリックするとメニューを閉じる
    document.addEventListener('click', function(event) {
        var menus = document.querySelectorAll('.menu-content');
        menus.forEach(function(menu) {
            if (!menu.contains(event.target) && !event.target.classList.contains('menu-button')) {
                menu.style.display = 'none';
            }
        });
    });

    // 新規投稿フォームを表示/非表示に切り替える関数
    function togglePostForm() {
        var form = document.getElementById('post-form');
        if (form.style.display === "none") {
            form.style.display = "block";
        } else {
            form.style.display = "none";
        }
    }
</script>
</body>
</html>




