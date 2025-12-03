<?php
$error_message = ""; // Initialize error message variable so that PHP doesn't throw an undefined variable error
$has_error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $login = $_POST['login'];
    $password = $_POST['password'];
    $auth = query("SELECT * FROM users WHERE username = ? OR email = ?", [$login, $login], "ss");

    if ($auth && password_verify($password, $auth['password'])) {
        $_SESSION['username'] = $auth['username'];
        $_SESSION['email'] = $auth['email']; 
        $_SESSION['uuid'] = $auth['uuid'];
        $_SESSION['last_password_change'] = $auth['last_password_change']; 
        $_SESSION['permission_level'] = $auth['permission_level'];

        header('Location: ../index.php');
        exit();
    } else {
        $error_message = "Invalid login credentials.";
        $has_error = true;
    }
}
?>