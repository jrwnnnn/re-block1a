<?php
  require_once __DIR__ . '/../config/config.php';
  require_once __DIR__ . '/core/security-headers.php';
  require_once __DIR__ . '/core/session.php';

  $returnError = $_GET['error'] ?? 'Unknown+error+occurred';
  $returnError = str_ireplace('+', ' ', $returnError);
?>

<!DOCTYPE html>
<html>
  <head>
    <?php
      $title = "404 - Block1A";
      include 'views/partials/meta.php';
      include 'views/partials/gtag.php';
    ?>
    <link rel="icon" href="public/assets/icons/favicon.ico" type="image/x-icon">
    <link href="public/css/output.css" rel="stylesheet">
    <title>Block1A - 404</title>
  </head>
  <body class="flex flex-col min-h-[100dvh] justify-between">
    <?php require 'views/partials/navigation.php'; ?>
    <main class="flex flex-col bg-[#2D3748] flex-grow">
        <div class="flex flex-col items-center justify-center flex-grow px-10 text-center text-white md:px-30">
          <p class="py-5 text-4xl font-bold text-center md:text-6xl"><?= $title ?></p>
          <p class="text-center md:text-lg"><?= isset($returnError) ? $returnError . "." : "" ?></p>
          <p class="text-center md:text-lg">Return to the <a href="<?= $baseUrl ?>index.php" class="text-blue-300">home page</a>.</p>
        </div>
    </main>
  </body>
</html>