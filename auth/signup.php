<?php
    require '../includes/security-headers.php';
    require_once '../includes/session-init.php';

    if (isset($_SESSION['user_id'])) {
        header('Location: ../profile.php');
        exit();
    }

    $email_error = '';
    $secretKey_error = '';
    $password_error = '';
    $success_message = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        require '../functions/connect.php';

        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $secretKey = trim($_POST['secretKey']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        $email_error = '';
        $password_error = '';
        $secretKey_error = '';
        $has_error = false;

        // Validate passwords
        if ($password !== $confirm_password) {
            $password_error = " - Passwords do not match.";
            $has_error = true;
        }

        if (strlen($password) < 8 || 
            !preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/[0-9]/', $password)) {
            $password_error = " - Password must be at least 8 characters, include uppercase, lowercase, and a number.";
            $has_error = true;
        }

        // Check if email already exists
        $email_check_sql = "SELECT id FROM players WHERE email = ?";
        $email_stmt = mysqli_prepare($conn, $email_check_sql);
        mysqli_stmt_bind_param($email_stmt, "s", $email);
        mysqli_stmt_execute($email_stmt);
        mysqli_stmt_store_result($email_stmt);

        if (mysqli_stmt_num_rows($email_stmt) > 0) {
            $email_error = " - Email already exists.";
            $has_error = true;
        }

        // Check if the secret key is valid
        $auth_check_sql = "SELECT uuid, username FROM auth WHERE secret = ?";
        $auth_stmt = mysqli_prepare($conn, $auth_check_sql);
        mysqli_stmt_bind_param($auth_stmt, "s", $secretKey);
        mysqli_stmt_execute($auth_stmt);
        $auth_result = mysqli_stmt_get_result($auth_stmt);

        if (mysqli_num_rows($auth_result) !== 1) {
            $secretKey_error = " - Invalid secret key.";
            $has_error = true;
        }

        if (!$has_error) {
            $auth_data = mysqli_fetch_assoc($auth_result);
            $uuid = $auth_data['uuid'];
            $username = $auth_data['username'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert into players
            $insert_sql = "INSERT INTO players (email, password, uuid, username) VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert_sql);
            mysqli_stmt_bind_param($stmt, "ssss", $email, $hashed_password, $uuid, $username);

            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['user_id'] = mysqli_insert_id($conn);
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['uuid'] = $uuid;
                
                // delete the secret from auth table
                $delete_secret_sql = "DELETE FROM auth WHERE secret = ?";
                $delete_stmt = mysqli_prepare($conn, $delete_secret_sql);
                mysqli_stmt_bind_param($delete_stmt, "s", $secretKey);
                mysqli_stmt_execute($delete_stmt);
                $delete_stmt->close();

                $success_message = "Success! Logging you in...";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = '../profile.php';
                    }, 2000);
                </script>";
            } else {
                $password_error = "Signup failed. Please try again.";
            }

            $stmt->close();
        }

        $email_stmt->close();
        $auth_stmt->close();
        $conn->close();
    }

?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta property="og:type" content="website">
            <meta property="og:title" content="Signup - Block1A">
            <meta property="og:description" content="Join our community and start your adventure today!">
            <meta property="og:image" content="../assets/season2-banner.PNG">
            <meta property="og:url" content="https://block1a.onrender.com/auth/signup.php">
            <meta property="og:site_name" content="Block1A">
        <link rel="icon" href="assets/favicon.ico" type="image/x-icon">
        <link href="../src/output.css" rel="stylesheet">
        <title>Block1A - Signup</title>
    </head>
    <body>
        <section class="bg-[url('../assets/auth-background.webp')] bg-cover bg-center bg-no-repeat flex flex-col items-center justify-center min-h-screen md:px-30 px-5">
            <div class="bg-[#1a202a] flex flex-col rounded-md p-8 w-full max-w-md">
                <div class="flex flex-row items-start justify-between pb-5">
                    <p class="text-2xl font-bold text-white">Create an Account</p>
                    <img src="../assets/cs1a.png" alt="logo" class="w-20">
                </div>

                <?php if ($success_message): ?>
                    <div class="p-3 mb-4 font-semibold text-center text-white bg-green-600 rounded-md"><?= $success_message ?></div>
                <?php endif; ?>

                <form id="signupForm" class="space-y-4" method="POST" action="signup.php">
                    <div>
                        <label for="secretKey" class="block text-sm font-medium text-white">Secret Key <span class="text-red-500"><?= $secretKey_error ?></label>
                        <input type="text" id="secretKey" name="secretKey" value="<?= htmlspecialchars($_POST['secretKey'] ?? '') ?>"
                            class="mt-1 glob-input <?= $secretKey_error ? '!border-red-500' : 'border-gray-600' ?>" required>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-white">Email <span class="text-red-500"><?= $email_error ?></span></label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                            class="mt-1 glob-input <?= $email_error ? '!border-red-500' : 'border-gray-600' ?>" required>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-white">Password <span class="text-red-500"><?= $password_error ?></label>
                        <input type="password" id="password" name="password" value="<?= htmlspecialchars($_POST['password'] ?? '') ?>"
                            class="mt-1 glob-input <?= $password_error ? '!border-red-500' : 'border-gray-600' ?>" required>
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-white">Confirm Password <span class="text-red-500"><?= $password_error ?></label>
                        <input type="password" id="confirm_password" name="confirm_password" value="<?= htmlspecialchars($_POST['confirm_password'] ?? '') ?>"
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
        <script src="../script/signup.js"></script>
    </body>
</html>