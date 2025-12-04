<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/core/security-headers.php';
require_once __DIR__ . '/core/database.php';
require_once __DIR__ . '/core/session.php';
require_once __DIR__ . '/core/RBAC.php';
$userRole = getUserRole();

$article = query("SELECT * FROM articles WHERE id = ?", [$_GET['id']], "s");

if (!$article) {
    header("Location: 404.php?error=notfound");
    exit;
}

$tagColor = match ($article['tag']) {
    'server_updates' => 'text-orange-500',
    'event'          => 'text-blue-500',
    'game_updates'   => 'text-green-500',
    'tech'           => 'text-red-500',
    default          => 'text-white',
};

$article['tag'] = match ($article['tag']) {
    'server_updates' => 'Server Updates',
    'event'          => 'Event',
    'game_updates'   => 'Game Updates',
    'tech'           => 'Tech',
    default          => 'Unknown Tag',
};
?>


<!doctype html>
    <html>
    <head>
        <?php
            $title = sanitize($article['title']) . " - Block1A";
            $description = sanitize($article['subtitle']);
            $image = sanitize($article['cover']);
            include 'views/partials/meta.php';
        ?>
        <link rel="icon" href="public/assets/icons/favicon.ico" type="image/x-icon">
        <link href="public/css/output.css" rel="stylesheet">
        <title>Block1A - <?= sanitize($article['title'],) ?></title>
    </head>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
    <script src="public/js/localTime.js"></script>
    <body>
        <!-- Top navigation bar -->
        <?php require 'views/partials/navigation.php'; ?>

        <!-- Delete and edit article buttons -->
        <?php if (($roleMap[$userRole] ?? 0) >= ($roleMap['editor'] ?? 0)): ?>
            <div class="fixed z-10 flex flex-col gap-3 bottom-5 right-5">
                <form class="flex items-center gap-2 p-4 bg-red-500 rounded-md hover:cursor-pointer hover:bg-red-600" onsubmit="return confirm('Are you sure you want to delete this article? (This action is irreversable)');"
                    method="POST" action="controllers/action-router.php?action=deleteArticle">
                    <input type="hidden" name="id" value="<?= $article['id'] ?>">
                    <button type="submit">
                        <img src="https://cdn-icons-png.flaticon.com/128/3096/3096687.png" class="w-5">
                    </button>
                </form>
                <div class="flex items-center gap-2 p-4 bg-yellow-500 rounded-md hover:cursor-pointer hover:bg-yellow-600"
                    onclick="window.location.href='editor.php?action=edit&id=<?= $article['id'] ?>';">
                    <img src="https://cdn-icons-png.flaticon.com/128/9356/9356210.png" class="w-5">
                </div>
            </div>
        <?php endif; ?>

        <img src="<?= sanitize($article['cover']) ?>" class="w-full max-h-[60vh] object-cover object-center">
        <section class="flex flex-col bg-[#2D3748] space-y-2 md:px-30 px-5 pt-10">
            <p class="text-4xl font-bold text-center text-white md:text-6xl"><?= sanitize($article['title']) ?></p>
            <p class="text-center text-white md:text-lg"><?= sanitize($article['subtitle']) ?></p>

            <div class="flex self-center gap-3 pt-5">
                <p class="text-sm <?= $tagColor ?>"><?= sanitize($article['tag']) ?></p>
                <p class="text-sm text-gray-500">|</p>
                <p class="text-sm text-white"><?= sanitize($article['author']) ?></p>
                <p class="text-sm text-gray-500">|</p>
                <p class="text-sm text-white"> <script>document.write(localTime("<?= date('c', strtotime($article['date_posted'])) ?>", "MMMM D, YYYY"))</script></p>
            </div>

            <hr class="border-t-2 border-[#4A5568] mt-5">
        </section>
        <div id="content" class="bg-[#2D3748] md:px-[20vw] px-5 py-20 text-white markdown"></div>
        <?php if (!$article['last_edited'] == NULL): ?>
            <div class="flex justify-center items-flex-end bg-[#2D3748] md:px-30 px-5 pb-5">
                <p class="text-sm italic text-center text-gray-500">Last edited on <script>document.write(localTime("<?= date('c', strtotime($article['last_edited'])) ?>", "MMMM D, YYYY"))</script>
                </p>
            </div>
        <?php endif; ?>
        <?php require 'views/partials/footer.php'; ?>

        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script>
            const content = <?= json_encode($article['content']) ?>;
            document.querySelector('#content').innerHTML = marked.parse(content);
        </script>
    </body>
</html>