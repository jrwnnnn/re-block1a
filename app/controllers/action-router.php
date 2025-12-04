<?php
require_once '../core/security-headers.php';
require_once '../core/database.php';
require_once '../core/session.php';
require_once 'userControllers.php';
require_once 'newsControllers.php';

$action = $_GET['action'] ?? $_POST['action'];

$userController = new userControllers();
$newsController = new newsControllers();

switch ($action) {

    case 'logout':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['csrf_token'] === ($_POST['csrf_token'] ?? '')) {
            $userController->logout();
        } else {
            header('Location: ../../index.php');
            exit();
        }
        break;
    
    case 'deleteAccount':
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['csrf_token'] === ($_POST['csrf_token'] ?? '')) {
            $userController->deleteAccount();
        } else {
            header('Location: ../../index.php');
            exit();
        }
        break;
    
    case 'createArticle':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newsController->createArticle();
        } else {
            header('Location: ../../index.php');
            exit();
        }
        break;

    default:
        http_response_code(404);
        header('Location: ../404.php?');
        break;
}