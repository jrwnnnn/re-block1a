<?php
    require_once  '../includes/session-init.php';
    require_once  '../functions/connect.php';
    
    $uuid = $_GET["uuid"] ?? "";
    if (!$uuid) { echo json_encode(["online" => false]); exit; }

    $sql = "SELECT online FROM players WHERE uuid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $stmt->bind_result($online);
    $stmt->fetch();
    $stmt->close();

    echo json_encode(["online" => $online == 1])
?>