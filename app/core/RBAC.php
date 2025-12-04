<?php
require_once 'session.php';
require_once 'database.php';

$roleMap = [
    'admin' => 3,
    'editor' => 2,
    'user' => 1,
    'none' => 0,
];

function getUserRole() {
    if (!isset($_SESSION['uuid'])) {
        return 'none';
    }
    
    $user = query("SELECT permission_level FROM users WHERE uuid = ?", [$_SESSION['uuid']], "s");

    if (!$user) {
        return 'none';
    }
    return $user['permission_level'] ?? 'none';
}

function RBAC($requiredLevel, $fallback = '../index.php') {
    global $roleMap;

    if (!isset($_SESSION['uuid'])) {
        header("Location: $fallback");
        exit();
    }

    $userRole = getUserRole();

    if (($roleMap[$userRole] ?? 0) < ($roleMap[$requiredLevel] ?? 0)) {
        header("Location: $fallback");
        exit();
    }
}
?>