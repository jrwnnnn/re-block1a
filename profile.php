<?php
    require_once 'includes/security-headers.php';
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }

    $uuid = $_GET['player'] ?? $_SESSION['uuid'];
    
    $sql = "SELECT uuid FROM users WHERE uuid = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $uuid);
    $stmt->execute();
    $result = $stmt->get_result();
    if (!$result || $result->num_rows === 0) {
        header('Location: 404.php?error=player_not_found');
    }
    $stmt->close();
    
    $tab = $_GET['tab'] ?? 'statistics';
    if (!in_array($tab, ['statistics', 'playpass', 'settings'])) {
        $tab = 'statistics';
        header('Location: profile.php?tab=statistics');
    } else if ($tab === 'settings') {
        $uuid = $_SESSION['uuid'];
    }

    $sql = "SELECT username, uuid, skin, firstJoined, lastSeen FROM player_data WHERE uuid = '$uuid'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    extract($row);

    $sql = "SELECT playTime FROM player_statistics WHERE uuid = '$uuid'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    extract($row);

    $firstJoined = $firstJoined ? date('F j, Y', strtotime($firstJoined)) : 'N/A';
    $lastSeen = $lastSeen ? date('F j, Y, g:i A', strtotime($lastSeen)) : 'N/A';

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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Profile - Block1A">
    <meta property="og:image" content="https://block1a.onrender.com/assets/season2-banner.jpg">
    <meta property="og:url" content="https://block1a.onrender.com/profile.php">
    <meta property="og:site_name" content="Block1A">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link href="src/output.css" rel="stylesheet">
    <title>Block1A - Profile</title>
</head>
    <body class="flex flex-col min-h-screen">
        <?php require 'includes/navigation.php'; ?>
        <!-- Top Card -->
        <div id="topCard" class="flex flex-col px-5 !bg-[url(../assets/topcard-blue.jpg)] bg-no-repeat shadow md:bg-cover bg-bottom-right p-7 ace-y-4 md:flex-row md:px-30 gap-5 md:gap-10">
            <img src="https://starlightskins.lunareclipse.studio/render/ultimate/steve/full?skinUrl=<?= $skin ?>" alt="User Avatar" class="w-35 md:w-45">
            <div class="flex flex-col justify-between flex-grow">
                <div class="flex flex-col items-start justify-between space-x-4 md:flex-row">
                    <div class="text-white topCardText">
                        <p class="mb-2 text-4xl font-bold"><?= $username ?></p>
                        <p class="hidden md:block"><b>UUID:</b> <?= $uuid ?></p>
                        <p class="block md:hidden"><b>Status: </b> <span id="mdStatusText">Loading...</span></p>
                        <p><b>Last Seen:</b> <span class="py-1 text-right"><?php $lastSeen ?></span></p>
                    </div>
                    <div class="flex justify-end gap-5 mt-5 md:mt-0">
                        <img src="https://cdn-icons-png.flaticon.com/128/6853/6853826.png" alt="Playpass Icon" class="w-6 h-6 mb-1 cursor-pointer topCardIcon" style="filter: invert(1);" onclick="window.location.href='profile.php?tab=playpass'" />
                        <?php if (isset($_SESSION['uuid']) && $uuid === $_SESSION['uuid']): ?>
                            <img src="https://cdn-icons-png.flaticon.com/128/503/503849.png" alt="Settings Icon" class="w-6 h-6 mb-1 cursor-pointer topCardIcon" style="filter: invert(1);" onclick="window.location.href='profile.php?tab=settings'" />
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
                        <p class="text-lg font-bold"><?= htmlspecialchars(ticksToReadable($playTime)); ?></p>
                    </div>
                    <div class="text-white topCardText">
                        <p class="text-sm">Joined</p>
                        <p class="text-lg font-bold"><?php echo htmlspecialchars($firstJoined); ?></p>
                    </div>
                </div>
            </div>
        </div>
        <section class="bg-[#2D3748] py-10 md:px-30 px-5 flex flex-col space-y-6">
            <div class="flex flex-col flex-grow w-full min-h-screen mt-5 text-white md:px-20">
                <?php require 'includes/profile/' . $tab . '.php'; ?>
            </div>
        </section>
    </body>
<script src="script/timeFunctions.js"></script>
<script>
    const uuid = "<?php echo $uuid; ?>"; 
    function updateStatus() {
        fetch(`functions/activity.php?uuid=${uuid}`)
            .then(res => res.json())
            .then(data => {
                const card = document.getElementById("topCard");
                const status = document.getElementById("statusText");
                const mdStatus = document.getElementById("mdStatusText");

                // Animate background color transition
                card.style.transition = "background-image 0.5s";
                if (data.online) {
                    card.classList.remove("!bg-[url(../assets/topcard-blue.jpg)]");
                    card.classList.add("!bg-[url(../assets/topcard-green.jpg)]");
                    status.textContent = "Online";
                    mdStatus.textContent = "Online";
                } else {
                    card.classList.remove("!bg-[url(../assets/topcard-green.jpg)]");
                    card.classList.add("!bg-[url(../assets/topcard-blue.jpg)]");
                    status.textContent = "Offline";
                    mdStatus.textContent = "Offline";
                }

                document.querySelectorAll(".topCardText").forEach(text => {
                    if (data.online) {
                        text.classList.remove("!text-white");
                        text.classList.add("!text-black");
                    } else {
                        text.classList.remove("!text-black");
                        text.classList.add("!text-white");
                    }
                });
                document.querySelectorAll(".topCardIcon").forEach(icon => {
                    if (data.online) {
                        icon.style.setProperty("filter", "invert(0)", "important");
                    } else {
                        icon.style.setProperty("filter", "invert(1)", "important");
                    }
                });
            });
    }

    updateStatus();
    setInterval(updateStatus, 5000);
</script>
</html>