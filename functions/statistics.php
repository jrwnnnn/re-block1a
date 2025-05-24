<?php
    require_once '../includes/session-init.php';
    require_once 'connect.php';

    if (!isset($_SESSION['uuid'])) {
        echo json_encode(['error' => 'Not logged in']);
        exit();
    }

    $uuid = $_SESSION['uuid'];

    $sql = "SELECT cause, x, y, z, timestamp FROM death_log WHERE uuid = ? ORDER BY id DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $result = $stmt->get_result();

    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($logs);
    exit();
?>