<?php
    require_once '../includes/session-init.php';
    if (!isset($_SESSION['permission_level']) && $_SESSION['permission_level'] !== 1) { 
        header('Location:' . $fallback);
        exit;
    }
?>