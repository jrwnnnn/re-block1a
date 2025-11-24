<?php
    require_once '../includes/session-init.php';
    if (!isset($_SESSION['uuid'])) { 
        header('Location: ../index.php');
        exit;
    }
?>