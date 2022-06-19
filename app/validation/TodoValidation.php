<?php
class TodoValidation {
    public $data = array();
    public $error_msgs = array();

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }

    public function getErrorMessages() {
        return $this->error_msgs;
    }

    /* 
     * 入力欄の検証
     */
    public function check() {
        if (isset($this->data['title']) && empty($this->data['title'])) {
            $this->error_msgs[] = "※タイトルが空です。タイトルを入力してください。";
        }
        if (isset($this->data['detail']) && empty($this->data['detail'])) {
            $this->error_msgs[] = "※詳細が空です。詳細を入力してください。";
        }

        if (count($this->error_msgs) > 0) {
            return false;
        }

        return true;
    }
}