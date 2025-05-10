<?php
    require '../includes/security-headers.php';
    require_once '../includes/session-init.php';


    if (isset($_SESSION['permission_level']) && $_SESSION['permission_level'] == 1) {
        require '../functions/connect.php';
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
    
        $action = $_GET['action'] ?? 'create';
        $article_id = $_GET['id'] ?? null;

        if ($action == 'edit' && $article_id) {
            $stmt = $conn->prepare("SELECT * FROM articles WHERE id = ?");
            $stmt->bind_param("s", $article_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $article = $result->fetch_assoc();
            $stmt->close();
        } else {
            $article = null;
        }
    } else {
        header('Location: ../news.php');
        exit;
    }
    $conn->close();
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../assets/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
    <link href="../src/output.css" rel="stylesheet">
    <link href="../src/simplemde.css" rel="stylesheet">
    <title>Block1A - Editor</title>
</head>
<body class="min-h-screen">
    <?php require 'includes/navigation.php'; ?>
    <img src="" id="coverPreview" alt="cover" class="hidden w-full max-h-[40vh] object-cover object-center">
    <div id="loading" class="fixed inset-0 flex flex-col items-center justify-center invisible z-99" style="background-color: rgba(0, 0, 0, 0.75);">
        <img src="../assets/panda-roll.gif" alt="Loading" class="w-30 h-30">
    </div>
    <section class="flex flex-col md:px-30 px-5 py-10 pb-20 text-white bg-[#2D3748]">
        <form id="postForm" class="space-y-4" onsubmit="formLoading(); return false;">
            <input type="hidden" name="action" value="<?= $action ?>">
            <input type="hidden" name="id" value="<?= $article ? $article['id'] : '' ?>">

            <input type="text" name="title" placeholder="Title" class="w-full text-4xl font-bold text-white md:text-6xl focus:outline-none" value="<?= $article ? htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8') : '' ?>" required autocomplete="off">
            <textarea type="text" name="subtitle" placeholder="Subtitle" class="w-full text-lg text-white md:text-2xl focus:outline-none" required autocomplete="off"><?= $article ? htmlspecialchars($article['subtitle'], ENT_QUOTES, 'UTF-8') : '' ?></textarea >

            <div class="flex flex-col gap-3 md:flex-row">
                <input type="text" name="cover" placeholder="Cover Image URL" class="px-3 py-2 text-white bg-gray-800 rounded-lg focus:outline-none" value="<?= $article ? htmlspecialchars($article['cover'], ENT_QUOTES, 'UTF-8') : '' ?>" required autocomplete="off">
                <div class="flex gap-3">
                    <select name="tag" class="flex-grow px-3 py-2 text-white bg-gray-800 rounded-lg md:w-auto md:order focus:outline-none">
                        <option value="server_updates" <?= $article && $article['tag'] == 'server_updates' ? 'selected' : '' ?>>Server Updates</option>
                        <option value="event" <?= $article && $article['tag'] == 'event' ? 'selected' : '' ?>>Event</option>
                        <option value="game_updates" <?= $article && $article['tag'] == 'game_updates' ? 'selected' : '' ?>>Game Updates</option>
                        <option value="tech" <?= $article && $article['tag'] == 'tech' ? 'selected' : '' ?>>Tech</option>
                    </select>
                    <div class="flex gap-3 px-3 py-2 bg-gray-800 rounded-md">
                        <img src="https://mc-heads.net/avatar/<?php echo htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8');?>" alt="avatar" class="w-5 rounded aspect-square">
                        <p class="text-white"><?= $_SESSION['username'] ?></p>
                    </div>
                </div>
            </div>
            <textarea name="content" id="editor"><?= $article ? htmlspecialchars($article['content'], ENT_QUOTES, 'UTF-8') : '' ?></textarea>
            <div class="flex items-start justify-between gap-3">
                <button type="submit" class="bg-blue-500  glob-btn md:text-lg hover:bg-blue-600" onclick="loadingLong()">
                    <?= $action == 'edit' ? 'Update Article' : 'Post Article' ?>
                </button>

                <label for="spotlight" class="flex items-center gap-2 text-white">
                    <input type="checkbox" name="spotlight" id="spotlight" class="w-4 h-4 text-blue-500 rounded focus:ring focus:ring-blue-300" <?= $article && $article['spotlight'] == 1 ? 'checked' : '' ?>>Spotlight
                </label>
            </div>
        </form>
    </section>
    <?php require 'includes/footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
    <script src="../script/simplemde.js"></script>
    <script src="../script/editor.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", () => {
        const coverInput = document.querySelector("input[name='cover']");
        const coverPreview = document.getElementById("coverPreview");

        const updatePreview = () => {
            const url = coverInput.value.trim();
            if (url) {
                coverPreview.src = url;
                coverPreview.classList.remove("hidden");
            } else {
                coverPreview.classList.add("hidden");
                coverPreview.src = ""; // clear the image
            }
        };

        coverInput.addEventListener("input", updatePreview);

        updatePreview();
    });
</script>

</body>
</html>
