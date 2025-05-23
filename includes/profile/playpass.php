<?php
    require_once 'includes/session-init.php';
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
?>

<div class="space-y-10 md:pr-30">    
    <div class="text-white">
        <p class="mb-5 text-2xl font-bold">PlayPass</p>
           
    </div>
</div>