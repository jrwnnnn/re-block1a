<?php
require_once 'session.php';
require_once 'database.php';

function RBAC($requiredLevel, $fallback = '../index.php') {
    if (!isset($_SESSION['uuid'])) {
        header("Location: $fallback");
        exit();
    }

    $user = query("SELECT permission_level FROM users WHERE uuid = ?", [$_SESSION['uuid']], "s");
    $userRole = $user['permission_level'] ?? 'none';
    $roleMap = [
        'admin' => 3,
        'editor' => 2,
        'user' => 1,
        'none' => 0,
    ];

    if (($roleMap[$userRole] ?? 0) < ($roleMap[$requiredLevel] ?? 0)) {
        header("Location: $fallback");
        exit();
    }
}
?>