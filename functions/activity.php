<?php
    require_once  '../includes/session-init.php';
    require_once  '../functions/connect.php';
    
    $uuid = $_GET["uuid"] ?? "";
    if (!$uuid) { echo json_encode(["online" => false]); exit; }

    $article = $conn->query("SELECT status FROM player_data WHERE id = " . (int)$uuid)->fetch_assoc();

    echo json_encode(["online" => $article['status'] == 1]);
?>