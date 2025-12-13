<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/core/security-headers.php';
require_once __DIR__ . '/core/session.php';
require_once __DIR__ . '/core/RBAC.php';
RBAC('admin', '../index.php');
require_once __DIR__ . '/core/database.php';
?>

<!doctype html>
<html>
  <head>
    <?php
      $title = "[SECURE] Token Generator - Block1A";
      include 'views/partials/meta.php';
      include 'views/partials/gtag.php';
    ?>
    <link rel="icon" href="public/assets/icons/favicon.ico" type="image/x-icon">
    <link href="public/css/output.css" rel="stylesheet">
    <title>[SECURE] Token Generator - Block1A</title>
  </head>
  <body class="bg-[#2D3748] text-white min-h-screen flex flex-col items-center justify-center">
    <div class="bg-[#1a202a] p-8 rounded-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-4">Generate Password Reset Token</h1>
        
        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="p-3 mb-4 font-semibold text-center text-white bg-red-600 rounded-md">
                <?= sanitize($_SESSION['error_message']) ?>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <form action="controllers/action-router.php?action=create_reset_token" method="POST" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium">User Email</label>
                <input type="email" name="email" id="email" class="glob-input mt-1 w-full border-gray-600 focus:outline-none focus:ring-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-blue-500 glob-btn hover:bg-blue-600">Generate Token & Link</button>
        </form>

        <?php if (isset($_SESSION['generated_link'])): ?>
            <div class="mt-4">
                <label for="generated_link" class="block text-sm font-medium">Generated Link</label>
                <div class="flex gap-2 mt-1">
                    <input type="text" id="generated_link" value="<?= sanitize($_SESSION['generated_link']) ?>" class="glob-input w-full border-gray-600 focus:outline-none focus:ring-blue-500" readonly>
                    <button onclick="copyLink()" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Copy
                    </button>
                </div>
            </div>
            <?php unset($_SESSION['generated_link']); ?>
        <?php endif; ?>
    </div>
    <script>
        function copyLink() {
            var copyText = document.getElementById("generated_link");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value).then(function() {
                alert("Link copied to clipboard!");
            }, function(err) {
                console.error('Async: Could not copy text: ', err);
            });
        }
    </script>
  </body>
</html>