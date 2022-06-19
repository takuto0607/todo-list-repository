<?php
require_once('./../../config/database.php');

class Todo {
    const STATUS_INCOMPLETE = 0;  // 未完了
    const STATUS_COMPLETED = 1;   // 完了

    const STATUS_INCOMPLETE_TXT = "未完了";
    const STATUS_COMPLETED_TXT = "完了";

    public $id;      // ID
    public $title;   // タイトル
    public $detail;  // 詳細
    public $status;  // ステータス

    public function getId() {
        return $this->id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDetail() {
        return $this->detail;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setDetail($detail) {
        $this->detail = $detail;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    /* 
     * DBからTODOリストをすべて取得
     */
    public static function findAll() {
        $pdo = new PDO(DSN, USERNAME, PASSWORD);
        $stmt = $pdo->query("SELECT * FROM todos");

        if ($stmt) {
            $todo_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            $todo_list = array();
        }

        if ($todo_list && count($todo_list) > 0) {
            foreach ($todo_list as $index => $todo_item) {
                $todo_list[$index]['display_status'] = self::getDisplayStatus($todo_item['status']);
            }
        }

        return $todo_list;
    }

    /* 
     * DBから指定されたIDのTODOリストを取得
     */
    public static function findById($todo_id) {
        $pdo = new PDO(DSN, USERNAME, PASSWORD);
        $stmt = $pdo->query(sprintf("SELECT * FROM todos WHERE id = %s;", $todo_id));

        if ($stmt) {
            $todo_item = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $todo_item = array();
        }
        
        if ($todo_item) {
            $todo_item['display_status'] = self::getDisplayStatus($todo_item['status']);
        }

        return $todo_item;
    }

    /* 
     * ステータスを画面表示用に文字列を与える
     * 0: 未完了
     * 1: 完了
     */
    public static function getDisplayStatus($todo_status) {
        if ($todo_status == self::STATUS_INCOMPLETE) {
            return self::STATUS_INCOMPLETE_TXT;
        } else if ($todo_status == self::STATUS_COMPLETED) {
            return self::STATUS_COMPLETED_TXT;
        }

        return "";
    }

    /* 
     * 新規TODOデータをDBに追加
     */
    public function save() {
        try {
            $query = sprintf (
                "INSERT INTO `todos` 
                 (`title`, `detail`, `status`, `created_at`, `updated_at`)
                 VALUES ('%s', '%s', 0, NOW(), NOW())", 
                 $this->title, $this->detail
            );
            $pdo = new PDO(DSN, USERNAME, PASSWORD);
            $pdo->beginTransaction();
            $result = $pdo->query($query);
            $pdo->commit();
        } catch (Exception $e) {
            error_log("新規作成に失敗しました。");
            error_log($e->getMessage());
            error_log($e->getTraceAsString());

            $pdo->rollBack();

            return false;
        }

        return $result;
    }

    /* 
     * DB内の該当するTODOデータを更新
     */
    public function update() {
        try {
            $query = sprintf (
                "UPDATE `todos`
                 SET `title`='%s', `detail`='%s', `updated_at`='%s'
                 WHERE id = %s",
                 $this->title, $this->detail, date("Y-m-d H:i:s"), $this->id
            );
            
            $pdo = new PDO(DSN, USERNAME, PASSWORD);
            $pdo->beginTransaction();
            $result = $pdo->query($query);
            $pdo->commit();
        } catch (Exception $e) {
            error_log("更新に失敗しました。");
            error_log($e->getMessage());
            error_log($e->getTraceAsString());

            $pdo->rollBack();

            return false;
        }

        return $result;
    }

    /* 
     * 指定されたIDのTODOデータがあるか確認
     */
    public static function isExistById($todo_id) {
        $pdo = new PDO(DSN, USERNAME, PASSWORD);
        $stmt = $pdo->query(sprintf("SELECT * FROM todos WHERE id = %s;", $todo_id));

        if ($stmt) {
            $todo_item = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $todo_item = array();
        }
        
        if ($todo_item) {
            return true;
        }
        return false;
    }

     /* 
     * 指定されたIDのTODOデータをDBから削除
     */
    public function delete() {
        try {
            $query = sprintf ("DELETE FROM `todos` WHERE id = %s", $this->id);

            $pdo = new PDO(DSN, USERNAME, PASSWORD);
            $pdo->beginTransaction();
            $result = $pdo->query($query);
            $pdo->commit();
        } catch (Exception $e) {
            error_log("削除に失敗しました。");
            error_log($e->getMessage());
            error_log($e->getTraceAsString());

            $pdo->rollBack();

            return false;
        }   

        return $result;
    }

    /* 
     * 指定されたIDのTODOデータのステータスをDBで更新
     */
    public function updateStatus() {
        try {
            $query = sprintf (
                "UPDATE `todos` SET `status`='%s', `updated_at`='%s' WHERE id = %s",
                 $this->status, date("Y-m-d H:i:s"), $this->id);
            
            $pdo = new PDO(DSN, USERNAME, PASSWORD);
            $pdo->beginTransaction();
            $result = $pdo->query($query);
            $pdo->commit();
        } catch (Exception $e) {
            error_log("ステータスの更新に失敗しました。");
            error_log($e->getMessage());
            error_log($e->getTraceAsString());

            $pdo->rollBack();

            return false;
        }

        return $result;
    }

}