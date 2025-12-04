<?php
    require_once __DIR__ . '/config/config.php';
    require_once __DIR__ . '/app/core/security-headers.php';
    require_once __DIR__ . '/app/core/database.php';
    require_once __DIR__ . '/app/core/session.php';
    

    $spotlight = query("SELECT * FROM articles WHERE spotlight = 1 ORDER BY date_posted DESC LIMIT 1");
    $article = query("SELECT * FROM articles WHERE spotlight = 0 ORDER BY date_posted DESC LIMIT 3");
    $uniquePlayers = query("SELECT COUNT(DISTINCT uuid) AS total FROM player_data");

    if (isset($_SESSION['uuid'])) {
        $onlinePlayers = query("SELECT username, uuid, skin FROM player_data WHERE status = 1 ORDER BY username LIMIT 20");
        $offlinePlayers = query("SELECT username, uuid, skin FROM player_data WHERE status = 0 ORDER BY username LIMIT 20");
    }
?>


<!doctype html>
<html>
    <head>
        <?php
        $title = "Home - Block1A";
        $description = "The Official Minecraft Server of BSCS-1A! Available for Minecraft Java Edition players.";
        include 'app/views/partials/meta.php';
        ?>
        <link rel="icon" href="app/public/assets/icons/favicon.ico" type="image/x-icon">
        <link href="app/public/css/output.css" rel="stylesheet">
        <title>Block1A - Home</title>
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
        <script src="app/public/js/dateUtils.js"></script>
            <script>document.write(localTime("<?= date("c", strtotime($timestamp)) ?>", "MMMM D, YYYY hh:mm A"));</script>
    </head>
    <body>
        <?php require 'app/views/partials/navigation.php'; ?>
        <div class="flex items-center justify-center gap-2 px-4 py-2 bg-gradient-to-r from-red-600 via-red-500 to-red-400">
            <img src="https://cdn-icons-png.flaticon.com/128/9291/9291673.png" class="inline w-5 mr-2 align-middle md:w-4" style="filter: invert(1);">
            <p class="text-sm font-semibold text-white"> The server is currently closed. Re-opening soon!</p>
        </div>
        <!-- Main Splash Screen -->
        <?php if (!isset($_SESSION['uuid'])): ?>
            <section class="flex flex-col min-h-screen bg-center bg-no-repeat bg-cover" style="background-image: url('app/public/assets/images/backgrounds/s2-background.webp')">
                <div class="flex flex-col items-center justify-center flex-grow px-10 pb-20 text-white md:items-start md:justify-end md:px-30">
                    <p class="pb-5 text-5xl font-bold text-center md:text-6xl md:pt-0 pt-9">HOP IN, BUILD STUFF, HAVE FUN</p>
                    <p class="text-center md:text-lg">The Official Minecraft Server of BSCS-2A! Available for Minecraft Java Edition players.</p>
                    <button id="copy-button" onclick="copyToClipboard()" class="bg-yellow-500 text-black md:text-lg font-bold py-2 px-5 rounded-md mt-5 hover:bg-[#2D3748] hover:text-white hover:cursor-pointer transition duration-300 ease-in-out">Copy IP : cs1a.sparked.network</button>
                </div>        
            </section>
            <section class="bg-[#1a202a] md:px-30 px-5 py-5">
                <div class="grid grid-cols-3 gap-3 md:gap-10">
                    <div class="text-center text-white">
                        <p class="text-2xl font-bold text-yellow-500 md:text-5xl"><?= sanitize($uniquePlayers['total']) ?></p>
                        <p>Unique players</p>
                    </div>
                    <div class="text-center text-white">
                        <p id="player-count" class="text-2xl font-bold text-yellow-500 md:text-5xl">Loading...</p>
                        <p>Online players</p>
                    </div>
                    <div class="text-center text-white">
                        <p class="text-2xl font-bold text-yellow-500 md:text-5xl">99.8%</p>
                        <p>Server Uptime</p>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <section class="relative bg-[#2D3748] text-white">
                <?php if ($spotlight): ?>
                    <div class="relative">
                        <!-- <img src="app/public/assets/images/backgrounds/temporary-background.webp" class="w-full md:h-[50vh] h-[60vh] object-cover object-center"> -->
                        <img src="<?= sanitize($spotlight['cover']) ?>" class="w-full md:h-[60vh] h-[50vh] object-cover object-center">
                        <div class="absolute inset-0 flex flex-col items-start justify-end px-5 py-10 md:justify-center md:px-30">
                            <p class="text-lg tracking-widest text-blue-400">Spotlight</p>
                            <p class="pb-5 text-3xl font-bold md:text-5xl"><?= sanitize($spotlight['title']) ?></p>
                            <p class="text-base md:text-lg"><?= sanitize($spotlight['subtitle']) ?></p>
                            <button id="copy-button" onclick="window.location.href='app/article.php?id=<?= $spotlight['id'] ?>'" class="px-5 py-2 mt-5 font-bold text-white transition duration-300 ease-in-out bg-blue-500 rounded-md md:text-lg hover:bg-white hover:text-black hover:cursor-pointer">Read</button>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
        <?php if (isset($_SESSION['uuid'])): ?>
            <section class="bg-[#2D3748] text-white">
                <div class="px-5 pt-10 md:px-30">
                    <div class="flex gap-2 py-5 overflow-x-auto md:gap-5 md:pl-5" onwheel="if(this.scrollWidth>this.clientWidth){event.preventDefault();this.scrollLeft+=event.deltaY;}">
                        <?php foreach ($onlinePlayers as $player): ?>
                            <div onclick="window.location.href='app/profile.php?player=<?= $player['uuid'] ?>'" class="text-black flex-shrink-0 md:pr-10 pr-10 pt-10 bg-[url(../assets/images/ui/topcard-green.jpg)] bg-cover bg-bottom-right rounded-lg shadow-md p-3 flex flex-col max-w-35 hover:cursor-pointer transition-transform duration-300 ease-in-out hover:scale-102 hover:shadow-xl hover:ring-4 hover:ring-green-400/50 group">
                                <img src="https://starlightskins.lunareclipse.studio/render/ultimate/steve/bust?skinUrl=<?= $player['skin']?>" class="mb-1 transition-transform duration-300 group-hover:scale-110">
                                <p class="font-bold truncate"><?= $player['username'] ?></p>
                                <p class="text-sm text-gray-700">Online</p>
                            </div>
                        <?php endforeach; ?>
                        <?php foreach ($offlinePlayers as $player): ?>
                            <div onclick="window.location.href='app/profile.php?player=<?= $player['uuid'] ?>'" class="flex flex-col flex-shrink-0 p-3 pt-10 pr-10 text-black transition-transform duration-300 ease-in-out bg-gray-400 bg-cover rounded-lg shadow-md bg-bottom-right max-w-35 hover:cursor-pointer hover:scale-102 hover:shadow-xl hover:ring-4 hover:ring-gray-400/50 group">
                                <img src="https://starlightskins.lunareclipse.studio/render/ultimate/steve/bust?skinUrl=<?= $player['skin']?>" class="mb-1 transition-transform duration-300 group-hover:scale-110">
                                <p class="font-bold truncate"><?= $player['username'] ?></p>
                                <p class="text-sm text-gray-700">Offline</p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
        <section class="flex flex-col px-5 py-10 <?= !isset($_SESSION['uuid']) ? 'bg-white ' : 'bg-[#2D3748] ' ?> md:px-30">
            <p class="text-3xl font-bold <?= !isset($_SESSION['uuid']) ? 'text-black ' : 'text-white ' ?> md:text-4xl mb-7">News</p>
            <div class="grid gap-10 md:grid-cols-3 hover:cursor-pointer">
                <?php foreach ($article  as $article): ?>
                    <?php
                        $tagColor = match ($article['tag']) {
                            'server_updates' => 'text-red-500',
                            'event' => 'text-blue-500',
                            'game_updates' => 'text-green-500',
                            'tech' => 'text-red-500',
                            default => 'text-white',
                        };
                    ?>
                    <div onclick="window.location.href='app/article.php?id=<?= $article['id'] ?>'" class="<?= !isset($_SESSION['uuid']) ? 'text-black ' : 'text-white ' ?> flex flex-col hover:cursor-pointer justify-between">
                        <div class="w-full mb-4 overflow-hidden rounded-md aspect-video">
                            <img src="<?= sanitize($article['cover']) ?>" class="object-cover w-full h-full transition duration-500 ease-in-out hover:scale-105">
                        </div>
                        <p class="<?= $tagColor ?> capitalize"><?= sanitize(str_replace('_', ' ', $article['tag'])) ?></p>
                        <p class="mb-2 text-2xl font-bold" line-clamp-2><?= sanitize($article['title']) ?></p>
                        <p class="line-clamp-3"><?= sanitize($article['subtitle']) ?></p>
                        <div class="flex items-center gap-2 mt-10">                      
                            <p class="text-sm <?= !isset($_SESSION['uuid']) ? 'text-gray-600 ' : 'text-gray-400 ' ?>"><script>document.write(localTime("<?= date("c", strtotime($article['date_posted'])) ?>", "MMMM D, YYYY"));</script></p>
                            <hr class="flex-grow <?= !isset($_SESSION['uuid']) ? 'border-gray-600 ' : 'border-gray-600 ' ?> border-1">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="flex items-center justify-end gap-2 mt-10">
                <a href="app/news.php" class="flex items-center tracking-widest text-blue-500 hover:text-blue-700">See all news
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </section>
        <?php if (!isset($_SESSION['uuid'])): ?>
            <?php
                $carouselImages = glob('app/public/assets/content/carousel/*.webp');
            ?>
                    <section class="flex flex-col bg-[#2D3748] md:px-30 px-5 py-10">
                        <p class="text-3xl font-bold text-yellow-500 md:text-4xl mb-7">The Server</p>
                        <p class="text-white md:text-lg">This server kicked off on December 10, 2024, right before Christmas break. It started as a chill place for just 7 of us, playing for fun on Aternos. Since then, things have grown — we’ve moved to a premium server for smoother gameplay and more cool stuff to do. It’s still the same cozy vibe, just better performance and more space to hang out.</p>
                        <div class="mt-5 md:relative overflow-hidden rounded-md w-full md:h-[80vh] h-full group">
                            <div id="carousel" class="flex duration-500 ease-in-out">
                                <?php foreach ($carouselImages as $img): ?>
                                    <img src="<?= $img ?>" class="flex-shrink-0 w-full">
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
            <section class="flex flex-col items-center px-5 py-5 bg-blue-500 md:px-30">
                <p class="text-center text-white "> Whether you’re here to build, explore, or just vibe with friends, welcome to the crew!</p>
            </section>
        <?php endif ?>
        <?php require 'app/views/partials/footer.php'; ?>
        <script src="app/public/js/index.js"></script>
    </body>
</html>