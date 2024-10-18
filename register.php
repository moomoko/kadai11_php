<?php
// エラーメッセージを表示する設定
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

// 新規登録処理
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; // パスワード処理は簡略化しています

    // ユーザー名がすでに存在するか確認
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->fetch()) {
        echo "このユーザー名は既に使用されています。";
    } else {
        // 新しいユーザーをデータベースに追加
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, points) VALUES (:username, :email, :password, 0)");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
        if ($stmt->execute()) {
            // 出力があるとリダイレクトが失敗するので、リダイレクト前には何も表示しない
            header('Location: login.php');
            exit(); // exit()を忘れないように
        } else {
            echo "新規登録に失敗しました。";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>新規登録</title>
</head>
<body>
    <div class="register-container">
        <h2>新規登録</h2>
        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="ユーザー名" required>
            <input type="email" name="email" placeholder="メールアドレス" required>
            <input type="password" name="password" placeholder="パスワード" required>
            <button type="submit" name="register">新規登録</button>
        </form>
    </div>
</body>
</html>

