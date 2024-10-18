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

// ログイン処理
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password']; // パスワード処理は簡略化しています

    // ユーザー名とパスワードの確認
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch();

    if ($user) {
        // ユーザーが存在する場合はログイン成功
        $_SESSION['user_id'] = $user['id'];  // ユーザーIDをセッションに保存
        $_SESSION['username'] = $user['username'];  // ユーザー名をセッションに保存
        $_SESSION['is_admin'] = $user['is_admin'];  // 管理者フラグをセッションに保存

        // 5ポイントのボーナスを追加
        $stmt = $pdo->prepare("UPDATE users SET points = points + 5 WHERE id = :id");
        $stmt->bindParam(':id', $user['id'], PDO::PARAM_INT);
        $stmt->execute();

        // 管理者かどうかでリダイレクトを分ける
        if ($user['is_admin'] == 1) {
            // 管理者ページへリダイレクト
            header('Location: admin_dashboard.php');
        } else {
            // 通常ユーザー向けページへリダイレクト
            header('Location: index.php');
        }
        exit();
    } else {
        echo "ログイン失敗。ユーザー名またはパスワードが正しくありません。";
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <title>ログイン</title>
</head>
<body>
    <div class="login-container">
        <h2>ログイン</h2>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="ユーザー名" required>
            <input type="password" name="password" placeholder="パスワード" required>
            <button type="submit" name="login">ログイン</button>
        </form>
        <!-- 新規登録ページへのリンク -->
        <a href="register.php">アカウントの新規登録</a>
    </div>
</body>
</html>
