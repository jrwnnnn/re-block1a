<?php
    require 'includes/security-headers.php';
    require_once 'includes/session-init.php';
    
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link href="src/output.css" rel="stylesheet">
    <title>Block1A - <?php echo htmlspecialchars($mainTopic, ENT_QUOTES, 'UTF-8'); ?></title>
</head>
<body>
    <?php require 'includes/navigation.php'; ?>
    <section class="flex md:flex-row flex-col gap-5 bg-[#2D3748] pt-10 pb-20 md:px-30 px-10">
        <div class="text-white flex-grow">
            <p class="md:text-6xl text-4xl font-bold"><?php echo htmlspecialchars($mainTopic, ENT_QUOTES, 'UTF-8'); ?></p>

            <div class="bg-[#1A212B] px-8 py-5 rounded-lg my-5">
                <p class="text-lg text-white font-bold mb-2">Table of Contents</p>
                <ul class="text-white list-disc ml-5">
                    <?php foreach ($subTopics as $sub): ?>
                        <li><a href="#<?php echo urlencode(strtolower(str_replace(' ', '-', $sub['title']))); ?>" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($sub['title'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <p class="text-white pb-5"><?php echo htmlspecialchars($description, ENT_QUOTES, 'UTF-8'); ?></p>

            <!-- <div class="px-4 my-5" style="border-left: 4px solid #ECC94B;">
                <p class="text-white"><span class="text-white font-bold">See: </span> <span class="text-blue-400 hover:underline">Common Issues and Troubleshooting</p>
            </div> -->

            <?php foreach ($subTopics as $sub): ?>
                <p id="<?= htmlspecialchars($id, ENT_QUOTES, 'UTF-8') ?>" class="text-2xl text-white font-bold py-4"><?php echo htmlspecialchars($sub['title'], ENT_QUOTES, 'UTF-8'); ?></p>
                <div class="text-white py-5 prose prose-invert max-w-none space-y-3">
                    <?php echo $sub['content']; ?>
                </div>
            <?php endforeach; ?>

            <div class="flex md:flex-row flex-col justify-between items-center bg-[#1A212B] px-8 py-3 rounded-lg my-5">
                <p class="text-lg text-white font-bold">Is this article helpful?</p>
                <div id="feedback-section" class="mt-3 md:mt-0">
                    <button id="helpful-yes" class="bg-green-500 text-white md:text-lg font-bold py-1 px-5 rounded-md hover:cursor-pointer mr-3">Yes</button>
                    <button id="helpful-no" class="bg-red-500 text-white md:text-lg font-bold py-1 px-5 rounded-md hover:cursor-pointer">No</button>
                </div>
                <p id="feedback-thankyou" class="text-gray-500 hidden">Thank you for your feedback!</p>
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
                <p class="text-lg text-white font-bold mb-2">Related Articles</p>
                <ul class="text-white list-disc ml-5">
                    <?php foreach ($relatedArticles as $article): ?>
                        <li><a href="<?php echo htmlspecialchars($article['link'], ENT_QUOTES, 'UTF-8'); ?>" class="text-blue-400 hover:underline"><?php echo htmlspecialchars($article['title'], ENT_QUOTES, 'UTF-8'); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </section>
    <?php require 'includes/footer.php'; ?>
    
</body>
</html>
