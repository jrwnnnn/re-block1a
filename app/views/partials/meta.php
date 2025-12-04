<?php
$type    = $type ?? 'website';
$title   = $title ?? $pageTitle;
$description = $description ?? 'The Official Minecraft Server of CS2A.';
$image   = $image ?? $baseUrl . 'app/public/assets/images/meta_images/default.PNG';
$url     = $url ?? ($_SERVER['REQUEST_SCHEME'] ?? 'https') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$siteName  = $siteName ?? 'Block1A';
?>

<meta property="og:type" content="<?= sanitize($type) ?>">
<meta property="og:title" content="<?= sanitize($title) ?>">
<meta property="og:description" content="<?= sanitize($description) ?>">
<meta property="og:image" content="<?= sanitize($image) ?>">
<meta property="og:url" content="<?= sanitize($url) ?>">
<meta property="og:site_name" content="<?= sanitize($siteName) ?>">

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">