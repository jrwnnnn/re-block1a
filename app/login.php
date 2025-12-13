<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/core/security-headers.php';
require_once __DIR__ . '/core/session.php';
if (isset($_SESSION['uuid'])) {
    header('Location: profile.php');
    exit();
}
require_once __DIR__ . '/core/database.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $error_message = "";
    $has_error = false;

    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $auth = query("SELECT * FROM users WHERE username = ? OR email = ?", [$login, $login], "ss");

    if ($auth && password_verify($password, $auth['password'])) {
        $_SESSION['uuid'] = $auth['uuid'];
        $_SESSION['username'] = $auth['username'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

        header('Location: profile.php');
        exit();
    } else {
        $error_message = "Invalid login credentials.";
        $has_error = true;
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <?php
            $title = "Login - Block1A";
            $description = "Login to your Block1A account.";
            require 'views/partials/meta.php'; 
            include 'views/partials/gtag.php';
        ?>
        <link rel="icon" href="public/assets/icons/favicon.ico" type="image/x-icon">
        <link href="public/css/output.css" rel="stylesheet">
        <title>Block1A - Login</title>
    </head>
    <body>
        <section class="bg-[url('../assets/images/backgrounds/auth-background.webp')] bg-cover bg-center bg-no-repeat flex flex-col items-center justify-center min-h-screen px-5 md:px-30">
            <div class="bg-[#1a202a] flex flex-col rounded-md p-8 w-full max-w-md">
                <div class="flex items-start justify-between pb-5">
                    <p class="text-2xl font-bold text-white">Login to Your Account</p>
                    <img src="public/assets/images/cs1a.png" alt="cs1a logo" class="w-20">
                </div>
                <form id="loginForm" class="space-y-4" method="POST" action="login.php">
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="p-3 font-semibold text-center text-white bg-green-600 rounded-md">
                            <?= $_SESSION['success_message'] ?>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                        <div class="p-3 font-semibold text-center text-white bg-red-600 rounded-md">
                            <?= $error_message ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <label for="login" class="block text-sm font-medium text-white">Email or Username</label>
                        <input type="email" id="login" name="login" value="<?= sanitize($_POST['login'] ?? '') ?>" class="glob-input mt-1 <?= $has_error ? '!border-red-500' : 'border-gray-600' ?> focus:outline-none focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-white">Password</label>
                        <input type="password" id="password" name="password" value="" class="glob-input mt-1 <?= $has_error ? '!border-red-500' : 'border-gray-600' ?> focus:outline-none focus:ring-blue-500" required>
                    </div>
                    <div class="flex items-center justify-between pb-5">
                        <div class="flex justify-center gap-2 text-sm text-white">
                            <input type="checkbox" id="showPassword" class="" style="width: 16px; height: 16px; cursor: pointer;">
                            <label for="showPassword">Show Password</label>
                        </div>
                        <a href="contact.php" class="text-sm glob-link">Forgot password?</a>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 glob-btn hover:bg-blue-600" <?= !empty($success_message) ? 'disabled' : '' ?>>Login</button> <!-- Submit button -->
                </form>
                <div class="mt-5 text-center">
                    <p class="text-sm text-white">Don't have an account?
                    <a href="signup.php" class="glob-link">Create one!</a>
                    </p>
                </div>
            </div>
        </section>
        <script src="public/js/password_preview.js"></script>
    </body>
</html>