<?php
    require '../includes/security-headers.php';
    require '../functions/connect.php';
    require_once '../includes/session-init.php';

    $id = $_GET['id'] ?? '';
    $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
    $stmt->close();
    $conn->close();

    if (!$post) {
        header("Location: ../404.php?error=notfound");
        exit;
    }

    $tagColor = match ($post['tag']) {
        'server_updates' => 'text-orange-500',
        'event' => 'text-blue-500',
        'game_updates' => 'text-green-500',
        'tech' => 'text-red-500',
        default => 'text-white',
    };

    $post['tag'] = match ($post['tag']) {
        'server_updates' => 'Server Updates',
        'event' => 'Event',
        'game_updates' => 'Game Updates',
        'tech' => 'Tech',
        default => 'Unknown Tag',
    };

?>


<!doctype html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
        <link href="../src/output.css" rel="stylesheet">
        <title>Block1A - <?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></title>
    </head>
    <body>
        <?php require 'includes/navigation.php'; ?>
        <?php if (isset($_SESSION['permission_level']) && $_SESSION['permission_level'] == 1): ?>
            <div class="fixed z-10 flex flex-col gap-3 bottom-5 right-5">
                <div class="flex items-center gap-2 p-4 bg-red-500 rounded-md hover:cursor-pointer hover:bg-red-600"
                    onclick="if (confirm('Are you sure you want to delete this article? (This action is irreversable)')) { window.location.href='../functions/delete-article.php?id=<?= htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') ?>'; }">
                    <img src="https://cdn-icons-png.flaticon.com/128/3096/3096687.png" class="w-5">
                </div>
                <div class="flex items-center gap-2 p-4 bg-yellow-500 rounded-md hover:cursor-pointer hover:bg-yellow-600"
                    onclick="window.location.href='editor.php?action=edit&id=<?= htmlspecialchars($post['id'], ENT_QUOTES, 'UTF-8') ?>';">
                    <img src="https://cdn-icons-png.flaticon.com/128/9356/9356210.png" class="w-5">
                </div>
        </div>
        <?php endif; ?>
        <img src="<?= htmlspecialchars($post['cover'], ENT_QUOTES, 'UTF-8') ?>" alt="cover" class="w-full max-h-[40vh] object-cover object-center">
        <section class="flex flex-col bg-[#2D3748] space-y-2 md:px-30 px-5 pt-10">
            <p class="text-4xl font-bold text-center text-white md:text-6xl"><?= htmlspecialchars($post['title'], ENT_QUOTES, 'UTF-8') ?></p>
            <p class="text-center text-white md:text-lg"><?= htmlspecialchars($post['subtitle'], ENT_QUOTES, 'UTF-8') ?></p>
            <div class="flex self-center gap-3 pt-5">
                <p class="text-sm <?= $tagColor ?>"><?= htmlspecialchars($post['tag'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="text-sm text-gray-500">|</p>
                <p class="text-sm text-white"><?= htmlspecialchars($post['author'], ENT_QUOTES, 'UTF-8') ?></p>
                <p class="text-sm text-gray-500">|</p>
                <p class="text-sm text-white"><?= date("F d, Y", strtotime($post['date_posted'])) ?></p>
            </div>
            <hr class="border-t-2 border-[#4A5568] mt-5">
        </section>
        <div id="content" class="bg-[#2D3748] md:px-[25vw] px-5 py-20 text-white markdown"></div>
        <?php if (!$post['last_edited'] == NULL): ?>
            <div class="flex justify-center items-flex-end bg-[#2D3748] md:px-30 px-5 pb-5">
                <p class="italic text-center text-gray-500">Last edited on <?= date("F d, Y", strtotime($post['last_edited'])) ?></p>
            </div>
        <?php endif; ?>
        <?php require 'includes/footer.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script>
            const content = <?= json_encode($post['content']) ?>;
            document.querySelector('#content').innerHTML = marked.parse(content);
        </script>
    </body>
    </html>