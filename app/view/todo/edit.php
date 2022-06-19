<?php
require_once('./../../controller/TodoController.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new TodoController();
    $controller->update();
    exit;
}

/*
 * $todo_item  DBから取得したTODOリストのデータ
 * $params     GETリクエストから取得したTODOリストのデータ
 */
$controller = new TodoController();
$todo_data = $controller->edit();
$todo_item = $todo_data['todo_item'];
$params = $todo_data['params'];

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
    <title>TODO編集</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="./../../public/css/main.css">
</head>
<body>
    <h1>TODO編集</h1>
    <form action="./edit.php" method="post">
        <div>
            <div>タイトル</div>
            <input name="title" type="text" value="<?php if (isset($params['title'])) { echo $params['title']; } else { echo $todo_item['title']; } ?>">
        </div>
        <div>
            <div>詳細</div>
            <textarea name="detail"><?php if (isset($params['detail'])) { echo $params['detail']; } else { echo $todo_item['detail']; } ?></textarea>
        </div>
        <input type="hidden" name="todo_id" value=<?php echo $todo_item['id']; ?>>
        <button class="update-btn" type="submit">更新</button>
    </form>

    <!-- エラーメッセージを表示 -->
    <?php if ($error_msgs) : ?>
    <div>
        <ul class="error-content">
            <?php foreach ($error_msgs as $error_msg) : ?>
            <li><?php echo $error_msg; ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

</body>
</html>