<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/core/security-headers.php';
require_once __DIR__ . '/core/session.php';

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
      include 'views/partials/meta.php';
    ?>
    <link rel="icon" href="public/assets/icons/favicon.ico" type="image/x-icon">
    <link href="public/css/output.css" rel="stylesheet">
    <title>Block1A - BlueMap</title>
  </head>
  <body class="flex flex-col min-h-[100dvh] justify-between">
    <?php require 'views/partials/navigation.php'; ?>
    <main class="flex bg-[#1A212B] flex-grow">
      <div class="flex-grow">
        <iframe id="iframe" src="https://bluemap-proxy.onrender.com/bluemap/#<?= $world . ':' . $x . ':0:' . $z . ':' . $zoom ?>:0:0:0:0:perspective" class="w-full h-full"></iframe>
      </div>
    </main>
  </body>
</html>