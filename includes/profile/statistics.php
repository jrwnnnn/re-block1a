<?php
    require_once 'includes/security-headers.php';
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';

    if (!isset($_SESSION['user_id']) && !isset($_GET['player'])) {
        header('Location: auth/login.php');
        exit();
    }

    $uuid = $_GET['player'] ?? $_SESSION['uuid'];

    $sql = "SELECT 
        blockMined, blockPlaced, damageAbsorbed, damageDealt, damageTaken, damageResisted, deaths, 
        firstJoined, lastSeen, mobKills, playerKills, playTime, 
        distanceTraveled, level
    FROM player_data
    WHERE uuid = '$uuid'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $blockMined = $row['blockMined'];
        $blockPlaced = $row['blockPlaced'];
        $damageAbsorbed = $row['damageAbsorbed'];
        $damageDealt = $row['damageDealt'];
        $damageTaken = $row['damageTaken'];
        $damageResisted = $row['damageResisted'];
        $deaths = $row['deaths'];
        $firstJoined = $row['firstJoined'];
        $lastSeen = $row['lastSeen'];
        $mobKills = $row['mobKills'];
        $playerKills = $row['playerKills'];
        $playTime = $row['playTime'];
        $distanceTraveled = $row['distanceTraveled'];
        $level = $row['level'];
    }
?>
<div class="flex flex-col space-y-5 md:space-y-10">
    <div class="grid gap-10 md:grid-cols-2">
        <div class="flex flex-col">
            <div class="flex items-center">
                <img src="https://cdn-icons-png.flaticon.com/128/5528/5528021.png" alt="Statistics Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
                <p class="text-2xl font-bold text-white">Statistics</p>
            </div>
            <table class="w-full mt-2 text-sm text-gray-200">
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Blocks Broken</td>
                <td class="py-1 text-right"><?= number_format($blockMined)?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Blocks Placed</td>
                <td class="py-1 text-right"><?= number_format($blockPlaced)?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Deaths</td>
                <td class="py-1 text-right"><?= number_format($deaths)?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Distance Traveled</td>
                <td class="py-1 text-right"><?= number_format($distanceTraveled) ?> Blocks</td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Level</td>
                <td class="py-1 text-right"><?= number_format($level); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Mob Kills</td>
                <td class="py-1 text-right"><?= number_format($mobKills); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Player Kills</td>
                <td class="py-1 text-right"><?= number_format($playerKills); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Total Playtime</td>
                <td class="py-1 text-right"><?= ticksToReadable($playTime); ?></td>
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