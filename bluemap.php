<?php
require 'includes/security-headers.php';
require_once 'includes/session-init.php';

$world = isset($_GET['world']) ? floatval($_GET['world']) : "world";
$x = isset($_GET['x']) ? floatval($_GET['x']) : 1857;
$z = isset($_GET['z']) ? floatval($_GET['z']) : 1435;
$zoom = isset($_GET['zoom']) ? floatval($_GET['zoom']) : 1250;
?>

<!doctype html>
<html>
  <head>
    <?php
      $title = "BlueMap - Block1A";
      $description = "Explore the server map powered by BlueMap.";
      include 'includes/meta.php';
    ?>
    <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
    <link href="src/output.css" rel="stylesheet">
    <title>Block1A - BlueMap</title>
  </head>
  <body class="flex flex-col min-h-screen">
    <section class="bg-[#1A212B] bg-cover bg-center bg-no-repeat flex flex-col h-screen">
      <?php require 'includes/navigation.php'; ?>
      <div class="flex flex-grow flex-col justify-center items-center bg-[#2D3748]">
        <div class="flex flex-col items-center justify-center pb-10">
          <img src="assets/panda-roll.gif" alt="Server Closed" class="w-30 h-30">
          <p class="text-2xl font-bold text-white md:text-4xl">Server offline...</p>
          <p class="text-white">No map will be shown until the server opens.</p>
        </div>
      </div>
      <!-- <div class="flex flex-grow flex-col justify-center items-center bg-[#2D3748]">
        <div id="loading" class="flex flex-col items-center justify-center bg-[#2D3748] pb-10">
          <img src="assets/panda-roll.gif" alt="Loading" class="w-30 h-30">
          <p class="text-2xl font-bold text-white md:text-4xl">Loading Map...</p>
          <p class="text-white">Hang tight while we load this.</p>
        </div>
        <iframe id="iframe" src="https://bluemap-proxy.onrender.com/bluemap/#<?= $world . ':' . $x . ':0:' . $z . ':' . $zoom ?>:0:0:0:0:perspective" class="hidden w-full h-full" onload="const t = Math.floor(Math.random() * 5 + 1) * 1000; setTimeout(() => { document.getElementById('loading').style.display = 'none'; document.getElementById('iframe').classList.remove('hidden'); }, t);"></iframe>
      </div> -->
    </section>
  </body>
</html>