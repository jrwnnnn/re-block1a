<?php
require_once '../core/security-headers.php';
require_once '../core/database.php';
require_once '../core/session.php';
require_once '../core/RBAC.php';
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
    
    case 'articleAction':
        RBAC('editor', '../../index.php');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article_id = $_POST['id'] ?? null; 
            $title = $_POST['title'];
            $subtitle = $_POST['subtitle'];
            $cover = $_POST['cover'];
            $tag = $_POST['tag'];
            $spotlight = isset($_POST['spotlight']) ? 1 : 0;
            $author = $_SESSION['username'];
            $date_posted = date("Y-m-d");
            $content = $_POST['content'];
            
            if ($_POST['action'] === 'create') {
                $newsController->create($article_id, $title, $subtitle, $cover, $tag, $spotlight, $author, $date_posted, $content);
            } elseif ($_POST['action'] === 'edit') {
                $newsController->edit($article_id, $title, $subtitle, $cover, $tag, $spotlight, $content);
            }
        } else {
            header('Location: ../../index.php');
            exit();
        }
        break;

    case 'deleteArticle':
        RBAC('editor', '../../index.php');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $article_id = $_POST['id'] ?? null; 
            $newsController->delete($article_id);
        } else {
            header('Location: ../../index.php');
            exit();
        }
        break;

    default:
        http_response_code(404);
        header('Location: ../404.php');
        break;
}