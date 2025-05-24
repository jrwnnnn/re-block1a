<?php
    require_once 'includes/session-init.php';
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
?>

<div class="space-y-10">    
    <div class="text-white">
        <p style="font-size: 16px; color: gray; text-align: center;">We're still building this feature. Please check back later!</p>
    </div>
</div>