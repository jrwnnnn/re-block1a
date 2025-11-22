<?php
    require_once 'includes/security-headers.php';
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';

    if (!isset($_SESSION['user_id']) && !isset($_GET['player'])) {
        header('Location: auth/login.php');
        exit();
    }
?>

<!-- <div class="flex p-5 border-green-300 border-5 rounded-sm">
   <img src="https://static.wikia.nocookie.net/minecraft_gamepedia/images/b/b5/Hero_of_the_Village_JE1_BE2.png" alt="Playpass" class="w-30">
</div> -->
