<?php
require_once 'includes/session-init.php';
require_once 'includes/security-headers.php';
require_once 'functions/connect.php';
require_once 'includes/RBAC.php';
RBAC ('user', 'index.php');

$deathResult = query("SELECT cause, x, y, z, world, timestamp FROM death_log WHERE uuid = ? ORDER BY id DESC LIMIT 10", [$uuid], "s");
?>

<div class="flex flex-col">
    <div class="flex items-center mb-4">
        <img src="https://cdn-icons-png.flaticon.com/128/18650/18650881.png" alt="Death Logs Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
        <p class="text-2xl font-bold text-white">Death Log</p>
    </div>
    <?php if (!empty($deathResult)): ?>
        <?php foreach ($deathResult as $death): ?>
            <div onclick="window.location.href='bluemap.php?x=<?= $death['x'] ?>&z=<?= $death['z']; ?>&world=<?= $death['world'] ?>&zoom=50'" class="px-4 py-3 mb-2 transition duration-200 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 hover:shadow-lg">
                <p class="font-bold text-white"><?= $death['cause'] ?></p>
                <p class="text-sm text-gray-400">
                    <?= isset($death['x']) ? $death['x'] : 'N/A' ?>, 
                    <?= isset($death['y']) ? $death['y'] : 'N/A' ?>, 
                    <?= isset($death['z']) ? $death['z'] : 'N/A' ?>, 
                    <?php
                        $world = match ($death['world']) {
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
                <p class="text-sm text-gray-400"><?= date('F j, Y - h:i A', strtotime($death['timestamp'])) ?: 'N/A' ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-gray-400">No death records found.</p>
    <?php endif; ?>
    </div>
</div> 