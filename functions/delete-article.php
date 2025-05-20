<?php
    require_once '../includes/session-init.php';

    if (!isset($_GET['id'])) { 
        header('Location: ../news.php');
        exit;
    }
    
    define('ADMIN_PERMISSION_LEVEL', 1);

    if (isset($_SESSION['permission_level']) && $_SESSION['permission_level'] == ADMIN_PERMISSION_LEVEL) {
        require_once 'connect.php';
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $article_id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("s", $article_id);

        if ($stmt->execute()) {
            header('Location: ../news.php');
            $stmt->close();
            $conn->close();
            exit;
        } else {
            echo "Error deleting article: " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit;
        }
        
    } else {
        header('Location: ../news.php');
        exit;
    }
?>