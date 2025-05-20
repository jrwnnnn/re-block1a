<?php
    require_once 'includes/session-init.php';
    unset($_SESSION['id']);

    session_unset();
    session_destroy();

    header("Location: ../index.php");
exit;
?>