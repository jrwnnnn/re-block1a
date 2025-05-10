<?php
    require 'includes/security-headers.php';
    require 'functions/connect.php';
    require_once 'includes/session-init.php';

    $spotlightStmt = $conn->prepare("SELECT * FROM articles WHERE spotlight = 1 ORDER BY date_posted DESC LIMIT 1");
    $spotlightStmt->execute();
    $spotlightResult = $spotlightStmt->get_result();
    $spotlightPost = $spotlightResult->fetch_assoc();
    $spotlightStmt->close();

    $nonSpotlightStmt = $conn->prepare("SELECT * FROM articles WHERE spotlight = 0 ORDER BY date_posted DESC LIMIT 3");
    $nonSpotlightStmt->execute();
    $nonSpotlightResult = $nonSpotlightStmt->get_result();
    $nonSpotlightPosts = $nonSpotlightResult->fetch_all(MYSQLI_ASSOC);
    $nonSpotlightStmt->close();

    $conn->close();
?>


<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
  <link href="src/output.css" rel="stylesheet">
  <title>Block1A - Home</title>
</head>
<body>
    <section class="flex flex-col min-h-screen bg-center bg-no-repeat bg-cover" style="background-image: url('assets/home_splash.webp')">
        <?php require 'includes/navigation.php'; ?>
        <div class="flex flex-col items-center justify-center flex-grow px-10 pb-20 text-white md:items-start md:justify-end md:px-30">
            <p class="pb-5 text-5xl font-bold text-center md:text-6xl md:pt-0 pt-9">HOP IN, BUILD STUFF, HAVE FUN</p>
            <p class="text-center md:text-lg">The Official Minecraft Server of BSCS-1A! Available for both Minecraft Java and Bedrock Platform.</p>
            <button id="copy-button" onclick="copyToClipboard()" class="bg-yellow-500 text-black md:text-lg font-bold py-2 px-5 rounded-md mt-5 hover:bg-[#2D3748] hover:text-white hover:cursor-pointer transition duration-300 ease-in-out">Copy IP : cs1a.minecra.fr</button>
        </div>        
    </section>
    <section class="bg-[#1a202a] md:px-30 px-5 py-5">
        <div class="grid grid-cols-3 gap-3 md:gap-10">
            <div class="text-center text-white">
                <p class="text-2xl font-bold text-yellow-500 md:text-5xl">27</p>
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
    <section class="relative bg-[#2D3748] text-white">
        <?php if ($spotlightPost): ?>
            <div class="relative">
                <img src="<?= htmlspecialchars($spotlightPost['cover'], ENT_QUOTES, 'UTF-8') ?>" class="w-full md:h-[70vh] h-[80vh] object-cover object-center">
                <div class="absolute inset-0 flex flex-col items-start justify-end px-5 py-10 md:justify-center md:px-30">
                    <p class="text-lg tracking-widest text-blue-400">Spotlight</p>
                    <p class="pb-5 text-3xl font-bold md:text-5xl"><?= htmlspecialchars($spotlightPost['title'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="text-base md:text-lg"><?= htmlspecialchars($spotlightPost['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>
                    <button id="copy-button" onclick="window.location.href='news/article.php?id=<?= $spotlightPost['id'] ?>'" class="px-5 py-2 mt-5 font-bold text-white transition duration-300 ease-in-out bg-blue-500 rounded-md md:text-lg hover:bg-white hover:text-black hover:cursor-pointer">Read</button>
                </div>
            </div>
        <?php endif; ?>
    </section>
    <section class="flex flex-col px-5 py-10 bg-white md:px-30">
        <p class="text-3xl font-bold text-black md:text-5xl mb-7">News</p>
        <div class="grid gap-10 md:grid-cols-3 hover:cursor-pointer">
            <?php foreach ($nonSpotlightPosts  as $post): ?>
                <?php
                    $tagColor = match ($post['tag']) {
                        'server_updates' => 'text-red-500',
                        'event' => 'text-blue-500',
                        'game_updates' => 'text-green-500',
                        'tech' => 'text-red-500',
                        default => 'text-white',
                    };
                ?>
                <div onclick="window.location.href='news/article.php?id=<?= $post['id'] ?>'" class="text-black hover:cursor-pointer">
                    <div class="w-full mb-4 overflow-hidden rounded-md aspect-video">
                        <img src="<?= htmlspecialchars($post['cover'], ENT_QUOTES, 'UTF-8') ?>" class="object-cover w-full h-full transition duration-500 ease-in-out hover:scale-105">
                    </div>
                    <p class="<?= $tagColor ?> capitalize"><?= htmlspecialchars(str_replace('_', ' ', $post['tag']), ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="mb-2 text-2xl font-bold"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><?= htmlspecialchars($post['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>
                    <div class="flex items-center gap-2 mt-5">                      
                        <p class="text-sm text-gray-600"><?= date("F d, Y", strtotime($post['date_posted'])) ?></p>
                        <hr class="flex-grow border-gray-500 md:hidden border-1">
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="flex items-center justify-end gap-2 mt-10">
            <a href="news.php" class="flex items-center tracking-widest text-blue-500 hover:text-blue-700">
            See all news
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
            </a>
        </div>
    </section>
    <section class="flex flex-col bg-[#2D3748] md:px-30 px-5 py-10">
        <p class="text-4xl font-bold text-yellow-500 md:text-6xl mb-7">The Server</p>
        <div class="md:relative overflow-hidden rounded-md w-full md:h-[80vh] h-full group">
          <div id="carousel" class="flex transition-transform duration-500 ease-in-out group-hover:brightness-50">
            <img src="assets/carousel-1.webp" alt="Screenshot 1" class="flex-shrink-0 w-full">
            <img src="assets/carousel-2.webp" alt="Screenshot 2" class="flex-shrink-0 w-full">
            <img src="assets/carousel-3.webp" alt="Screenshot 3" class="flex-shrink-0 w-full">
            <img src="assets/carousel-4.webp" alt="Screenshot 4" class="flex-shrink-0 w-full">
            <img src="assets/carousel-5.webp" alt="Screenshot 5" class="flex-shrink-0 w-full">
            <img src="assets/carousel-6.webp" alt="Screenshot 6" class="flex-shrink-0 w-full">
            <img src="assets/carousel-7.webp" alt="Screenshot 7" class="flex-shrink-0 w-full">
            <img src="assets/carousel-8.webp" alt="Screenshot 8" class="flex-shrink-0 w-full">
            <img src="assets/carousel-9.webp" alt="Screenshot 9" class="flex-shrink-0 w-full">
            <img src="assets/carousel-10.webp" alt="Screenshot 10" class="flex-shrink-0 w-full">
          </div>
          <div class="flex flex-col items-center justify-center gap-5 transition-opacity duration-300 ease-in-out md:absolute md:inset-0 md:opacity-0 group-hover:opacity-100">
            <img src="assets/cs1a.png" class="hidden md:block w-50">
            <p class="mt-5 text-white md:text-lg md:text-center md:mt-0 md:px-50">This server kicked off on December 10, 2024, right before Christmas break. It started as a chill place for just 7 of us, playing for fun on Aternos. Since then, things have grown — we’ve moved to a premium server for smoother gameplay and more cool stuff to do. It’s still the same cozy vibe, just better performance and more space to hang out.</p>
          </div>
        </div>
    </section>
    <section class="flex flex-col items-center px-5 py-5 bg-blue-500 md:px-30">
        <p class="text-center text-white "> Whether you’re here to build, explore, or just vibe with friends, welcome to the crew!</p>
    </section>
    <?php require 'includes/footer.php'; ?>
    <script src="script/index.js"></script>
</body>
</html>