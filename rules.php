<?php
  require 'includes/security-headers.php';
  require_once 'includes/session-init.php';
  
  $page = $_GET['page'] ?? 'home';
  $rules = json_decode(file_get_contents('data/rules.json'), true);

  if (!isset($rules[$page])) {
      header("Location: 404.php?error=notfound");
      exit;
  }
  $current = $rules[$page];

  $pages = array_keys($rules);
  $currentIndex = array_search($page, $pages);
  $prevPage = $pages[$currentIndex - 1] ?? null;
  $nextPage = $pages[$currentIndex + 1] ?? null;
?>
<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
  <link href="src/output.css" rel="stylesheet">
  <title>Block1A - Rules</title>
</head>
<body>
  <div class="flex flex-col">
    <?php require 'includes/navigation.php'; ?>
    <div class="bg-[#2D3748] flex flex-grow md:flex-row flex-col text-white min-h-screen md:px-30 px-5 rules-sidebar">
      <div class="min-w-[20vw] py-10 pr-5 md:border-r-1 md:border-gray-500">
        <div class="flex flex-col space-y-4">
          <?php foreach ($pages as $key): ?>
            <a href="rules.php?page=<?= $key ?>" class="<?= $key === $page ? 'text-white bg-gray-700' : 'text-gray-300' ?> rounded-md p-2 mb-2 hover:bg-gray-700">
              <?= ucwords(str_replace('_', ' ', $key)) ?>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="mt-5 md:p-5">
        <hr class="block mb-10 border-gray-500 md:hidden">
        <p class="mb-10 text-4xl font-bold break-words md:text-6xl"><?= htmlspecialchars($current['title'], ENT_QUOTES, 'UTF-8') ?></p>

        <?php foreach ($current['sections'] as $i => $section): ?>
          <p id="sec<?= $i+1 ?>" class="mb-10 text-2xl font-bold"><?= htmlspecialchars($section['heading'], ENT_QUOTES, 'UTF-8') ?></p>
          <div class="flex flex-col mb-10 space-y-4">
            <?php foreach ($section['paragraphs'] as $para): ?>
              <p><?= htmlspecialchars($para, ENT_QUOTES, 'UTF-8') ?></p>
            <?php endforeach; ?>
          </div>
        <?php endforeach; ?>

        <div class="flex flex-col mt-20 mb-10 md:justify-between md:flex-row">
          <?php if ($prevPage): ?>
            <button onclick="window.location.href='rules.php?page=<?= $prevPage ?>'" class="glob-btn bg-yellow-500 !text-black font-bold mt-5 hover:bg-yellow-600">Previous: <?= ucwords(str_replace('_', ' ', $prevPage)) ?></button>
          <?php endif; ?>
          <?php if ($nextPage): ?>
            <button onclick="window.location.href='rules.php?page=<?= $nextPage ?>'" class="glob-btn bg-yellow-500 !text-black font-bold mt-5 hover:bg-yellow-600">Next: <?= ucwords(str_replace('_', ' ', $nextPage)) ?></button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
  <?php require 'includes/footer.php'; ?>
  
</body>
</html>
