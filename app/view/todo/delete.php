<?php
require_once('./../../controller/TodoController.php');

$controller = new TodoController;
$result = $controller->delete();

$resoponse = array();
if ($result) {
    $resoponse['result'] = "success";
} else {
    $resoponse['result'] = "fail";
}

echo json_encode($resoponse);