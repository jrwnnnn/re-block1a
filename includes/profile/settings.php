<?php
    require_once 'includes/session-init.php';
    require_once 'functions/connect.php';

    if (!isset($_SESSION['user_id'])) {
        header('Location: pages/login.php');
        exit();
    }

    $userId = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT * FROM user_data WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if (!$user) {
        die("User not found.");
    }

    $error = [];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $username = !empty($_POST['username']) ? htmlspecialchars(trim($_POST['username'])) : $user['username'];
        $email = !empty($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : $user['email'];
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if ($username === $user['username'] && $email === $user['email'] && empty($newPassword)) {
            $_SESSION['success_profile'] = "No changes were made. ദ്ദി •⩊• )";
            echo "<script>window.location.href = 'profile.php';</script>";
            exit();
        }

        if ($username !== $user['username']) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $username) || !preg_match('/[a-zA-Z0-9]/', $username)) {
                $error['username'] = "Username must only contain letters, numbers, or underscores, and must have at least one letter or number.";
            } elseif (strlen($username) < 3 || strlen($username) > 16) {
                $error['username'] = "Username must be between 3 and 16 characters long.";
            } else {
                $stmt = $conn->prepare("SELECT id FROM user_data WHERE username = ? AND id != ?");
                $stmt->bind_param("si", $username, $userId);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $error['username'] = "Username is already taken.";
                }
            }
        }

        if ($email !== $user['email']) {
            $stmt = $conn->prepare("SELECT id FROM user_data WHERE email = ? AND id != ?");
            $stmt->bind_param("si", $email, $userId);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error['email'] = "Email is already in use.";
            }
        }

        $hashedPassword = null;
        $updatePassword = false;

        if (!empty($newPassword)) {
            if (!password_verify($currentPassword, $user['password'])) {
                $error['currentPassword'] = "Password is incorrect.";
            } elseif ($newPassword !== $confirmPassword) {
                $error['newPassword'] = "Passwords don't match.";
            } else if (strlen($newPassword) < 8 || !preg_match('/[A-Z]/', $newPassword) || !preg_match('/[a-z]/', $newPassword) || !preg_match('/[0-9]/', $newPassword)) {
                $error['newPassword'] = "Password must be at least 8 characters, include uppercase, lowercase, a number.";
            } else {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updatePassword = true;
            }
        }

        if (empty($error)) {
            if ($updatePassword) {
                $stmt = $conn->prepare("UPDATE user_data SET username = ?, email = ?, password = ?, last_password_change = NOW() WHERE id = ?");
                $stmt->bind_param("sssi", $username, $email, $hashedPassword, $userId);
                $_SESSION['last_password_change'] = gmdate('Y-m-d H:i:s');

            } else {
                $stmt = $conn->prepare("UPDATE user_data SET username = ?, email = ? WHERE id = ?");
                $stmt->bind_param("ssi", $username, $email, $userId);
            }
    
            if ($stmt->execute()) {
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                if ($updatePassword) {
                    $_SESSION['success_password'] = "Password updated successfully!";
                } else {
                    $_SESSION['success_profile'] = "Profile updated successfully!";
                }

                echo "<script>window.location.href = 'profile.php';</script>";
                exit();
            }
        }
    }
?>

<div class="space-y-10 md:pr-100">    
    <div class="text-white space-y-2">
        <p class="mb-5 text-2xl font-bold e">Profile Settings</p>

        <?php if (!empty($_SESSION['success_profile'])): ?>
            <div class="p-3 font-semibold text-center text-white bg-green-600 rounded-md">
                <?= htmlspecialchars($_SESSION['success_profile']) ?>
            </div>
            <?php unset($_SESSION['success_profile']); ?>
        <?php endif; ?>


        <form method="POST" class="space-y-2">

            <div>
                <label for="username" class="block mb-1 text-gray-300">Username 
                    <?php if (!empty($error['username'])): ?>
                        <span class="text-red-500">- <?= htmlspecialchars($error['username']) ?></span>
                    <?php endif; ?>
                </label>
                <input type="text" id="username" name="username" class="glob-input <?= !empty($error['username']) ? '!border-red-500' : 'border-gray-600 focus:border-blue-500' ?>" value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : htmlspecialchars($user['username']) ?>"">
            </div>

            <div>
                <label for="email" class="block mb-1 text-gray-300">Email 
                    <?php if (!empty($error['email'])): ?>
                        <span class="text-red-500">- <?= htmlspecialchars($error['email']) ?></span>
                    <?php endif; ?>
                </label>
                <input type="email" id="email" name="email" class="glob-input <?= !empty($error['email']) ? '!border-red-500' : 'border-gray-600 focus:border-blue-500' ?>" value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : htmlspecialchars($user['email']) ?>">
            </div>

            <button type="submit" class="glob-btn mt-5 bg-blue-500 hover:bg-blue-600">Save Changes</button>
        </form>
    </div>
    <div class="text-white space-y-2">
        <p class="mb-5 text-2xl font-bold">Password </p>
        <p>Please remember your password as there is currently no way to reset it.</p>
        <p class="mb-5 text-sm italic text-gray-300"> Last changed: <span id="last_auth_change_timestamp" data-time="<?= htmlspecialchars($_SESSION['last_password_change'], ENT_QUOTES, 'UTF-8') ?>"></span></p>

        <?php if (!empty($_SESSION['success_password'])): ?>
            <div class="p-3 font-semibold text-center text-white bg-green-600 rounded-md">
                <?= htmlspecialchars($_SESSION['success_password']) ?>
            </div>
            <?php unset($_SESSION['success_password']); ?>
        <?php endif; ?>

        <form method="POST" class="space-y-2">
            <div>
                <label for="current_password" class="block mb-2 text-gray-300">Current Password 
                    <?php if (!empty($error['currentPassword'])): ?>
                        <span class="text-red-500">- <?= htmlspecialchars($error['currentPassword'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </label>
                <input type="password" id="current_password" name="current_password" class="glob-input <?= !empty($error['currentPassword']) ? '!border-red-500' : 'border-gray-600' ?>" required>
            </div>
            <div>
                <label for="password" class="block mb-2 text-gray-300">New Password
                    <?php if (!empty($error['newPassword'])): ?>
                        <span class="text-red-500">- <?= htmlspecialchars($error['newPassword'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </label>
                <input type="password" id="password" name="password" class="glob-input <?= !empty($error['newPassword']) ? '!border-red-500' : '!border-gray-600' ?>" required>            
            </div>
            <div>
                <label for="confirm_password" class="block mb-2 text-gray-300">Confirm New Password 
                    <?php if (!empty($error['newPassword'])): ?>
                        <span class="text-red-500">- <?= htmlspecialchars($error['newPassword'], ENT_QUOTES, 'UTF-8') ?></span>
                    <?php endif; ?>
                </label>
                <input type="password" id="confirm_password" name="confirm_password" class="glob-input <?= !empty($error['newPassword']) ? '!border-red-500' : 'border-gray-600' ?>" required>            
            </div>

            <div class="flex items-center gap-2 text-white">
                <input type="checkbox" id="showPassword" class="" style="width: 16px; height: 16px; cursor: pointer;">
                <label for="showPassword">Show Password</label>
            </div>

            <button type="submit" class="glob-btn mt-5 bg-blue-500 hover:bg-blue-600">Change Password</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('showPassword').addEventListener('change', function () {
    const cu_pass = document.getElementById('current_password');
    const pass = document.getElementById('password');
    const confirm = document.getElementById('confirm_password');
    const type = this.checked ? 'text' : 'password';
    cu_pass.type = type;
    pass.type = type;
    confirm.type = type;
    });

    const el = document.getElementById('last_auth_change_timestamp');
    const utcTime = el.dataset.time + ' UTC';
    const date = new Date(utcTime); 
    if (!isNaN(date)) {
        el.innerText = date.toLocaleString(); 
    } else {
        el.innerText = "Never"; 
    }
</script>