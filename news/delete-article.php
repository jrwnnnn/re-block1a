<?php
    require_once '../includes/session-init.php';
    require_once 'connect.php';
    require_once 'RBAC-1.php';
        $fallback = '../news.php';
    
    $stmt = query("DELETE FROM articles WHERE id = ?", [$_GET['id']], "s");

    if ($stmt) {
        header('Location: ../news.php');
        exit;
    } else {
        echo "Error deleting article: " . $stmt->error;
        exit;
    }
?>