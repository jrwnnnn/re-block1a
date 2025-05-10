<?php
  require 'includes/security-headers.php';
  require_once 'includes/session-init.php';

  $returnError = $_GET['error'] ?? null;
  if ($returnError == 'notfound') {
      $title = 'Page Not Found';
      $message = 'The page you are looking for does not exist.';
  } else if ($returnError == 'unauthorized') {
      $title = 'Unauthorized Access';
      $message = 'You do not have permission to access this page.';
  } else {
      $title = 'Error';
      $message = 'An unknown error occurred.';
  }
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
  <link href="src/output.css" rel="stylesheet">
  <title>Block1A - 404</title>
</head>
<body>
  <section class="flex flex-col bg-[#2D3748] bg-cover bg-center bg-no-repeat min-h-screen">
    <?php require 'includes/navigation.php'; ?>
      <div class="flex flex-col items-center justify-center flex-grow px-10 text-center text-white pb-30 md:px-30">
        <img src="assets/i-am-steve-minecraft.gif" alt="Steve" class="">
        <p class="py-5 text-4xl font-bold text-center md:text-6xl"><?= htmlspecialchars($title, ENT_QUOTES, 'UTF-8') ?></p>
        <p class="text-center md:text-lg"><?= htmlspecialchars($message, ENT_QUOTES, 'UTF-8') ?></p>
        <p class="text-center md:text-lg">Return to the <a href="index.php" class="text-blue-300">home page</a>.</p>
      </div>
  </section>
  <?php require 'includes/footer.php'; ?>
  
</body>
</html>