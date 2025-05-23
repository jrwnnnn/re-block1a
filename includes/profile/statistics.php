<?php
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';
    
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
    
    $uuid = $_SESSION['uuid'];

?>

<div class="space-y-10 md:pr-120">
    <div class="flex items-center justify-center gap-2 px-4 py-2 mb-6 bg-red-600 rounded-lg">
        <img src="https://cdn-icons-png.flaticon.com/128/9291/9291673.png" alt="Development Icon" class="inline w-5 mr-2 align-middle md:w-4" style="filter: invert(1);">
        <p class="text-sm font-semibold text-white"> This page is currently in Development. Features may change or break.</p>
    </div>
    <div class="hidden p-6 space-y-4 bg-blue-500 rounded-lg shadow gap-7 md:flex">
        <img src="https://visage.surgeplay.com/full/512/<?= htmlspecialchars($_SESSION['username']); ?>" alt="User Avatar" class="mb-4 rounded-full h-60">
        <div>
            <div class="flex items-center mt-5 space-x-4">
                <div>
                    <div class="text-4xl font-bold text-slate-900"><?php echo htmlspecialchars($_SESSION['username']); ?></div>
                    <div class="text-black ">UUID: <?php echo htmlspecialchars($_SESSION['uuid']); ?></div>
                </div>
            </div>
            <div class="flex">
                <div class="py-4 text-black rounded">
                    <div class="text-sm">Total Playtime</div>
                    <div class="text-lg font-bold" id="stat-playtime">Loading...</div>
                </div>
            </div>
        </div>
    </div>
    <div class="rounded-lg space-y-7 p">
        <div class="flex flex-col">
            <div class="flex items-center">
                <img src="https://cdn-icons-png.flaticon.com/128/5528/5528021.png" alt="Statistics Icon" class="w-5 h-5 mr-2" style="filter: invert(1);">
                <p class="text-2xl font-bold text-white">Statistics</p>
            </div>
            <table class="w-full mt-2 text-sm text-gray-200">
                <tbody>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Advancements</td>
                        <td class="py-1 text-right" id="stat-adv">Loading...</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Player Kills</td>
                        <td class="py-1 text-right">-</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Deaths</td>
                        <td class="py-1 text-right" id="stat-deaths">Loading...</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Time Since Last Death</td>
                        <td class="py-1 text-right" id="stat-ticks">Loading...</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Level</td>
                        <td class="py-1 text-right" id="stat-level">Loading...</td>
                    </tr>
                    <tr>
                        <td class="py-1 pr-2 font-medium text-gray-300">Distance Traveled</td>
                        <td class="py-1 text-right" id="stat-distance">Loading...</td>
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
            
            <div id="death-logs"></div>
        </div>   
    </div>
</div>
<script>
function formatTicksReadable(ticks) {
    const seconds = Math.floor(ticks / 20);
    const days = Math.floor(seconds / 86400);
    const hours = Math.floor((seconds % 86400) / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);

    if (days > 0) return `${days}d ${hours}h`;
    if (hours > 0) return `${hours}h ${minutes}m`;
    return `${minutes}m`;
}

function updatePlayerStats() {
    fetch("functions/statistics.php")
        .then(res => res.json())
        .then(data => {
            if (data.error) return;

            const advCount = data.stats.adv;
            const advTotal = 122;
            const advPercent = Math.round((advCount / advTotal) * 100);
            document.querySelector("#stat-adv").textContent = `${advCount} / ${advTotal} (${advPercent}%)`;
            document.querySelector("#stat-deaths").textContent = data.stats.deaths;
            document.querySelector("#stat-ticks").textContent = formatTicksReadable(data.stats.ticks);
            document.querySelector("#stat-level").textContent = data.stats.level;
            document.querySelector("#stat-distance").textContent = `${data.stats.distance} Blocks`;
            document.querySelector("#stat-playtime").textContent = formatTicksReadable(data.stats.playtime);

            const logWrapper = document.querySelector("#death-logs");
            logWrapper.innerHTML = "";
            data.deathLogs.forEach(row => {
                const div = document.createElement("div");
                div.className = "px-4 py-3 mb-2 transition duration-200 bg-gray-800 rounded-lg cursor-pointer hover:bg-gray-700 hover:shadow-lg";
                div.onclick = () => window.open(`http://118.127.8.162:25789/#world:${row.x}:0:${row.z}:1500:0:0:0:0:perspective`, "_blank");
                div.innerHTML = `
                    <p class="font-bold text-white">${row.cause}</p>
                    <p class="text-sm text-gray-400">Coordinates: X: ${row.x}, Y: ${row.y}, Z: ${row.z}</p>
                    <p class="text-sm text-gray-400" data-time="${row.timestamp}">Loading time...</p>
                `;
                logWrapper.appendChild(div);
            });
            document.querySelectorAll('[data-time]').forEach(el => {
                const utcTime = el.dataset.time + ' UTC';
                const date = new Date(utcTime);

                if (!isNaN(date)) {
                    const now = new Date();
                    const isToday = date.toDateString() === now.toDateString();
                    
                    const yesterday = new Date();
                    yesterday.setDate(yesterday.getDate() - 1);
                    const isYesterday = date.toDateString() === yesterday.toDateString();

                    const timePart = date.toLocaleTimeString([], { hour: 'numeric', minute: '2-digit' });

                    if (isToday) {
                        el.innerText = `Today at ${timePart}`;
                    } else if (isYesterday) {
                        el.innerText = `Yesterday at ${timePart}`;
                    } else {
                        const datePart = date.toLocaleDateString(undefined, { year: 'numeric', month: 'short', day: 'numeric' });
                        el.innerText = `${datePart} at ${timePart}`;
                    }
                } else {
                    el.innerText = "Never";
                }
            });
        });
}

updatePlayerStats();
setInterval(updatePlayerStats, 10000); // refresh every 10s
</script>
