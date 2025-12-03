<?php
    require_once 'includes/security-headers.php';
    require_once 'functions/connect.php';
    require_once 'includes/session-init.php';

    $article = query("SELECT * FROM articles ORDER BY date_posted DESC");
?>

<!doctype html>
<html>
    <head>
        <?php 
        $title = "News - Block1A";
        $description = "Stay updated with the latest news, updates, and events happening in our server.";
        require 'includes/meta.php'; ?>
        <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
        <link href="src/output.css" rel="stylesheet">
        <title>Block1A - News</title>
    </head>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
    <script src="script/localTime.js"></script>
    <body>
        <?php require 'includes/navigation.php'; ?>
        <section class="flex flex-col items-center justify-center text-white bg-cover bg-center bg-no-repeat min-h-[40vh] px-5" style="background-image: url('assets/blog-hero.webp')">
            <p class="text-4xl font-bold text-yellow-500 md:text-6xl">News</p>
            <p class="mt-5 text-center md:text-lg">Stay updated with the latest news, updates, and events happening in our server.</p>
        </section>
        <!-- Create article button -->
        <?php if (isset($_SESSION['permission_level']) && ($_SESSION['permission_level'] === "editor" || $_SESSION['permission_level'] === "admin")): ?>
            <div class="fixed z-10 p-4 bg-yellow-500 rounded-md bottom-5 right-5 hover:bg-yellow-300 hover:cursor-pointer" onclick="window.location.href='news/editor.php?action=create';">
                <img src="https://cdn-icons-png.flaticon.com/128/3524/3524388.png" class="w-5">
            </div>
        <?php endif; ?>
        <section class="bg-[#2D3748] grid md:grid-cols-3 px-5 md:px-30 py-20 gap-10">
            <?php foreach ($article as $article): ?>
                <?php
                    $tagColor = match ($article['tag']) {
                        'server_updates' => 'red-500',
                        'event' => 'blue-500',
                        'game_updates' => 'green-500',
                        'tech' => 'red-500',
                        default => 'white',
                    };
                ?>
                <div onclick="window.location.href='news/article.php?id=<?= $article['id'] ?>'" class="flex flex-col text-white hover:cursor-pointer justify-between">
                    <div>
                        <div class="w-full mb-4 overflow-hidden rounded-md aspect-video">
                            <img src="<?= sanitize($article['cover']) ?>" class="object-cover w-full h-full transition duration-500 ease-in-out hover:scale-105">
                        </div>
                        <p class="text-sm text-<?= $tagColor ?> capitalize"><?= sanitize(str_replace('_', ' ', $article['tag'])) ?></p>
                        <p class="mb-2 text-2xl font-bold"><?= sanitize($article['title']) ?></p>
                        <p><?= sanitize($article['subtitle']) ?></p>
                    </div>
                    <div class="flex items-center gap-2 mt-5">                      
                        <p class="text-sm text-gray-400"><script>document.write(localTime("<?= date('c', strtotime($article['date_posted'])) ?>", "MMMM D, YYYY"))</script></p>
                        <hr class="flex-grow border-gray-600 border-1">
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
        <?php require 'includes/footer.php'; ?>
    </body>
</html>
