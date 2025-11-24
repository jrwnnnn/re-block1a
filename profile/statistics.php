<?php
    require_once 'includes/security-headers.php';
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';

    if (!isset($_SESSION['uuid']) && !isset($_GET['player'])) {
        header('Location: auth/login.php');
        exit();
    }

    $uuid = $_GET['player'] ?? $_SESSION['uuid'];

    $playerStatistics = query("SELECT 
        blockMined, blockPlaced, damageAbsorbed, damageDealt, damageTaken, 
        damageResisted,  distanceTraveled, deaths, level,
        mobKills, playerKills, playTime
    FROM player_statistics
    WHERE uuid = ?", [$uuid], "s");

    $playerData = query("SELECT firstJoined, lastSeen FROM player_data WHERE uuid = ?", [$uuid], "s");
?>
<div class="flex flex-col space-y-5 md:space-y-10">
    <div>
        <?php include 'playpass.php'; ?>
    </div>
    <div class="grid gap-10 md:grid-cols-2">
        <div class="flex flex-col">
            <div class="flex items-center">
                <img src="https://cdn-icons-png.flaticon.com/128/5528/5528021.png" alt="Statistics Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
                <p class="text-2xl font-bold text-white">Statistics</p>
            </div>
            <table class="w-full mt-2 text-sm text-gray-200">
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Blocks Broken</td>
                <td class="py-1 text-right"><?= number_format($playerStatistics['blockMined'])?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Blocks Placed</td>
                <td class="py-1 text-right"><?= number_format($playerStatistics['blockPlaced'])?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Deaths</td>
                <td class="py-1 text-right"><?= number_format($playerStatistics['deaths'])?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Distance Traveled</td>
                <td class="py-1 text-right"><?= number_format($playerStatistics['distanceTraveled']) ?> Blocks</td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Level</td>
                <td class="py-1 text-right"><?= number_format($playerStatistics['level']); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Mob Kills</td>
                <td class="py-1 text-right"><?= number_format($playerStatistics['mobKills']); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Player Kills</td>
                <td class="py-1 text-right"><?= number_format($playerStatistics['playerKills']); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Total Playtime</td>
                <td class="py-1 text-right"><?= ticksToReadable($playerStatistics['playTime']); ?></td>
                </tr>
            </table>
        </div>
        <div>
            <?php include 'combat.php'; ?>
        </div>
    </div>
    <div>
        <?php include 'deathlog.php'; ?>
    </div>
</div>
<script src="script/armor_set.js"></script>