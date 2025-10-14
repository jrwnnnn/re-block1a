<?php 
    require_once '../includes/session-init.php';
    if (isset($_SESSION['user_id'])) {
        if (isset($_POST['destroy'])) {
            require_once 'connect.php';
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $user = htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8'); 
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?"); 
            $stmt->bind_param("s", $user); 
            $sqlres = $stmt->execute(); 
            $stmt->close();
            $conn->close(); 

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