<?php
    session_start();
    require 'connect.php';

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    if (!isset($_GET['id'])) { 
        header('Location: ../news.php');
        exit;
    }

    if (isset($_SESSION['permission_level']) && $_SESSION['permission_level'] == 1) {
        $article_id = $_GET['id'];

        $stmt = $conn->prepare("DELETE FROM articles WHERE id = ?");
        $stmt->bind_param("s", $article_id);

        if ($stmt->execute()) {
            header('Location: ../news.php');
            $stmt->close();
            $conn->close();
            exit;
        }
    } else {
        header('Location: ../news.php');
        exit;
    }
?>