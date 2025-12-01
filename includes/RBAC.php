<?php
require_once 'session-init.php';
function RBAC($level, $fallback = '../index.php') {
    $roleMap = [
        'admin' => 3, // administrator
        'editor' => 2, // content editor
        'user' => 1, // regular authenticated user
        'none' => 0, // default for unauthenticated users
    ];
    if (!isset($_SESSION['permission_level']) || $roleMap[$_SESSION['permission_level']] < $roleMap[$level]) {
        header("Location: $fallback");
        exit;
    }
}
?>