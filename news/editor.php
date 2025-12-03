<?php
// CODEX RATING
// Efficiency: 9/10
// Security: 9.5/10
// Readability: 9/10

require_once '../includes/security-headers.php';
require_once '../includes/session-init.php';
require_once '../includes/RBAC.php';
RBAC('editor', '../news.php');
require_once '../functions/connect.php';

$action = $_GET['action'] ?? 'create';
$article_id = $_GET['id'] ?? null;

if ($action == 'edit' && $article_id) {
    $article = query("SELECT * FROM articles WHERE id = ?", [$article_id], "s");
} else {
    $article = null;
}
?>

<!doctype html>
<html>
<head>
    <?php
        $title = "Editor - Block1A";
        include '../includes/meta.php';
    ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css">
    <link href="../src/output.css" rel="stylesheet">
    <link href="../src/easymde.css" rel="stylesheet">
    <title>Block1A - Editor</title>
</head>
<body class="min-h-screen">
    <?php require '../includes/navigation.php'; ?>

    <img src="" id="coverPreview" alt="cover" class="hidden w-full max-h-[40vh] object-cover object-center">
    <section class="flex flex-col md:px-30 px-5 py-10 pb-20 text-white bg-[#2D3748]">
        <form id="postForm" class="space-y-4" method="POST" action="actions/submit-article.php">
            <!-- Hidden fields for action type and article ID -->
            <input type="hidden" name="action" value="<?= $action ?>">
            <input type="hidden" name="id" value="<?= $article ? $article['id'] : '' ?>">

            <input type="text" name="title" placeholder="Title" class="w-full text-4xl font-bold text-white md:text-6xl focus:outline-none" value="<?= $article ? sanitize($article['title']) : '' ?>" required autocomplete="off">
            <textarea type="text" name="subtitle" placeholder="Subtitle" class="w-full text-lg text-white md:text-2xl focus:outline-none" required autocomplete="off"><?= $article ? sanitize($article['subtitle']) : '' ?></textarea >
            <div class="flex flex-col gap-3 md:flex-row">
                <input type="text" name="cover" placeholder="Cover Image URL" class="px-3 py-2 text-white bg-gray-800 rounded-lg focus:outline-none" value="<?= $article ? $article['cover'] : '' ?>" required autocomplete="off">
                <div class="flex gap-3">
                    <select name="tag" class="flex-grow px-3 py-2 text-white bg-gray-800 rounded-lg md:w-auto md:order focus:outline-none">
                        <option value="server_updates" <?= $article && $article['tag'] == 'server_updates' ? 'selected' : '' ?>>Server Updates</option>
                        <option value="event" <?= $article && $article['tag'] == 'event' ? 'selected' : '' ?>>Event</option>
                        <option value="game_updates" <?= $article && $article['tag'] == 'game_updates' ? 'selected' : '' ?>>Game Updates</option>
                        <option value="tech" <?= $article && $article['tag'] == 'tech' ? 'selected' : '' ?>>Tech</option>
                    </select>
                    <div class="flex gap-3 px-3 py-2 bg-gray-800 rounded-md">
                        <img src="https://mc-heads.net/avatar/<?= sanitize($_SESSION['username']);?>" alt="avatar" class="w-5 rounded aspect-square">
                        <p class="text-white"><?= sanitize($_SESSION['username']) ?></p>
                    </div>
                </div>
            </div>

            <!-- Markdown Editor Textarea -->
            <textarea name="content" id="editor"><?= $article ? sanitize($article['content']) : '' ?></textarea>
            <div class="flex items-start justify-between gap-3">
                <button type="submit" class="bg-blue-500 glob-btn md:text-lg hover:bg-blue-600"">
                    <?= $action == 'edit' ? 'Update Article' : 'Post Article' ?>
                </button>
                <label for="spotlight" class="flex items-center gap-2 text-white">
                    <input type="checkbox" name="spotlight" id="spotlight" class="w-4 h-4 text-blue-500 rounded focus:ring focus:ring-blue-300" <?= $article && $article['spotlight'] == 1 ? 'checked' : '' ?>>Spotlight
                </label>
            </div>
        </form>
    </section>

    <?php require '../includes/footer.php'; ?>

    <!-- Scripts for EasyMDE editor and custom logic -->
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>
    <script src="script/cover_preview.js"></script>
    <script src="script/easymde.js"></script>
    <script src="script/editor.js"></script>

</script>

</body>
</html>
