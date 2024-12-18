<?php
require_once "controllers/UserController.php";

$controller = new UserController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($action === 'register') {
        $controller->register($username, $password);
    } elseif ($action === 'login') {
        $controller->login($username, $password);
    }
}
?>
