<?php
class HomeController {
    public function index() {
        session_start();
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: /login');
            exit();
        }

        require __DIR__ . '/../views/home/index.php';
    }
}
?>
