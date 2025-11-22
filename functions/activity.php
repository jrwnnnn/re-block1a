<?php
    require_once  '../includes/session-init.php';
    require_once  '../functions/connect.php';
    
    $uuid = $_GET["uuid"] ?? "";
    if (!$uuid) { echo json_encode(["online" => false]); exit; }

    $article = query("SELECT status FROM player_data WHERE uuid = ?", [$uuid], "s");

    echo json_encode(["online" => $article['status'] == 1]);
?>