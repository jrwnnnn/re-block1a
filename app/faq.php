<?php
    require_once __DIR__ . '/../config/config.php';
    require 'core/security-headers.php';
    require_once 'core/session.php';
    
    $requestedTopic = $_GET['topic'] ?? '';

    $data = json_decode(file_get_contents('data/faq.json'), true);
    $faqs = $data['faqs'] ?? [];

    $matchedTopic = null;

    foreach ($faqs as $faq) {
        $slug = strtolower(str_replace(' ', '-', $faq['mainTopic']));
        if ($slug === $requestedTopic) {
            $matchedTopic = $faq;
            break;
        }
    }

    if (!$matchedTopic) {
        header("Location: 404.php");
        echo "Page not found.";
        exit;
}

    $mainTopic = $matchedTopic['mainTopic'];
    $description = $matchedTopic['description'];
    $subTopics = $matchedTopic['subTopics'];
    $relatedArticles = $matchedTopic['relatedArticles'];
    $id = strtolower(str_replace(' ', '-', $matchedTopic['mainTopic']));
    $id = preg_replace('/[^a-z0-9\-_]/', '', $id);

?>

<!doctype html>
<html>
    <head>
        <?php
        $title = "FAQ - Block1A";
        include 'views/partials/meta.php';
        ?>
        <link rel="icon" href="public/assets/favicon.ico" type="image/x-icon">
        <link href="public/css/output.css" rel="stylesheet">
        <title>Block1A - <?php echo htmlspecialchars($mainTopic, ENT_QUOTES, 'UTF-8'); ?></title>
    </head>
    <body>
        <?php require 'views/partials/navigation.php'; ?>
        <section class="flex md:flex-row flex-col gap-5 bg-[#2D3748] pt-10 pb-20 md:px-30 px-5">
            <div class="flex-grow text-white">
                <p class="text-4xl font-bold md:text-6xl"><?php echo htmlspecialchars($mainTopic, ENT_QUOTES, 'UTF-8'); ?></p>

                <div class="bg-[#1A212B] px-8 py-5 rounded-lg my-5">
                    <p class="mb-2 text-lg font-bold text-white">Table of Contents</p>
                    <ul class="ml-5 text-white list-disc">
                        <?php foreach ($subTopics as $sub): ?>
                            <li><a href="#<?php echo urlencode(strtolower(str_replace(' ', '-', $sub['title']))); ?>" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($sub['title'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <p class="pb-5 text-white"><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>

                <!-- <div class="px-4 my-5" style="border-left: 4px solid #ECC94B;">
                    <p class="text-white"><span class="font-bold text-white">See: </span> <span class="text-blue-400 hover:underline">Common Issues and Troubleshooting</p>
                </div> -->

                <?php foreach ($subTopics as $sub): ?>
                    <p id="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" class="py-4 text-2xl font-bold text-white"><?php echo htmlspecialchars($sub['title'], ENT_QUOTES, 'UTF-8'); ?></p>
                    <div class="py-5 space-y-3 prose text-white prose-invert max-w-none">
                        <?php echo $sub['content']; ?>
                    </div>
                <?php endforeach; ?>

                <div class="flex md:flex-row flex-col justify-between items-center bg-[#1A212B] px-8 py-3 rounded-lg my-5">
                    <p class="text-lg font-bold text-white">Is this article helpful?</p>
                    <div id="feedback-section" class="mt-3 md:mt-0">
                        <button id="helpful-yes" class="px-5 py-1 mr-3 font-bold text-white bg-green-500 rounded-md md:text-lg hover:cursor-pointer">Yes</button>
                        <button id="helpful-no" class="px-5 py-1 font-bold text-white bg-red-500 rounded-md md:text-lg hover:cursor-pointer">No</button>
                    </div>
                    <p id="feedback-thankyou" class="hidden text-gray-500">Thank you for your feedback!</p>
                </div>

                <script>
                    document.getElementById('helpful-yes').addEventListener('click', function() {
                        document.getElementById('feedback-section').style.display = 'none';
                        document.getElementById('feedback-thankyou').classList.remove('hidden');
                    });

                    document.getElementById('helpful-no').addEventListener('click', function() {
                        document.getElementById('feedback-section').style.display = 'none';
                        document.getElementById('feedback-thankyou').classList.remove('hidden');
                    });
                </script>
            </div>

            <div class="min-w-[25vw]">
                <div class="bg-[#1A212B] px-8 py-5 rounded-lg">
                    <p class="mb-2 text-lg font-bold text-white">Related Articles</p>
                    <ul class="ml-5 text-white list-disc">
                        <?php foreach ($relatedArticles as $article): ?>
                            <li><a href="<?php echo htmlspecialchars($article['link'], ENT_QUOTES, 'UTF-8'); ?>" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </section>
        <?php require 'views/partials/footer.php'; ?>  
    </body>
</html>
