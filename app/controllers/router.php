<?php
session_start();

require_once '../core/security-headers.php';
require_once '../core/database.php';
require_once '../core/session.php';
require_once 'authControllers.php';
require_once 'newsControllers.php';

// 2. Determine Action
// Checks URL (?action=x) or Form (<input name="action" value="x">)
$action = $_GET['action'] ?? $_POST['action'] ?? 'home';

// 3. Instantiate Controllers
$auth = new AuthController();
$news = new NewsController();
$pages = new PageController();

// 4. Route Traffic
switch ($action) {

    // --- AUTH ROUTES ---
    case 'login':
        // If POST, process login. If GET, show form.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Security::guard(); // Check CSRF
            $auth->login();
        } else {
            require 'app/views/auth/login.php';
        }
        break;

    case 'logout':
        Security::guard(); // Must be POST + CSRF
        $auth->logout();
        break;

    // --- NEWS ROUTES ---
    case 'news_create':
        $news->create(); // Shows empty editor
        break;

    case 'news_edit':
        $id = $_GET['id'] ?? null;
        $news->edit($id); // Shows editor with data
        break;

    case 'news_store':
        Security::guard(); // Check CSRF
        $news->store(); // Handles both Create (Insert) and Edit (Update) logic
        break;

    case 'news_delete':
        Security::guard(); // Check CSRF
        $id = $_POST['id'] ?? null;
        $news->delete($id);
        break;

    // --- STATIC PAGES ---
    case 'home':
        require 'app/views/home.php'; 
        break;

    default:
        http_response_code(404);
        require 'app/views/404.php';
        break;
}