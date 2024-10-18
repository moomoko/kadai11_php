<?php
session_start(); // セッション開始

// // // データベース接続
try {
    $pdo = new PDO('mysql:host=mysql3101.db.sakura.ne.jp;dbname=momodai_homayasan;charset=utf8', '', '');
} catch (PDOException $e) {
    echo 'データベース接続失敗: ' . $e->getMessage();
    exit();
}

// try {
//     $pdo = new PDO('mysql:host=localhost;dbname=ranking_db;charset=utf8', 'root', '');
// } catch (PDOException $e) {
//     echo 'データベース接続失敗: ' . $e->getMessage();
//     exit();
// }

// ユーザーがログインしているか確認
if (!isset($_SESSION['user_id'])) {
    echo 'ログインしてください。';
    exit();
}

// 全ユーザーのランキングを取得
$stmt = $pdo->prepare("SELECT username, points FROM users ORDER BY points DESC");
$stmt->execute();
$ranking = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ログインユーザーの情報を取得
$logged_in_username = $_SESSION['username'];
$logged_in_rank = null; // ログインユーザーの順位を保持する変数

// ランキング内でログインユーザーの順位を探す
foreach ($ranking as $index => $user) {
    if ($user['username'] === $logged_in_username) {
        $logged_in_rank = $index + 1; // 順位は0から始まるので+1する
        break;
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ランキング</title>
    <link rel="stylesheet" href="./css/style.css"> <!-- ここで style.css を読み込む -->
</head>
<body>
    <div class="ranking-container">
        <h1>ユーザーランキング</h1>

        <!-- ログインユーザーの順位を表示 -->
        <?php if ($logged_in_rank !== null): ?>
            <p><?php echo htmlspecialchars($logged_in_username, ENT_QUOTES, 'UTF-8'); ?>さんの順位は <?php echo $logged_in_rank; ?> 位です！</p>
        <?php else: ?>
            <p>ランキングに含まれていません。</p>
        <?php endif; ?>

        <!-- ランキング全体表示 -->
        <table>
            <tr>
                <th>順位</th>
                <th>ユーザー名</th>
                <th>ポイント</th>
            </tr>
            <?php foreach ($ranking as $index => $user): ?>
                <tr>
                    <td><?php echo $index + 1; ?></td> <!-- 順位 -->
                    <td><?php echo htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8'); ?></td> <!-- ユーザー名 -->
                    <td><?php echo $user['points']; ?></td> <!-- ポイント -->
                </tr>
            <?php endforeach; ?>
        </table>

        <!-- 戻るボタンを中央に配置 -->
        <div class="button-container">
            <a href="index.php">ホームに戻る</a>
        </div>
    </div>
</body>
</html>


