<?php
  require 'includes/security-headers.php';
  require_once 'includes/session-init.php';
  require_once 'functions/connect.php';

  $sql = "SELECT username FROM statistics WHERE status = 1";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  $onlinePlayers = [];
  while ($row = $result->fetch_assoc()) {
      $onlinePlayers[] = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
  }
  $stmt->close();

  $sql = "SELECT username FROM statistics WHERE status = 0";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->get_result();
  $offlinePlayers = [];
  while ($row = $result->fetch_assoc()) {
      $offlinePlayers[] = htmlspecialchars($row['username'], ENT_QUOTES, 'UTF-8');
  }
?>

<!doctype html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta property="og:type" content="website">
  <meta property="og:title" content="Community - Block1A">
  <meta property="og:description" content="">
  <meta property="og:image" content="https://block1a.onrender.com/assets/meta-bluemap.PNG">
  <meta property="og:url" content="https://block1a.onrender.com/bluemap.php">
  <meta property="og:site_name" content="Block1A">
  <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
  <link href="src/output.css" rel="stylesheet">
  <title>Community - BlueMap</title>
</head>
<body class="flex flex-col min-h-screen">
  <section class="bg-[#2D3748] bg-cover bg-center bg-no-repeat flex flex-col h-screen">
    <?php require 'includes/navigation.php'; ?>
  
  </section>
</body>
</html>