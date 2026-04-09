<?php
ob_start();
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'config/Database.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/PeminjamanController.php';
require_once 'controllers/PSController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/ActivityLogController.php';
require_once 'controllers/KategoriController.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'login';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Routing
switch($page) {
    case 'login':
        $controller = new AuthController();
        if($action == 'process') {
            $controller->login();
        } else {
            $controller->showLogin();
        }
        break;
    case 'logout':
        $controller = new AuthController();
        $controller->logout();
        break;
    case 'dashboard':
        require_once 'views/dashboard.php';
        break;
    case 'peminjaman':
        $controller = new PeminjamanController();
        $controller->handleRequest($action);
        break;
    case 'ps':
        $controller = new PSController();
        $controller->handleRequest($action);
        break;
    case 'user':
        $controller = new UserController();
        $controller->handleRequest($action);
        break;
    case 'activity_log':
        $controller = new ActivityLogController();
        if($action == 'stats') {
            $controller->stats();
        } else {
            $controller->index();
        }
        break;
    case 'kategori':
        $controller = new KategoriController();
        $controller->handleRequest($action);
        break;
    default:
        header('Location: index.php?page=login');
        break;
}
