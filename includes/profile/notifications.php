<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
?>

<div class="space-y-10 md:pr-120">    
    <p style="font-size: 16px; color: gray; text-align: center;">We're still building this feature. Please check back later!</p>
</div>