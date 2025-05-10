<?php
require '../includes/security-headers.php';
require_once '../includes/session-init.php';

if (isset($_SESSION['user_id'])) {
    header('Location: ../profile.php');
    exit();
}

$user_error = '';
$password_error = '';
$success_message = '';

$has_error = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require '../functions/connect.php';

    $login = $_POST['login'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM user_data WHERE username = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ss", $login, $login);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($result)) {
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email']; 
            $_SESSION['last_password_change'] = $user['last_password_change']; 
            $_SESSION['permission_level'] = $user['permission_level']; 
            
            $success_message = "Welcome back, " . htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') . "!";
            echo "<script>
                setTimeout(function() {
                    window.location.href = '../profile.php';
                }, 2000);
            </script>";
        } else {
            $password_error = "Incorrect password.";
            $has_error = true;
        }
    } else {
        $user_error = "Email not found.";
        $has_error = true;
    }

    $stmt->close();
    $conn->close();
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
        <link href="../src/output.css" rel="stylesheet">
        <title>Block1A - Login</title>
    </head>
    <body>
        <section class="bg-[url('../assets/auth-background.webp')] bg-cover bg-center bg-no-repeat flex flex-col items-center justify-center min-h-screen px-5 md:px-30">
            <div class="bg-[#1a202a] flex flex-col rounded-md p-8 w-full max-w-md">
                <div class="flex items-start justify-between pb-5">
                    <p class="text-2xl font-bold text-white">Login to Your Account</p>
                    <img src="../assets/cs1a.png" alt="logo" class="w-20">
                </div>
                <form id="loginForm" class="space-y-4" method="POST" action="login.php">
                    <?php if (!empty($password_error)): ?>
                        <div class="p-3 font-semibold text-center text-white bg-red-600 rounded-md">
                            <?= htmlspecialchars($password_error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($user_error)): ?>
                        <div class="p-3 font-semibold text-center text-white bg-red-600 rounded-md">
                            <?= htmlspecialchars($user_error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($success_message)): ?>
                        <div class="p-3 font-semibold text-center text-white bg-green-600 rounded-md">
                            <?= htmlspecialchars($success_message, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <label for="login" class="block text-sm font-medium text-white">Email</label>
                        <input type="email" id="login" name="login" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>" class="glob-input mt-1 <?= $user_error ? '!border-red-500' : 'border-gray-600' ?> focus:outline-none focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-white">Password</label>
                        <input type="password" id="password" name="password" value="<?= htmlspecialchars($_POST['password'] ?? '') ?>" class="glob-input mt-1 <?= $password_error ? '!border-red-500' : 'border-gray-600' ?> focus:outline-none focus:ring-blue-500" required>
                    </div>
                    <div class="flex items-center justify-between pb-5">
                        <div class="flex justify-center gap-2 text-sm text-white">
                            <input type="checkbox" id="showPassword" class="" style="width: 16px; height: 16px; cursor: pointer;">
                            <label for="showPassword">Show Password</label>
                        </div>
                        <a href="../contact.php" class="text-sm glob-link">Forgot password?</a>
                    </div>
                    <button type="submit" class="w-full bg-blue-500 glob-btn hover:bg-blue-600" <?= !empty($success_message) ? 'disabled' : '' ?>>
                        Login
                    </button>
                </form>

                <div class="mt-5 text-center">
                    <p class="text-sm text-white">Don't have an account?
                    <a href="signup.php" class="glob-link">Create one!</a>
                    </p>
                </div>
            </div>
        </section>
        <script src="../script/login.js"></script>
    </body>
</html>