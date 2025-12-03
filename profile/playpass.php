<?php
require_once 'includes/security-headers.php';
require_once 'includes/session-init.php';
require_once 'functions/connect.php';
require_once 'includes/RBAC.php';
RBAC ('user', 'auth/login.php');

$uuid = $_SESSION['uuid'];
$reciepts = query("SELECT * FROM playpass_receipts WHERE uuid = ? ORDER BY referenceID DESC LIMIT 10", [$uuid], "s");
if (isset($reciepts['referenceID'])) {
    $reciepts = [$reciepts];
}

?>

<div class="flex flex-col">
    
    <div class="flex items-center mb-2">
        <img src="https://cdn-icons-png.flaticon.com/128/5528/5528021.png" alt="Statistics Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
        <p class="text-2xl font-bold text-white">Transactions History</p>
    </div> 
</div>
