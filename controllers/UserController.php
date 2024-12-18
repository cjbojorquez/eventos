<?php
require_once "../models/UserModel.php";

class UserController {
    private $model;

    public function __construct() {
        $this->model = new UserModel("localhost", "root", "", "sistema_login");
    }

    public function register($username, $password) {
        if ($this->model->createUser($username, $password)) {
            header("Location: login.php?success=1");
        } else {
            header("Location: register.php?error=1");
        }
    }

    public function login($username, $password) {
        if ($this->model->authenticateUser($username, $password)) {
            session_start();
            $_SESSION['user'] = $username;
            header("Location: dashboard.php");
        } else {
            header("Location: login.php?error=1");
        }
    }
}
?>
