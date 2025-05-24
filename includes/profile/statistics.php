<?php
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
    
    $uuid = $_SESSION['uuid'];
    
    $sql = "SELECT advancement, blockMined, blockPlaced, damageAbsorbed, damageDealt, damageTaken, deaths, firstJoined, lastSeen, mobKills, playerKills, playTime, timeSinceDeath, distanceTraveled, level FROM statistics WHERE uuid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $stmt->bind_result($advancement, $blockMined, $blockPlaced, $damageAbsorbed, $damageDealt, $damageTaken, $deaths, $firstJoined, $lastSeen, $mobKills, $playerKills, $playTime, $timeSinceDeath, $distanceTraveled, $level);
    $stmt->fetch();
    $stmt->close();

    $sql = "SELECT cause, x, y, z, timestamp FROM death_log WHERE uuid = ? ORDER BY id DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $result = $stmt->get_result();

    function ticksToReadable($ticks) {
        if (!is_numeric($ticks) || $ticks <= 0) return "0s";

        $seconds = floor($ticks / 20);
        $minutes = floor($seconds / 60);
        $hours   = floor($minutes / 60);
        $days    = floor($hours / 24);

        $seconds = $seconds % 60;
        $minutes = $minutes % 60;
        $hours   = $hours % 24;

        $parts = [];
        if ($days > 0)    $parts[] = "{$days}d";
        if ($hours > 0)   $parts[] = "{$hours}h";
        if ($minutes > 0) $parts[] = "{$minutes}m";
        if ($seconds > 0 && empty($parts)) $parts[] = "{$seconds}s";

        return implode(' ', $parts);
    }

?>

<div class="flex flex-col space-y-5 md:space-y-10">
    <!-- Beta Warning -->
    <div class="flex items-center justify-center gap-2 px-4 py-2 mb-6 bg-red-600 rounded-lg">
        <img src="https://cdn-icons-png.flaticon.com/128/9291/9291673.png" alt="Development Icon" class="inline w-5 mr-2 align-middle md:w-4" style="filter: invert(1);">
        <p class="text-sm font-semibold text-white"> This page is currently in Development. Features may change or break.</p>
    </div>

    <!-- Top Card -->
    <div class="hidden p-6 space-y-4 bg-blue-500 rounded-lg shadow gap-7 md:flex">
        <img src="https://visage.surgeplay.com/full/512/<?= htmlspecialchars($_SESSION['username']); ?>" alt="User Avatar" class="mb-4 rounded-full h-60">
        <div>
            <div class="flex items-center mt-5 space-x-4">
                <div>
                    <div class="text-4xl font-bold text-slate-900"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                    <div class="text-black ">UUID: <?= htmlspecialchars($_SESSION['uuid']); ?></div>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-5">
                <div class="py-4 text-black rounded">
                    <div class="text-sm">Total Playtime</div>
                    <div class="text-lg font-bold"><?= htmlspecialchars(ticksToReadable($playTime)); ?></div>

                </div>
                <div class="py-4 text-black rounded">
                    <div class="text-sm">Joined</div>
                    <div class="text-lg font-bold" data-time="<?= htmlspecialchars($firstJoined); ?>" data-format='{"year":"numeric","month":"long","day":"numeric"}'>Loading...</div>
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics and Skin -->
    <div class="grid gap-10 px-5 md:grid-cols-2">
        <div class="flex flex-col">
            <div class="flex items-center">
                <img src="https://cdn-icons-png.flaticon.com/128/5528/5528021.png" alt="Statistics Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
                <p class="text-2xl font-bold text-white">Statistics</p>
            </div>
            <table class="w-full mt-2 text-sm text-gray-200">
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-200">Advancements</td>
                <td class="py-1 text-right">
                    <?php
                    $totalAdvancements = 122;
                    $progress = is_numeric($advancement) && $totalAdvancements > 0
                        ? round(($advancement / $totalAdvancements) * 100)
                        : 0;
                    ?>
                    <?= htmlspecialchars($advancement); ?> / <?= $totalAdvancements; ?> (<?= $progress; ?>%)
                </td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Blocks Broken</td>
                <td class="py-1 text-right"><?= htmlspecialchars($blockMined); ?></td>
                </tr>
                        <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Blocks Placed</td>
                <td class="py-1 text-right"><?= htmlspecialchars($blockPlaced); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Damage Absorbed</td>
                <td class="py-1 text-right"><?= htmlspecialchars($damageAbsorbed); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Damage Dealt</td>
                <td class="py-1 text-right"><?= htmlspecialchars($damageDealt); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Damage Taken</td>
                <td class="py-1 text-right"><?= htmlspecialchars($damageTaken); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Deaths</td>
                <td class="py-1 text-right"><?= htmlspecialchars($deaths); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Distance Traveled</td>
                <td class="py-1 text-right"><?= htmlspecialchars($distanceTraveled); ?> Blocks</td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Last Seen</td>
                <td class="py-1 text-right" data-time='<?= htmlspecialchars($lastSeen); ?>' data-format='{"year":"numeric","month":"long","day":"numeric" ,"hour":"2-digit","minute":"2-digit"}'></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Level</td>
                <td class="py-1 text-right"><?= htmlspecialchars($level); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Mob Kills</td>
                <td class="py-1 text-right"><?= htmlspecialchars($mobKills); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Player Kills</td>
                <td class="py-1 text-right"><?= htmlspecialchars($playerKills); ?></td>
                </tr>
                <tr>
                <td class="py-1 pr-2 font-medium text-gray-300">Time Since Last Death</td>
                <td class="py-1 text-right"><?= htmlspecialchars(ticksToReadable($timeSinceDeath)); ?></td>
                </tr>
            </table>
        </div>
        <div class="flex flex-col">
            <div class="flex items-center">
                <img src="https://icons.veryicon.com/png/o/object/material-design-icons-1/minecraft-8.png" alt="Statistics Icon" class="w-6 h-6 mr-2" style="filter: invert(1);">
                <p class="text-2xl font-bold text-white">Skin</p>
            </div>
        </div>
    </div>
    <div class="flex flex-col px-5">
        <div class="flex items-center">
            <img src="https://cdn-icons-png.flaticon.com/128/18650/18650881.png" alt="Death Logs Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
            <p class="text-2xl font-bold text-white">Death Logs</p>
        </div>
        <p class="mb-5 text-sm italic text-gray-300">Your last 5 deaths will be shown here.</p>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div onclick="window.location.href='bluemap.php?x=<?= htmlspecialchars($row['x']); ?>&z=<?= htmlspecialchars($row['z']); ?>&zoom=100'" class="px-4 py-3 mb-2 transition duration-200 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 hover:shadow-lg">
                    <p class="font-bold text-white"><?= htmlspecialchars($row['cause']); ?></p>
                    <p class="text-sm text-gray-400"><?= htmlspecialchars($row['x']) ?>, <?= htmlspecialchars($row['y']) ?>, <?= htmlspecialchars($row['z']) ?></p>
                    <p class="text-sm text-gray-400" 
                        data-time="<?= htmlspecialchars($row['timestamp']); ?>" 
                        data-format='{"year":"numeric","month":"long","day":"numeric" ,"hour":"2-digit","minute":"2-digit"}'>
                        Loading time...
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
        <div class="flex items-center justify-center px-4 py-3 mb-2 bg-gray-800 rounded-lg">
                <p class="text-white">Congrats. You haven't died yet.</p>
        </div>
        <?php endif; ?>
    </div> 
    <div class="flex flex-col px-5">
        <div class="flex items-center">
            <img src="https://cdn-icons-png.flaticon.com/128/786/786346.png" alt="Death Logs Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
            <p class="text-2xl font-bold text-white">Armor</p>
        </div>
    </div> 
</div>
<script src="script/timeFunctions.js"></script>
<script>
    document.querySelectorAll('[data-time]').forEach(el => {
        const utcDateStr = el.getAttribute('data-time');
        let formatOptions = {};

        try {
            const formatData = el.getAttribute('data-format');
            if (formatData) {
                formatOptions = JSON.parse(formatData);
            }
        } catch(e) {
        }

        el.textContent = convertToLocalTime(utcDateStr, formatOptions);
    });
</script>