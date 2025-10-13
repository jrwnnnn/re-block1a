<?php
    require_once 'includes/session-init.php';
    require_once 'includes/security-headers.php';
    require_once 'functions/connect.php';
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }

    $sql = "SELECT cause, x, y, z, world, timestamp FROM death_log WHERE uuid = '$uuid' ORDER BY id DESC LIMIT 10";
    $deathResult = $conn->query($sql);
    $conn->close();
?>

<div class="flex flex-col">
    <div class="flex items-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/128/18650/18650881.png" alt="Death Logs Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
        <p class="text-2xl font-bold text-white">Death Log</p>
    </div>
    <?php if ($deathResult && $deathResult->num_rows > 0): ?>
        <?php while ($row = $deathResult->fetch_assoc()): ?>
            <div onclick="window.location.href='bluemap.php?x=<?= $row['x'] ?>&z=<?= $row['z']; ?>&world=<?= $row['world'] ?>&zoom=50'" class="px-4 py-3 mb-2 transition duration-200 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 hover:shadow-lg">
                <p class="font-bold text-white"><?= $row['cause'] ?></p>
                <p class="text-sm text-gray-400">
                    <?= isset($row['x']) ? $row['x'] : 'N/A' ?>, 
                    <?= isset($row['y']) ? $row['y'] : 'N/A' ?>, 
                    <?= isset($row['z']) ? $row['z'] : 'N/A' ?>, 
                    <?php
                        $world = match ($row['world']) {
                            'world' => 'Overworld',
                            'world_nether' => 'Nether',
                            'world_the_end' => 'The End',
                            'wilds' => 'Shattered Wilds',
                            'wilds_nether' => 'Wild Nether',
                            default => 'Unrecorded',
                        };
                    ?>
                    <?= $world?>
                </p>
                <p class="text-sm text-gray-400"><?= date('F j, Y - h:i A', strtotime($row['timestamp'])) ?: 'N/A' ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="flex items-center justify-center px-4 py-3 mb-2 bg-gray-800 rounded-lg">
            <p class="text-white">No death records. Yet.</p>
        </div>
    <?php endif; ?>
    </div>
</div> 