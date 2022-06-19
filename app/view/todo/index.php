<?php
require_once('./../../controller/TodoController.php');

$controller = new TodoController;
$todo_list = $controller->index();

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
    <title>TODOリスト</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="./../../public/css/main.css">
</head>
<body>
    <h1>TODOリスト</h1>
    <div><a href="./new.php">新規作成</a></div>
    <?php if($todo_list): ?>
        <ul class="list-group">
            <?php foreach ($todo_list as $todo_item) : ?>
            <li class="list-group-item">
                <label class="checkbox-label">
                    <input type="checkbox" class="todo-checkbox" data-id="<?php echo $todo_item['id']; ?>" <?php if ($todo_item['status']) echo "checked"; ?>>
                </label>
                <a href="./detail.php?todo_id=<?php echo $todo_item['id'] ?>"><?php echo $todo_item['title']; ?></a>: 
                <span class="status-txt"><?php echo $todo_item['display_status']; ?></span>
                <div class="delete-btn" data-id="<?php echo $todo_item['id']; ?>">
                    <button>削除</button>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>データなし</p>
    <?php endif; ?>

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

    <script src="./../../public/js/jquery-3.6.0.min.js"></script>
    <script>
        // 削除機能
        $(".delete-btn").click(function() {
            let todo_id = $(this).data('id');

            if (confirm("削除してもよろしいですか？ id: " + todo_id)) {
                $(".delete-btn").prop("disabled", true);

                let data = {};
                data.todo_id = todo_id;
    
                $.ajax({
                    url: './delete.php',
                    type: 'post',
                    data: data
                }).then(
                    function(data) {
                        let json = JSON.parse(data);
                        console.log("success", json);

                        if (json.result == 'success') {
                            window.location.href = "./index.php";
                        } else {
                            console.log("failed to delete.");
                            alert("failed to delete.");
                            $(".delete-btn").prop("disabled", false);
                        }
                    },
                    function() {
                        console.log("failed to delete.");
                        alert("failed to delete.");
                        $(".delete-btn").prop("disabled", false);
                    }
                );
            }
        });

        // ステータスの更新
        $('.todo-checkbox').change(function() {
            let todo_id = $(this).data('id');

            let data = {};
            data.todo_id = todo_id;
    
            $.ajax({
                url: './update_status.php',
                type: 'post',
                data: data
            }).then(
                function(data) {
                    let json = JSON.parse(data);
                    console.log("success", json);
                    if (json.result == 'success') {
                        console.log('success.');

                        let status_txt = $(this).parent().parent().find('.status-txt').text();
                        console.log(status_txt);
                        if (status_txt == '完了') {
                            status_txt = '未完了';
                        } else if (status_txt == '未完了') {
                            status_txt = '完了';
                        }

                        $(this).parent().parent().find('.status-txt').text(status_txt);

                    } else {
                        console.log("failed to update status.");
                        alert("failed to update status.");             
                    }
                }.bind(this),
                function() {
                    console.log("failed to update status.");
                    alert("failed to ajax");
                }
            );
        });

    </script>
</body>
</html>