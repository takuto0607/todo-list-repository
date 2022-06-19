<?php
require_once('./../../controller/TodoController.php');

$controller = new TodoController;
$todo_item = $controller->detail();
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=devicewidth, initial-scale=1.0">
    <title>TODO詳細</title>

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="./../../public/css/main.css">
</head>
<body>
    <h1>TODO詳細</h1>
    <div class="detail-content">
        <div class="detail-block">
            <div>タイトル：</div>
            <div><?php echo $todo_item['title']; ?></div>
        </div>
        <div class="detail-block">
            <div>詳細：</div>
            <div><?php echo $todo_item['detail']; ?></div>
        </div>
        <div class="detail-block">
            <div>ステータス：</div>
            <div><?php echo $todo_item['display_status']; ?></div>
        </div>
    </div>
    <div>
        <button><a class="edit-btn" href="./edit.php?todo_id=<?php echo $todo_item['id']; ?>">編集</a></button>
    </div>
</body>
</html>