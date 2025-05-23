<?php
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
    
    $uuid = $_SESSION['uuid'];

    $sql = "SELECT cause, x, y, z, timestamp FROM death_log WHERE uuid = ? ORDER BY id DESC LIMIT 5";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $result = $stmt->get_result();

    $sql = "SELECT stat_death FROM players WHERE uuid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $stmt->bind_result($deathCount);
    $stmt->fetch();
    $stmt->close();

?>


<div class="space-y-3 md:pr-100">
    <div class="hidden p-6 space-y-4 bg-blue-500 rounded-lg shadow gap-7 md:flex">
        <img src="https://visage.surgeplay.com/full/512/keiNoRead" alt="User Avatar" class="mb-4 rounded-full h-60">
        <div>
            <div class="flex items-center mt-5 space-x-4">
                <div>
                    <div class="text-4xl font-bold text-slate-900"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                    <div class="text-black ">UUID: <?php echo htmlspecialchars($_SESSION['uuid']); ?></div>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                <div class="py-4 text-black rounded">
                    <div class="text-sm">Total Playtime</div>
                    <div class="text-lg font-bold">214h 38m</div>
                </div>
                <div class="py-4 text-black rounded">
                    <div class="text-sm">Playtime This Week</div>
                    <div class="text-lg font-bold">6h 42m</div>
                </div>
            </div>
        </div>
    </div>
    <div class="p-4 space-y-5 rounded-lg">
        <div class="flex flex-col">
            <div class="flex items-center">
                <img src="https://cdn-icons-png.flaticon.com/128/5528/5528021.png" alt="Statistics Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
                <p class="text-2xl font-bold text-white">Statistics</p>
            </div>
            <table class="w-full mt-2 text-sm text-gray-200">
                <tbody>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Advancements</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Player Kills</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Deaths</td>
                        <td class="py-1 text-right"><?= $deathCount ?? 0 ?></td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Time Since Last Death</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Experience Points</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Distance Walked</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Distance Sprinted</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Distance Flown</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Animals Bred</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Chests Opened</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Items Crafted</td>
                        <td class="py-1 text-right">BUILDING</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="flex flex-col">
            <div class="flex items-center">
                <img src="https://cdn-icons-png.flaticon.com/128/18650/18650881.png" alt="Death Logs Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
                <p class="text-2xl font-bold text-white">Death Logs</p>
            </div>
            <p class="mb-3 text-sm italic text-gray-300">Your last 5 deaths will be shown here.</p>

            <?php while ($row = $result->fetch_assoc()): ?>
                <div onclick="window.open('http://118.127.8.162:25789/#world:<?= $row['x'] ?>:0:<?= $row['z'] ?>:1500:0:0:0:0:perspective', '_blank')" class="px-4 py-3 mb-2 transition duration-200 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 hover:shadow-lg">
                    <p class="font-bold text-white"><?= htmlspecialchars($row['cause']) ?></p>
                    <p class="text-sm text-gray-400">
                        Coordinates: X: <?= $row['x'] ?>, Y: <?= $row['y'] ?>, Z: <?= $row['z'] ?>
                    </p>
                    <p class="text-sm text-gray-400"><?= htmlspecialchars($row['timestamp']) ?></p>
                </div>
            <?php endwhile; ?>
        </div>   
    </div>
</div>
