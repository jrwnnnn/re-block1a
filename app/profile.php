<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/core/security-headers.php';
require_once __DIR__ . '/core/session.php';
require_once __DIR__ . '/core/RBAC.php';
RBAC ('user', 'login.php');
require_once __DIR__ . '/core/database.php';
require_once __DIR__ . '/helpers/ticksToReadable.php';

$uuid = $_GET['player'] ?? $_SESSION['uuid'];

$playerData = query("SELECT username, uuid, skin, firstJoined, lastSeen FROM player_data WHERE uuid = ?", [$uuid], "s");
$playTime = query("SELECT playTime FROM player_statistics WHERE uuid = ?", [$uuid], "s");
$status = query("SELECT status FROM player_data WHERE uuid = ?", [$uuid], "s");

$tab = $_GET['tab'] ?? 'statistics';
if (!in_array($tab, ['statistics', 'settings'])) {
    $tab = 'statistics';
    header('Location: profile.php?tab=statistics');
} else if ($tab === 'settings') {
    $uuid = $_SESSION['uuid'];
}
?> 
<!DOCTYPE html>
<html>
    <head>
        <?php
        $title = sanitize($playerData['username']) . "'s Profile - Block1A";
        include 'views/partials/meta.php';
        ?>
        <link rel="icon" href="public/assets/icons/favicon.ico" type="image/x-icon">
        <link href="public/css/output.css" rel="stylesheet">
        <title><?= sanitize($playerData['username']) . "'s Profile - Block1A" ?></title>
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
        <script src="public/js/dateUtils.js"></script>
    </head>
    <body class="flex flex-col min-h-screen">
        <?php require 'views/partials/navigation.php'; ?>
        <div class="relative flex flex-col px-5 md:flex-row md:gap-10 md:px-50">
            <div class="absolute inset-0 bg-gray-500 bg-cover bg-center md:brightness-70"></div> <!-- !bg-[url('../../public/assets/images/ui/default-player-banner.jpg')] -->
            <div class="md:hidden absolute inset-0 bg-gradient-to-b from-transparent to-[#2D3748] z-5"></div>
            <div class="flex mt-10">
                <img src="https://starlightskins.lunareclipse.studio/render/ultimate/steve/bust?skinUrl=<?= sanitize($playerData['skin']) ?>" alt="Player Model" class="z-10 w-auto md:h-60 h-50">
            </div>
            <div class="z-10 flex flex-col justify-between flex-grow md:py-5 py-2">
                <div class="flex flex-col items-start justify-between space-x-4 md:flex-row">
                    <div class="text-white">
                        <p class="mb-2 text-4xl font-bold"><?= sanitize($playerData['username']) ?></p>
                        <p class="line-clamp-1"><b>UUID:</b> <?= $playerData['uuid'] ?></p>
                        <p><b>Last Seen:</b> <span class="py-1 text-right"><?= $status['status'] == 0 ? '<script>document.write(localTime("' . date('c', strtotime($playerData['lastSeen'])) . '", "MMMM D, YYYY, hh:mm A"));</script>' : '-' ?></span></p>
                    </div>
                    <div class="flex justify-end gap-5 mt-5 md:mt-0">
                        <?php if (isset($_SESSION['uuid']) && $uuid === $_SESSION['uuid']): ?>
                            <img src="https://cdn-icons-png.flaticon.com/128/503/503849.png" alt="Settings Icon" class="w-6 h-6 mb-1 cursor-pointer" style="filter: invert(1);" onclick="window.location.href='profile.php?tab=settings'" />
                            <form action="controllers/action-router.php?action=logout" method="POST">
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <button type="submit" style="background: none; border: none; padding: 0;">
                                    <img src="https://cdn-icons-png.flaticon.com/128/4400/4400629.png" alt="Logout Icon" class="w-6 h-6 mb-1 cursor-pointer" style="filter: invert(1);" />
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex-shrink hidden grid-cols-3 mr-10 md:grid gap-15">
                    <div class="text-white">
                        <p class="text-sm truncate">Status</p>
                        <p class="flex items-center gap-2 text-lg font-bold truncate">
                            <span class="w-3 h-3 <?= $status['status'] == 0 ? 'bg-gray-500' : 'bg-greeen-500' ?> rounded-full"></span>
                            <span><?= $status['status'] == 0 ? 'Offline' : 'Online' ?></span>
                        </p>
                    </div>
                    <div class="text-white">
                        <p class="text-sm truncate">Total Playtime</p>
                        <p class="text-lg font-bold truncate"><?= ticksToReadable($playTime['playTime']); ?></p>
                    </div>
                    <div class="text-white">
                        <p class="text-sm truncate">First Joined</p>
                        <p class="text-lg font-bold truncate"><script>document.write(localTime("<?= date('c', strtotime($playerData['firstJoined'])) ?>", "MMMM D, YYYY"));</script></p>
                    </div>
                </div>
            </div>
        </div>
        <section class="bg-[#2b3443] py-10 md:px-30 px-5 flex flex-col space-y-6">
            <div class="flex flex-col flex-grow w-full min-h-screen mt-5 text-white md:px-20">
                <?php require 'views/partials/profile/' . $tab . '.php'; ?>
            </div>
        </section>
        <?php include 'views/partials/footer.php'; ?>
    </body>
</html>