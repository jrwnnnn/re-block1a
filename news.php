<?php
    require 'includes/security-headers.php';
    require 'functions/connect.php';
    require_once 'includes/session-init.php';

    $stmt = $conn->prepare("SELECT * FROM articles ORDER BY date_posted DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    $conn->close();
?>

<!doctype html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
        <link href="src/output.css" rel="stylesheet">
        <title>Block1A - News</title>
    </head>
    <body>
        <?php require 'includes/navigation.php'; ?>
        <section class="flex flex-col items-center justify-center text-white bg-cover bg-center bg-no-repeat min-h-[40vh] px-5" style="background-image: url('assets/blog-hero.webp')">
            <p class="text-4xl font-bold text-yellow-500 md:text-6xl">News</p>
            <p class="mt-5 text-center md:text-lg">Stay updated with the latest news, updates, and events happening in our community.</p>
        </section>
        <?php if (isset($_SESSION['permission_level']) && $_SESSION['permission_level'] == 1): ?>
            <div class="fixed z-10 p-4 bg-yellow-500 rounded-md bottom-5 right-5 hover:bg-yellow-300 hover:cursor-pointer" onclick="window.location.href='news/editor.php?action=create';">
                <img src="https://cdn-icons-png.flaticon.com/128/3524/3524388.png" class="w-5">
            </div>
        <?php endif; ?>
        <section class="bg-[#2D3748] grid md:grid-cols-3 px-5 md:px-30 py-20 gap-10">
            <?php foreach ($posts as $post): ?>
                <?php
                    $tagColor = match ($post['tag']) {
                        'server_updates' => 'text-red-500',
                        'event' => 'text-blue-500',
                        'game_updates' => 'text-green-500',
                        'tech' => 'text-red-500',
                        default => 'text-white',
                    };
                ?>
                <div onclick="window.location.href='news/article.php?id=<?= $post['id'] ?>'" class="text-white hover:cursor-pointer">
                    <div class="w-full mb-4 overflow-hidden rounded-md aspect-video">
                        <img src="<?= htmlspecialchars($post['cover'], ENT_QUOTES, 'UTF-8') ?>" class="object-cover w-full h-full transition duration-500 ease-in-out hover:scale-105">
                    </div>
                    <p class="<?= $tagColor ?> capitalize"><?= htmlspecialchars(str_replace('_', ' ', $post['tag']), ENT_QUOTES, 'UTF-8') ?></p>
                    <p class="mb-2 text-2xl font-bold"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></p>
                    <p><?= htmlspecialchars($post['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>
                    <div class="flex items-center gap-2 mt-5">                      
                        <p class="text-sm text-gray-400"><?= date("F d, Y", strtotime($post['date_posted'])) ?></p>
                        <hr class="flex-grow border-gray-600 md:hidden border-1">
                    </div>
                </div>
            <?php endforeach; ?>
        </section>
        <?php require 'includes/footer.php'; ?>
    </body>
    </html>
