<?php
  require 'includes/security-headers.php';
  require_once 'includes/session-init.php';

?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Contact - Block1A">
  <meta property="og:description" content="To report a player, reset your password, or to ask a generic question about our server...">
  <meta property="og:image" content="assets/season2-banner.PNG">
  <meta property="og:url" content="https://block1a.onrender.com/contact.php">
  <meta property="og:site_name" content="Block1A">
  <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
  <link href="src/output.css" rel="stylesheet">
  <title>Block1A - Contact</title>
</head>
<body class="flex flex-col min-h-screen">
    <?php require 'includes/navigation.php'; ?>
    <section class="bg-[#2D3748] flex flex-col h-screen text-white md:px-30 px-5 pt-20">
        <h1 class="pb-5 text-4xl font-bold md:text-6xl">Contact Us</h1>
        <p>To report a player, reset your password, or to ask a generic question about our server, join our <a href="https://discord.gg/" class="text-blue-500 underline">Discord</a>!</p>
        <p>You can email our support team at <a href="mailto:support@block1a.com" class="text-blue-500 underline">support@block1a.com</a>, we aim to reply within 24 hours.</p>
        <p>You can also contact us in-game using the <code class="px-2 py-1 text-gray-200 bg-gray-800 rounded">/helpop</code> command.</p>
        <img src="assets/panda-and-cat.webp" alt="panda" class="w-full mt-20 max-h-[40vh] object-cover object-center">
    </section>
  
    <?php require 'includes/footer.php'; ?>
</body>
</html>