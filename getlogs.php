<?php

header('Content-Type: application/json');
require_once ('function.php');
$username = $_GET['username'];
$password = $_GET['password'];
$vcbFunction = new VCBFunction();
if ($username != "" && $password != "")
{
    echo $vcbFunction->getLogs($username, $password);
}
else
{
    echo json_encode(array(
        "status" => "error",
        "msg" => "Username/Password invalid !!!"
    ));
}