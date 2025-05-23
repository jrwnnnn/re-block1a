<?php
    require_once '../includes/session-init.php';
    require_once 'connect.php';

    if (!isset($_SESSION['uuid'])) {
        echo json_encode(['error' => 'Not logged in']);
        exit();
    }

    $uuid = $_SESSION['uuid'];

    // Get player stats
    $sql = "SELECT stat_advancement, stat_death, stat_timeSinceDeath, exp, stat_distanceTraveled, stat_playTime FROM players WHERE uuid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $stmt->bind_result($adv, $deaths, $ticks, $level, $distance, $playtime);
    $stmt->fetch();
    $stmt->close();

    // Get death logs
    $sql = "SELECT cause, x, y, z, timestamp FROM death_log WHERE uuid = ? ORDER BY id DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $result = $stmt->get_result();

    $deathLogs = [];
    while ($row = $result->fetch_assoc()) {
        $deathLogs[] = $row;
    }

    echo json_encode([
        'stats' => [
            'adv' => (int)$adv,
            'deaths' => (int)$deaths,
            'ticks' => (int)$ticks,
            'level' => (int)$level,
            'distance' => (int)$distance,
            'playtime' => (int)$playtime
        ],
        'deathLogs' => $deathLogs
    ]);
?>