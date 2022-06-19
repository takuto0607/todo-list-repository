<?php
require_once('./../../controller/TodoController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new TodoController();
    $controller->new();
    exit;
}

$title = '';
$detail = '';
// GETリクエストがある時、パラメータを取得
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['title'])) {
        $title = $_GET['title'];
    }
    if (isset($_GET['detail'])) {
        $detail = $_GET['detail'];
    }
}

// エラーメッセージをセッションから取得
session_start();
$error_msgs = $_SESSION['error_msgs'];
unset($_SESSION['error_msgs']);

?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=devicewidth, initial-scale=1.0">
    <title>新規TODO作成</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="./../../public/css/main.css">
</head>
<body>
    <h1>新規TODO作成</h1>
    <form action="./new.php" method="post">
        <div>
            <div>タイトル</div>
            <input name="title" type="text" value="<?php echo $title; ?>">
        </div>
        <div>
            <div>詳細</div>
            <textarea name="detail"><?php echo $detail; ?></textarea>
        </div>
        <button class="register-btn" type="submit">登録</button>
    </form>

    <!-- エラーメッセージを表示 -->
    <?php if ($error_msgs) : ?>
    <div>
        <ul class="error-content">
            <?php foreach ($error_msgs as $error_msg) :?>
            <li><?php echo $error_msg; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

</body>
</html>