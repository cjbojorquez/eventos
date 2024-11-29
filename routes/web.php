<?php
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';

$authController = new AuthController();
$homeController = new HomeController();

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
echo "solicita $requestUri ";
switch ($requestUri) {
    case '/eventos/public/index.php':
        $authController->login();
        break;
    case '/login':
        $authController->login();
        break;
    case '/logout':
        $authController->logout();
        break;
    case '/home':
        $homeController->index();
        break;
    default:
        http_response_code(404);
        echo "Página no encontrada.";
}
?>
