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

// フォームが送信された場合の処理
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // フォーム入力が空でないか確認
    if (!empty($username) && !empty($email)) {
        // データベースにユーザーを追加する処理
        $stmt = $pdo->prepare("INSERT INTO users (username, email) VALUES (:username, :email)");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo "ユーザー追加に成功しました。";
        } else {
            echo "ユーザー追加に失敗しました。";
        }
    } else {
        echo "無効な入力です。全ての項目を入力してください。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー追加</title>
</head>
<body>
    <h1>ユーザー追加フォーム</h1>
    <!-- フォーム部分 -->
    <form action="insert_user.php" method="POST">
        <label for="username">ユーザー名:</label>
        <input type="text" id="username" name="username" required>
        <br><br>
        <label for="email">メールアドレス:</label>
        <input type="email" id="email" name="email" required>
        <br><br>
        <button type="submit">ユーザー追加</button>
    </form>

    <!-- 戻るボタン -->
    <a href="index.php">← 戻る</a>
</body>
</html>


