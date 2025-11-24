<?php 
    require_once '../includes/session-init.php';
    require_once 'connect.php';

    if (isset($_SESSION['uuid'])) {
        if (isset($_POST['destroy'])) {
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $user = sanitize($_SESSION['uuid']); 
            $stmt = query("DELETE FROM users WHERE id = ?", [$user], "s"); 
            session_unset(); 
            session_destroy(); 
            header("Location: ../auth/login.php"); 
            exit; 
        } 
    } else {
        header('Location: ../index.php'); 
        exit; 
    }
 ?>