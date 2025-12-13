<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/core/security-headers.php';
require_once __DIR__ . '/core/session.php';
require_once __DIR__ . '/core/database.php';

if (isset($_SESSION['uuid'])) {
    header('Location: profile.php');
    exit();
}

$token = $_GET['token'] ?? '';
if (empty($token)) {
    header('Location: index.php');
    exit();
}

// Verify token exists
$stmt = query("SELECT email FROM reset_tokens WHERE token = ?", [$token], "s");
if (empty($stmt)) {
    header('Location: index.php');
    exit();
}

$error_message = $_GET['error'] ?? '';
?>

<!doctype html>
<html>
  <head>
    <?php
      $title = "Reset Password - Block1A";
      $description = "Reset your Block1A account password securely.";
      include 'views/partials/meta.php';
      include 'views/partials/gtag.php';
    ?>
    <link rel="icon" href="public/assets/icons/favicon.ico" type="image/x-icon">
    <link href="public/css/output.css" rel="stylesheet">
    <title>Reset Password - Block1A</title>
  </head>
  <body class="flex flex-col min-h-[100dvh] justify-between">
    <main class="flex bg-[#1A212B] flex-grow">
        <section class="bg-[url('../assets/images/backgrounds/auth-background.webp')] bg-cover bg-center bg-no-repeat flex flex-col items-center justify-center min-h-screen md:px-30 px-5 w-full">
            <div class="bg-[#1a202a] flex flex-col rounded-md p-8 w-full max-w-md">
                <div class="flex items-start justify-between pb-5">
                    <p class="text-2xl font-bold text-white">Reset Your Password</p>
                    <img src="public/assets/images/cs1a.png" alt="cs1a logo" class="w-20">
                </div>
                <form class="space-y-4" method="POST" action="controllers/action-router.php?action=reset_password">
                    <input type="hidden" name="token" value="<?= sanitize($token) ?>">
                    <?php if (!empty($error_message)): ?>
                        <div class="p-3 font-semibold text-center text-white bg-red-600 rounded-md">
                            <?= sanitize($error_message) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-white">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="glob-input mt-1 border-gray-600 focus:outline-none focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="confirm_new_password" class="block text-sm font-medium text-white">Confirm New Password</label>
                        <input type="password" id="confirm_new_password" name="confirm_new_password" class="glob-input mt-1 border-gray-600 focus:outline-none focus:ring-blue-500" required>
                    </div>
                    <div class="flex gap-2 text-sm text-white">
                        <input type="checkbox" id="showPassword" class="" style="width: 16px; height: 16px; cursor: pointer;">
                        <label for="showPassword">Show Password</label>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 glob-btn hover:bg-blue-600">Reset Password</button>
                </form>
            </div>
        </section>
    </main>
    <script src="public/js/password_preview.js"></script>
  </body>
</html>