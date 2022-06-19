<?php
require_once('./../../model/TodoClass.php');
require_once('./../../validation/TodoValidation.php');

class TodoController {
    /* 
     * TODOデータの全リストを一覧表示
     */
    public function index() {
        $todo_list = Todo::findAll();
        return $todo_list;
    }

    /* 
     * TODOデータについて詳細を表示
     */
    public function detail() {
        $todo_id = $_GET['todo_id'];

        // 該当するIDがない時、エラーページへ遷移
        if (!$todo_id) {
            header("Location: ./../error/404.php");
            return;
        }
        if (Todo::isExistById($todo_id) === false) {
            header("Location: ./../error/404.php");
            return;
        }

        $todo_item = Todo::findById($todo_id);
        return $todo_item;
    }

    /* 
     * POSTリクエストで送信されたパラメータから新規TODOデータを作成
     */
    public function new() {
        $valid_data = array (
            'title' => $_POST['title'],
            'detail' => $_POST['detail']
        );

        // 入力欄に何も入力されてない時、エラーメッセージを表示
        $validation = new TodoValidation;
        $validation->setData($valid_data);
        if ($validation->check() === false) {
            $error_msgs = $validation->getErrorMessages();

            session_start();
            $_SESSION['error_msgs'] = $error_msgs;

            $params = sprintf("?title=%s&detail=%s", $_POST['title'], $_POST['detail']);
            header(sprintf("Location: ./new.php%s", $params));
            return;
        }

        $new_data = $validation->getData();

        $todo_data = new Todo;
        $todo_data->setTitle($new_data['title']);
        $todo_data->setDetail($new_data['detail']);
        $result = $todo_data->save();

        if ($result === false) {
            $params = sprintf("?title=%s&detail=%s", $new_data['title'], $new_data['detail']);
            header(sprintf("Location: ./new.php%s", $params));
            return;
        }

        header("Location: ./index.php");
    }

    /* 
     * GETリクエストで送信されたパラメータから該当のTODOデータを表示
     */
    public function edit() {
        $todo_id = '';
        $params = array();
        
        // GETリクエストからTODOデータのパラメータを取得
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            if (isset($_GET['todo_id'])) {
                $todo_id = $_GET['todo_id'];
            }
            if (isset($_GET['title'])) {
                $params['title'] = $_GET['title'];
            }
            if (isset($_GET['detail'])) {
                $params['detail'] = $_GET['detail'];
            }
        }

        // 該当のTODOデータがない場合、エラーページへ遷移
        if (!$todo_id) {
            header("Location: ./../error/404.php");
            return;
        }
        if (Todo::isExistById($todo_id) === false) {
            header("Location: ./../error/404.php");
            return;
        }

        // 該当するIDのTODOデータを取得
        $todo_item = Todo::findById($todo_id);
        if (!$todo_item) {
            header("Location: ./../error/404.php");
            return;
        }

        $todo_data = array (
            "todo_item" => $todo_item,
            "params"    => $params
        );

        return $todo_data;
    }

    /* 
     * TODOリストのデータを更新
     */
    public function update() {
        if (!$_POST['todo_id']) {
            session_start();
            $_SESSION['error_msgs'] = "指定したIDに該当するデータはありません。";
            header("Location: ./index.php");
            return;
        }

        if (Todo::isExistById($_POST['todo_id']) === false) {
            $params = sprintf("?todo_id=%s&title=%s&detail=%s", $_POST['todo_id'], $_POST['title'], $_POST['detail']);
            header(sprintf("Location: ./edit.php%s", $params));
            return;
        }

        $valid_data = array (
            "todo_id" => $_POST['todo_id'],
            "title" => $_POST['title'],
            "detail" => $_POST['detail']
        );

        // 入力欄に何も入力されてない時、エラーメッセージを表示
        $validation = new TodoValidation;
        $validation->setData($valid_data);
        if ($validation->check() === false) {
            $error_msgs = $validation->getErrorMessages();

            session_start();
            $_SESSION['error_msgs'] = $error_msgs;

            $params = sprintf("?todo_id=%s&title=%s&detail=%s", $_POST['todo_id'], $_POST['title'], $_POST['detail']);
            header(sprintf("Location: ./edit.php%s", $params));
            return;
        }

        $updated_data = $validation->getData();

        $todo_data = new Todo;
        $todo_data->setId($updated_data['todo_id']);
        $todo_data->setTitle($updated_data['title']);
        $todo_data->setDetail($updated_data['detail']);
        $result = $todo_data->update();

        if ($result === false) {
            $params = sprintf("?title=%s&detail=%s", $updated_data['title'], $updated_data['detail']);
            heaer(sprintf("Location: ./edit.php%s", $params));
            return;
        }

        header("Location: ./index.php");
    }

    /* 
     * 該当するTODOデータを削除（非同期通信）
     */
    public function delete() {
        // POSTリクエストからIDを取得
        $todo_id = $_POST['todo_id'];

        // 指定されたIDがない時、エラーログを流す
        if (!$todo_id) {
            error_log(sprintf("[TodoController][delete]record is not found. todo_id: %s", $todo_id));
            return false;
        }
        if (Todo::isExistById($todo_id) === false) {
            error_log(sprintf("[TodoController][delete]record is not found. todo_id: %s", $todo_id));
            return false;
        }

        $todo_data = new Todo;
        $todo_data->setId($todo_id);
        $result = $todo_data->delete();

        return $result;
    }

    /* 
     * 該当するTODOデータのステータスを更新（非同期通信）
     */
    public function updateStatus() {
        // POSTリクエストからIDを取得
        $todo_id = $_POST['todo_id'];

        // 指定されたIDがない時、エラーログを流す
        if (!$todo_id) {
            error_log(sprintf("[TodoController][updateStatus]record is not found. todo_id: %s", $todo_id));
            return false;
        }
        if (Todo::isExistById($todo_id) === false) {
            error_log(sprintf("[TodoController][updateStatus]record is not found. todo_id: %s", $todo_id));
            return false;
        }

        $todo_item = Todo::findById($todo_id);
        if (!$todo_item) {
            error_log(sprintf("[TodoController][updateStatus]record is not found. todo_id: %s", $todo_id));
            return false;
        }

        // ステータスの変更
        $status = $todo_item['status'];
        if ($status == Todo::STATUS_INCOMPLETE) {
            $status = Todo::STATUS_COMPLETED;
        } else if ($status == Todo::STATUS_COMPLETED) {
            $status =  Todo::STATUS_INCOMPLETE;
        }

        $todo_data = new Todo;
        $todo_data->setId($todo_id);
        $todo_data->setStatus($status);
        $result = $todo_data->updateStatus();

        return $result;
    }
}