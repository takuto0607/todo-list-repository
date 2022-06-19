<?php
require_once('./../../controller/TodoController.php');

$controller = new TodoController;
$result = $controller->updateStatus();

$response = array();
if ($result) {
    $response['result'] = "success";
} else {
    $response['result'] = "fail";
}

echo json_encode($response);