<!-- view_users.php -->
<?php
// データベース接続
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

// データベースから全ユーザー情報を取得
$sql = "SELECT * FROM users";
$stmt = $pdo->prepare($sql);
$stmt->execute();

// 結果の表示
$users = $stmt->fetchAll();
if (count($users) > 0) {
    echo "<h1>ユーザー一覧</h1>";
    foreach ($users as $user) {
        echo "ID: " . $user['id'] . ", ユーザー名: " . $user['username'] . ", メール: " . $user['email'] . "<br>";
    }
} else {
    echo "ユーザーが存在しません。";
}
?>

<!-- view_users.php にリンクを追加 -->
<td><a href="user_details.php?user_id=<?php echo $user['id']; ?>">詳細</a></td>

<a href="index.php" class="back-button">← 戻る</a>
