<?php
require_once __DIR__ . '/../core/security-headers.php';

class userControllers {

    public function logout() {
        session_unset();
        session_destroy();

        header('Location: ../../index.php');
        exit();
    }

    public function deleteAccount() {
        $delete = query("DELETE FROM users WHERE uuid = ?", [$_SESSION['uuid']], "s"); 
        session_unset(); 
        session_destroy(); 
        header("Location: ../../index.php"); 
        exit();
    }
}