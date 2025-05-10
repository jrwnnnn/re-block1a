<?php
  require 'includes/security-headers.php';
  require_once 'includes/session-init.php';
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
  <link href="src/output.css" rel="stylesheet">
  <title>Block1A - BlueMap</title>
</head>
<body class="min-h-screen flex flex-col">
  <section class="bg-[#1A212B] bg-cover bg-center bg-no-repeat flex flex-col h-screen">
    <?php require 'includes/navigation.php'; ?>
    <div class="flex flex-grow flex-col justify-center items-center bg-[#2D3748]">
      <div id="loading" class="flex flex-col items-center justify-center bg-[#2D3748] pb-10">
        <img src="assets/panda-roll.gif" alt="Loading" class="w-30 h-30">
        <p class="md:text-4xl text-2xl text-white font-bold">Loading Map...</p>
        <p class="text-white">Hang tight while we load this.</p>
      </div>
      <iframe id="iframe" src="https://bluecolored.de/bluemap/#acrana:302:1778:0.81:113.91:0.78:67" class="w-full h-full hidden" onload="const t = Math.floor(Math.random() * 5 + 1) * 1000; setTimeout(() => { document.getElementById('loading').style.display = 'none'; document.getElementById('iframe').classList.remove('hidden'); }, t);"></iframe>
    </div>
  </section>
</body>
</html>