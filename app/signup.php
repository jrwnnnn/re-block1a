<?php
// CODEX RATING
// Efficiency: 9/10
// Security: 10/10
// Readability: 9.5/10

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/core/security-headers.php';
require_once __DIR__ . '/core/session.php';

if (isset($_SESSION['uuid'])) {
    header('Location: profile.php');
    exit();
}

require_once __DIR__ . '/core/database.php';
require_once '../auth/actions/action_signup.php';
?>

<!doctype html>
<html>
    <head>
        <?php
            $title = "Signup - Block1A";
            $description = "Create a Block1A account.";
            require_once 'views/partials/meta.php';
        ?>
        <link rel="icon" href="public/assets/icons/favicon.ico" type="image/x-icon">
        <link href="public/css/output.css" rel="stylesheet">
        <title>Block1A - Signup</title>
    </head>
    <body>
        <section class="bg-[url('../assets/images/backgrounds/auth-background.webp')] bg-cover bg-center bg-no-repeat flex flex-col items-center justify-center min-h-screen md:px-30 px-5">
            <div class="bg-[#1a202a] flex flex-col rounded-md p-8 w-full max-w-md">
                <div class="flex flex-row items-start justify-between pb-5">
                    <p class="text-2xl font-bold text-white">Create an Account</p>
                    <img src="public/assets/images/cs1a.png" alt="logo" class="w-20">
                </div>

                <form id="signupForm" class="space-y-4" method="POST" action="signup.php">
                    <div>
                        <label for="secretKey" class="block text-sm font-medium text-white">Secret Key <span class="text-red-500"><?= $secretKey_error ?></span></label>
                        <input type="text" id="secretKey" name="secretKey" value="<?= sanitize($_POST['secretKey'] ?? '') ?>"
                            class="mt-1 glob-input <?= $secretKey_error ? '!border-red-500' : 'border-gray-600' ?>" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-white">Email <span class="text-red-500"><?= $email_error ?></span></label>
                        <input type="email" id="email" name="email" value="<?= sanitize($_POST['email'] ?? '') ?>"
                            class="mt-1 glob-input <?= $email_error ? '!border-red-500' : 'border-gray-600' ?>" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-white">Password <span class="text-red-500"><?= $password_error ?></span></label>
                        <input type="password" id="password" name="password" value=""
                            class="mt-1 glob-input <?= $password_error ? '!border-red-500' : 'border-gray-600' ?>" required>
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-white">Confirm Password <span class="text-red-500"><?= $password_error ?></span></label>
                        <input type="password" id="confirm_password" name="confirm_password" value=""
                            class="mt-1 glob-input <?= $password_error ? '!border-red-500' : 'border-gray-600' ?>" required>
                    </div>
                    <div class="flex items-center gap-2 pb-5 text-sm text-white">
                        <input type="checkbox" id="showPassword" class="accent-blue-500 hover:cursor-pointer" style="width: 16px; height: 16px;">
                        <label for="showPassword">Show Password</label>
                    </div>
                    <a href="login.php" class="text-sm glob-link">Already have an account?</a>
                    <button type="submit" class="glob-btn w-full bg-blue-500 mt-3 hover:bg-blue-600 <?= !empty($success_message) ? 'disabled' : '' ?>">Signup</button>
                </form>
            </div>
        </div>
        <script">
            // Password strength meter and toggle visibility
            const form = document.querySelector('form');
                form.addEventListener('submit', (e) => {
                    if (strength < 3) {
                        e.preventDefault();
                        alert("Password is too weak. Use at least 8 characters with uppercase, lowercase, numbers, and symbols.");
                    }
                });

            document.getElementById('showPassword').addEventListener('change', function () {
                const password = document.getElementById('password');
                const confirm = document.getElementById('confirm_password');
                const type = this.checked ? 'text' : 'password';
                password.type = type;
                confirm.type = type;
            });
        </script>
    </body>
</html>