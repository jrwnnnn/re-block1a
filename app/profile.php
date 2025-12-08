<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/core/security-headers.php';
require_once __DIR__ . '/core/session.php';
require_once __DIR__ . '/core/RBAC.php';
RBAC ('user', 'login.php');
require_once __DIR__ . '/core/database.php';
require_once __DIR__ . '/helpers/ticksToReadable.php';

$uuid = $_GET['player'] ?? $_SESSION['uuid'];

$user = query("SELECT * FROM users WHERE uuid = ?", [$uuid], "s");
if (!$user) {
    header('Location: 404.php?error=notfound');
    exit();
}
$playerData = query("SELECT * FROM player_data WHERE uuid = ?", [$uuid], "s");
$statistics = query("SELECT * FROM player_statistics WHERE uuid = ?", [$uuid], "s");
$badges = query("SELECT badgeId, dateRecieved FROM badges WHERE uuid = ? ORDER BY badgeId ASC LIMIT 100", [$uuid], "s");
$deathLog = query("SELECT * FROM death_log WHERE uuid = ? ORDER BY id DESC LIMIT 10", [$uuid], "s");
$playpass = query("SELECT status FROM playpass WHERE uuid = ?", [$uuid], "s");
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
    <body class="flex flex-col min-h-screen justify-between">
        <?php require 'views/partials/navigation.php'; ?>
        <?php if ($user['isPrivate'] == 0 || ($uuid === ($_SESSION['uuid'] ?? ''))): ?>
            <main class="flex flex-col flex-stretch">
                <section class="relative flex flex-col px-5 md:flex-row md:gap-10 md:px-50">
                    <div id="profileBanner" class="absolute inset-0 bg-cover bg-center md:brightness-60" style="background-image: url('<?= $user['bannerUrl'] ?? 'public/assets/images/backgrounds/s2-background.webp' ?>');"></div>
                    <div class="md:hidden absolute inset-0 bg-gradient-to-b from-transparent to-[#2D3748] z-5"></div>
                    <div class="flex mt-10">
                        <img src="https://starlightskins.lunareclipse.studio/render/ultimate/steve/bust?skinUrl=<?= sanitize($playerData['skin']) ?>" alt="Player Model" class="z-10 w-auto md:h-60 h-50">
                    </div>
                    <div class="z-10 flex flex-col justify-between flex-grow md:py-5 py-2">
                        <div class="flex flex-col items-start justify-between space-x-4 md:flex-row">
                            <div class="text-white">
                                <p class="mb-2 text-4xl font-bold"><?= sanitize($playerData['username']) ?></p>
                                <p class="line-clamp-1"><b>UUID:</b> <?= $playerData['uuid'] ?></p>
                                <p><b>Last Seen:</b> <span class="py-1 text-right"><?= $playerData['status'] == 0 ? '<script>document.write(localTime("' . date('c', strtotime($playerData['lastSeen'])) . '", "MMMM D, YYYY, hh:mm A"));</script>' : '-' ?></span></p>
                            </div>
                            <div class="flex justify-end gap-5 mt-5 md:mt-0">
                                <?php if (isset($_SESSION['uuid']) && $uuid === $_SESSION['uuid']): ?>
                                    <img src="https://cdn-icons-png.flaticon.com/128/503/503849.png" alt="Settings Icon" class="w-6 h-6 mb-1 cursor-pointer" style="filter: invert(1);" onclick="window.location.href='settings.php'" />
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
                                    <span class="w-3 h-3 <?= $playerData['status'] == 0 ? 'bg-gray-500' : 'bg-green-500' ?> rounded-full"></span>
                                    <span><?= $playerData['status'] == 0 ? 'Offline' : 'Online' ?></span>
                                </p>
                            </div>
                            <div class="text-white">
                                <p class="text-sm truncate">Total Playtime</p>
                                <p class="text-lg font-bold truncate"><?= ticksToReadable($statistics['playTime']); ?></p>
                            </div>
                            <div class="text-white">
                                <p class="text-sm truncate">First Joined</p>
                                <p class="text-lg font-bold truncate"><script>document.write(localTime("<?= date('c', strtotime($playerData['firstJoined'])) ?>", "MMMM D, YYYY"));</script></p>
                            </div>
                        </div>
                    </div>
                </section>
                <section class="bg-[#2b3443] py-10 md:px-60 px-5 flex flex-col space-y-6">
                    <div class="flex flex-col space-y-5 md:space-y-10">
                        <?php if (!empty($badges)): ?>
                            <div class="flex flex-col">
                                <hr class="flex-grow h-px border-0 bg-gradient-to-r from-transparent via-gray-600 to-transparent">
                                <div class="flex justify-center flex-wrap gap-5 my-7">
                                    <?php foreach ($badges as $badge): ?>
                                        <div class="relative group flex justify-center">
                                            <img src="public/assets/content/badges/<?= $badge['badgeId'] ?>.png" alt="<?= $badge['badgeId'] ?>" class="h-15 w-auto cursor-pointer shadow-lg">
                                            <div class="absolute bottom-full mb-3 hidden group-hover:block w-max px-3 py-1.5 bg-gray-200 rounded-md shadow-xl z-50">
                                                <p class="text-black text-sm font-medium"><?= 
                                                    $badgeName = match ($badge['badgeId']) {
                                                        's1' => 'Season 1 Badge',
                                                        's1-gold' => 'Season 1: Gold Badge',
                                                        's2' => 'Season 2 Badge',
                                                        's2-gold' => 'Season 2: Gold Badge',
                                                        default => 'Unknown Badge',
                                                    };
                                                    $badgeName ?>
                                                </p>
                                                <p class="text-gray-800 text-xs"><?= isset($badge['dateRecieved']) ? "Awarded on: " . date('F j, Y', strtotime($badge['dateRecieved'])) : "" ?></p>
                                            </div>   
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <hr class="flex-grow h-px border-0 bg-gradient-to-r from-transparent via-gray-600 to-transparent">
                            </div>
                        <?php endif; ?>
                        <div class="grid gap-10 md:grid-cols-2">
                            <div class="flex flex-col">
                                <div class="flex items-center mb-2">
                                    <img src="https://cdn-icons-png.flaticon.com/128/876/876171.png" class="w-5 h-5 mr-2" style="filter: invert(1);">
                                    <p class="text-2xl font-bold text-white">Statistics</p>
                                </div>
                                <table class="w-full text-sm text-gray-200">
                                    <tr>
                                    <td class="py-1 pr-2 font-medium text-gray-300 md:hidden">Status</td>
                                    <td class="py-1 text-right md:hidden <?= $playerData['status'] == 1 ? 'text-green-400' : ''?>"><?= $playerData['status'] == 0 ? 'Offline' : 'Online' ?></td>
                                    </tr>
                                    <tr>
                                    <td class="py-1 pr-2 font-medium text-gray-300">Blocks Broken</td>
                                    <td class="py-1 text-right"><?= number_format($statistics['blockMined'])?></td>
                                    </tr>
                                    <tr>
                                    <td class="py-1 pr-2 font-medium text-gray-300">Blocks Placed</td>
                                    <td class="py-1 text-right"><?= number_format($statistics['blockPlaced'])?></td>
                                    </tr>
                                    <tr>
                                    <td class="py-1 pr-2 font-medium text-gray-300">Deaths</td>
                                    <td class="py-1 text-right"><?= number_format($statistics['deaths'])?></td>
                                    </tr>
                                    <tr>
                                    <td class="py-1 pr-2 font-medium text-gray-300">Distance Traveled</td>
                                    <td class="py-1 text-right"><?= number_format($statistics['distanceTraveled']) ?> Blocks</td>
                                    </tr>
                                    <tr>
                                    <td class="py-1 pr-2 font-medium text-gray-300">Level</td>
                                    <td class="py-1 text-right"><?= number_format($statistics['level']); ?></td>
                                    </tr>
                                    <tr>
                                    <td class="py-1 pr-2 font-medium text-gray-300 md:hidden">First Joined</td>
                                    <td class="py-1 text-right md:hidden"><script>document.write(localTime("<?= date('c', strtotime($playerData['firstJoined'] ?? '')) ?>", "MMMM D, YYYY"))</script></td>
                                    </tr>
                                    <tr>
                                    <td class="py-1 pr-2 font-medium text-gray-300">Total Playtime</td>
                                    <td class="py-1 text-right"><?= ticksToReadable($statistics['playTime']); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="flex flex-col">
                                <div class="flex items-center mb-2">
                                    <img src="https://cdn-icons-png.flaticon.com/128/10182/10182637.png" class="w-5 h-5 mr-2" style="filter: invert(1);">
                                    <p class="text-2xl font-bold text-white">Combat</p>
                                </div>
                                <table class="w-full text-sm text-gray-200">
                                    <tr>
                                        <td class="py-1 pr-2 font-medium text-gray-300">Mob Kills</td>
                                        <td class="py-1 text-right"><?= $statistics['mobKills']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 pr-2 font-medium text-gray-300">Player Kills</td>
                                        <td class="py-1 text-right"><?= $statistics['playerKills']; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 pr-2 font-medium text-gray-300">Damage Absorbed</td>
                                        <td class="py-1 text-right"><?= $statistics['damageAbsorbed'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 pr-2 font-medium text-gray-300">Damage Dealt</td>
                                        <td class="py-1 text-right"><?= $statistics['damageDealt'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 pr-2 font-medium text-gray-300">Damage Resisted</td>
                                        <td class="py-1 text-right"><?= $statistics['damageResisted'] ?></td>
                                    </tr>
                                    <tr>
                                        <td class="py-1 pr-2 font-medium text-gray-300">Damage Taken</td>
                                        <td class="py-1 text-right"><?= $statistics['damageTaken'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                        <div class="flex flex-col">
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <img src="https://cdn-icons-png.flaticon.com/128/18650/18650881.png" class="w-5 h-5 mr-2" style="filter: invert(1);">
                                    <p class="text-2xl font-bold text-white">Death Log</p>
                                </div>
                                <p class="ml-2 text-sm text-gray-400"><?= $_SESSION['uuid'] === $uuid && $user['hideDeathLog'] == 1 ? 'You have set your deathlog to private.' : '' ?></p>
                            </div>
                            <?php if (!empty($deathLog) && ($uuid === ($_SESSION['uuid'] ?? '') || $user['hideDeathLog'] == 0)): ?>
                                <?php foreach ($deathLog as $death): ?>
                                    <div onclick="window.location.href='bluemap.php?x=<?= $death['x'] ?>&z=<?= $death['z']; ?>&world=<?= $death['world'] ?>&zoom=50'" class="px-4 py-3 mb-2 transition duration-200 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-700 hover:shadow-lg">
                                        <p class="font-bold text-white">
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
                                <div class="px-4 py-3 mb-2 bg-gray-700 rounded-lg">
                                    <p class="text-gray-400 text-center"><?= ($user['hideDeathLog'] && $uuid !== ($_SESSION['uuid'] ?? '')) ? 'This user\'s death log is private.' : 'Recent deaths will show here.' ?></p>
                                </div>
                            <?php endif; ?>
                            </div>
                        </div>
                    </section>
                </section>
            </main>
            <?php include 'views/partials/footer.php'; ?>
        <?php else: ?>
            <div class="bg-[#2b3443] flex flex-col flex-grow items-center justify-center px-5 text-center">
                <img src="https://cdn-icons-png.flaticon.com/128/565/565547.png" alt="Private Profile Icon" class="w-20 h-20 mb-5" style="filter: invert(0.6);">
                <p class="mb-2 text-2xl font-bold text-white">This profile is private.</p>
                <p class="text-gray-400">The user has chosen to keep their profile private.</p>
            </div>
        <?php endif; ?>
    </body>
</html>