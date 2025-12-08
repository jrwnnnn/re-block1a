<?php
require_once __DIR__ . '/../core/security-headers.php';
require_once __DIR__ . '/../core/database.php';

class adminControllers {
    public function createResetToken() {
        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            $_SESSION['error_message'] = "Email is required.";
            header('Location: ../create-token.php');
            exit();
        }

        // Check if user exists
        $user = query("SELECT uuid FROM users WHERE email = ?", [$email], "s");
        if (empty($user)) {
            $_SESSION['error_message'] = "No user found with that email.";
            header('Location: ../create-token.php');
            exit();
        }

        // Generate token
        $token = bin2hex(random_bytes(32));
        
        query("DELETE FROM reset_tokens WHERE email = ?", [$email], "s");
        
        query("INSERT INTO reset_tokens (email, token) VALUES (?, ?)", [$email, $token], "ss");

        $link = "https://block1a.onrender.com/app/forgot-password.php?token=$token";
        
        $_SESSION['generated_link'] = $link;
        header('Location: ../create-token.php');
        exit();
    }
}
