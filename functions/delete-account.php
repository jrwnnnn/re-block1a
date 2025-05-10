<?php session_start(); 
if (isset($_POST['destroy'])){ 
    require 'connect.php'; 
    $user = htmlspecialchars($_SESSION['user_id'], ENT_QUOTES, 'UTF-8'); 
    $stmt = $conn->prepare("DELETE FROM user_data WHERE id = ?"); 
    $stmt->bind_param("s", $user); 
    $sqlres = $stmt->execute(); 
    $stmt->close();
    $conn->close(); 

    session_unset(); 
    session_destroy(); 
    header("Location: ../auth/login.php"); 
    exit; } 
 ?>