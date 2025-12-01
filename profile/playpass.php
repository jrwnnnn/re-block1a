<?php
require_once '../includes/security-headers.php';
require_once '../includes/session-init.php';
require_once '../functions/connect.php';
require_once '../includes/RBAC.php';
RBAC ('user', '../auth/login.php');
?>

<!-- <div class="flex p-5 border-green-300 border-5 rounded-sm">
   <img src="https://static.wikia.nocookie.net/minecraft_gamepedia/images/b/b5/Hero_of_the_Village_JE1_BE2.png" alt="Playpass" class="w-30">
</div> -->
