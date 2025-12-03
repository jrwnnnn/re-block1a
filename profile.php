<?php
// CODEX RATING
// Efficiency: 9/10
// Security: 9/10
// Readability: 9/10

require_once 'includes/security-headers.php';
require_once 'includes/session-init.php';
require_once 'functions/connect.php';
require_once 'includes/RBAC.php';
RBAC ('user', 'auth/login.php');

$uuid = $_GET['player'] ?? $_SESSION['uuid'];

$playerData = query("SELECT username, uuid, skin, firstJoined, lastSeen FROM player_data WHERE uuid = ?", [$uuid], "s");
if (!$playerData) {
    header('Location: 404.php?error=player_not_found');
    exit();
}
$stmt = query("SELECT playTime FROM player_statistics WHERE uuid = ?", [$uuid], "s");

$tab = $_GET['tab'] ?? 'statistics';
if (!in_array($tab, ['statistics', 'playpass', 'settings'])) {
    $tab = 'statistics';
    header('Location: profile.php?tab=statistics');
} else if ($tab === 'settings') {
    $uuid = $_SESSION['uuid'];
}

$playerData['firstJoined'] = $playerData['firstJoined'] ? date('F j, Y', strtotime($playerData['firstJoined'])) : 'N/A';
$playerData['lastSeen'] = $playerData['lastSeen'] ? date('F j, Y, g:i A', strtotime($playerData['lastSeen'])) : 'N/A';

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

<!doctype html>
<html>
<head>
    <?php
      $title = sanitize($playerData['username']) . "'s Profile - Block1A";
      include 'includes/meta.php';
    ?>
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link href="src/output.css" rel="stylesheet">
    <title><?= sanitize($playerData['username']) . "'s Profile - Block1A" ?></title>
</head>
    <body class="flex flex-col min-h-screen">
        <?php require 'includes/navigation.php'; ?>
        <div class="relative flex flex-col md:flex-row md:gap-10 px-5 md:px-50">
            <div class="absolute inset-0 !bg-[url('https://cdna.artstation.com/p/assets/images/images/072/797/676/original/arara-vilano-lowrhen-country.gif?1708231362')] bg-cover bg-center brightness-75"></div>
            <div class="flex mt-10">
                <img src="https://starlightskins.lunareclipse.studio/render/ultimate/steve/bust?skinUrl=<?= sanitize($playerData['skin']) ?>" alt="Player Model" class="w-auto h-60 z-10 shadow-lg">
            </div>
            <div class="flex flex-col justify-between flex-grow py-5 z-10">
                <div class="flex flex-col items-start justify-between space-x-4 md:flex-row">
                    <div class="text-white topCardText">
                        <p class="mb-2 text-4xl font-bold"><?= sanitize($playerData['username']) ?></p>
                        <p class="hidden md:block"><b>UUID:</b> <?= $playerData['uuid'] ?></p>
                        <p class="block md:hidden"><b>Status: </b> <span id="activityStatus">Loading...</span></p>
                        <p><b>Last Seen:</b> <span class="py-1 text-right"><?= sanitize($playerData['lastSeen']) ?></span></p>
                    </div>
                    <div class="flex justify-end gap-5 mt-5 md:mt-0">
                        <!-- <img src="assets/level-up.png" alt="Playpass Icon" class="h-6 mb-1 cursor-pointer topCardIcon" style="filter: invert(1);" onclick="window.location.href='profile.php?tab=playpass'" /> -->
                        <?php if (isset($_SESSION['uuid']) && $uuid === $_SESSION['uuid']): ?>
                            <img src="https://cdn-icons-png.flaticon.com/128/503/503849.png" alt="Settings Icon" class="h-6 mb-1 cursor-pointer topCardIcon" style="filter: invert(1);" onclick="window.location.href='profile.php?tab=settings'" />
                            <form action="functions/logout.php" method="POST">
                                <button type="submit" style="background: none; border: none; padding: 0;">
                                    <img src="https://cdn-icons-png.flaticon.com/128/4400/4400629.png" alt="Logout Icon" class="w-6 h-6 mb-1 cursor-pointer topCardIcon" style="filter: invert(1);" />
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex-shrink hidden grid-cols-3 mr-10 md:grid gap-15">
                    <div class="text-white topCardText">
                        <p class="text-sm">Status</p>
                        <p class="flex items-center gap-2 text-lg font-bold" id="statusText">Loading...</p>
                    </div>
                    <div class="text-white topCardText">
                        <p class="text-sm">Total Playtime</p>
                        <p class="text-lg font-bold"><?= sanitize(ticksToReadable($stmt['playTime'])); ?></p>
                    </div>
                    <div class="text-white topCardText">
                        <p class="text-sm">Joined</p>
                        <p class="text-lg font-bold"><?= sanitize($playerData['firstJoined']); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <section class="bg-[#2D3748] py-10 md:px-30 px-5 flex flex-col space-y-6">
            <div class="flex flex-col flex-grow w-full min-h-screen mt-5 text-white md:px-20">
                <?php require 'profile/' . $tab . '.php'; ?>
            </div>
        </section>
        <?php include 'includes/footer.php'; ?>
    </body>
<script>
    const uuid = "<?= $playerData['uuid']; ?>"; 
    function updateStatus() {
        fetch(`functions/activity.php?uuid=${uuid}`)
            .then(res => res.json())
            .then(data => {
                const status = document.getElementById("activityStatus");

                if (data.online) {
                    status.textContent = "Online";
                } else {
                    status.textContent = "Offline";
                }
            });
    }

    updateStatus();
    setInterval(updateStatus, 5000);
</script>
</html>