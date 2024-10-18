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

if (isset($_GET['product_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
    $stmt->bindParam(':id', $_GET['product_id'], PDO::PARAM_INT);
    $stmt->execute();
    $product = $stmt->fetch();
    
    if (!$product) {
        echo '商品が見つかりませんでした。';
        exit();
    }
} else {
    echo '商品IDが指定されていません。';
    exit();
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="./css/style.css">
</head>
<body>
    <h1><?php echo htmlspecialchars($product['name']); ?></h1>
    <img src="/kadai10_auth/img/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
    <p>価格: ¥<?php echo htmlspecialchars($product['price']); ?></p>
    <p><?php echo htmlspecialchars($product['description']); ?></p>
    <a href="shop.php">戻る</a>
</body>
</html>
