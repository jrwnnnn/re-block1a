<?php
require_once __DIR__ . '/../../../core/security-headers.php';
require_once __DIR__ . '/../../../core/session.php';
require_once __DIR__ . '/../../../core/database.php';
require_once __DIR__ . '/../../../core/RBAC.php';
RBAC ('user', 'login.php');

date_default_timezone_set('Asia/Manila');

$uuid = $_SESSION['uuid'];
$user = query("SELECT * FROM users WHERE uuid = ?", [$uuid], "s"); // Fetch current user data

if (!$user) {
    die("User not found.");
}

$error = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = !empty($_POST['email']) ? filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL) : $user['email'];
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if ($email === $user['email'] && empty($newPassword)) { // No changes made
        $_SESSION['success_profile'] = "No changes were made. ദ്ദി •⩊• )";
        header("Location: profile.php");
        exit();
    }

    if ($email !== $user['email']) { // Email has changed, check for uniqueness
        $stmt = query("SELECT id FROM users WHERE email = ? AND uuid != ?", [$email, $uuid], "ss");
        if ($stmt) {
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
            $_SESSION['last_password_change'] = date('F j, Y, g:i a');
            $stmt = query("UPDATE users SET email = ?, password = ?, last_password_change = ? WHERE uuid = ?", [$email, $hashedPassword, $_SESSION['last_password_change'], $uuid], "ssss");

        } else {
            $stmt = query("UPDATE users SET email = ? WHERE uuid = ?", [$email, $uuid], "ss");
        }

        if ($stmt) {
            $_SESSION['email'] = $email;
            if ($updatePassword) {
                $_SESSION['success_password'] = "Password updated successfully!";
            } else {
                $_SESSION['success_profile'] = "Profile updated successfully!";
            }

            header("Location: profile.php");
            exit();
        }
    }
}
?>

<script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
<script src="public/js/localTime.js"></script>

<a href="profile.php" class="mb-5 glob-link">&larr; Back to Statistics</a>
<div class="space-y-10">
    <div class="grid md:grid-cols-2 gap-15">  
        <div class="space-y-10">
            <div class="space-y-2 text-white">
                <p class="mb-5 text-2xl font-bold">Profile Settings</p>

                <?php if (!empty($_SESSION['success_profile'])): ?>
                    <div class="p-3 font-semibold text-center text-white bg-green-600 rounded-md">
                        <?= sanitize($_SESSION['success_profile']) ?>
                    </div>
                    <?php unset($_SESSION['success_profile']); ?>
                <?php endif; ?>


                <form method="POST" class="space-y-2">
                    <p class="block mb-1 text-gray-300">UUID</p>
                    <div class="glob-input " title="This is your unique user identifier. You can't change this."><?= $_SESSION['uuid']  ?></div>

                    <div>
                        <label for="email" class="block mb-1 text-gray-300">Email 
                            <?php if (!empty($error['email'])): ?>
                                <span class="text-red-500">- <?= sanitize($error['email']) ?></span>
                            <?php endif; ?>
                        </label>
                        <input type="email" id="email" name="email" class="glob-input <?= !empty($error['email']) ? '!border-red-500' : 'border-gray-600 focus:border-blue-500' ?>" value="<?= isset($_POST['email']) ? sanitize($_POST['email']) : sanitize($user['email']) ?>">
                    </div>

                    <button type="submit" class="mt-5 bg-blue-500 glob-btn hover:bg-blue-600">Save Changes</button>
                </form>
            </div>
            <div class="space-y-2 text-white">
                <p class="mb-5 text-2xl font-bold">Password </p>
                <p>Please remember your password as there is currently no way to reset it.</p>
                <p class="mb-5 text-sm italic text-gray-300"> Last changed: 
                    <script> 
                        document.write(localTime("<?= date('c', strtotime($_SESSION['last_password_change'])) ?>", "MMMM D, YYYY, hh:mm A")) 
                    </script>
                </p>

                <?php if (!empty($_SESSION['success_password'])): ?>
                    <div class="p-3 font-semibold text-center text-white bg-green-600 rounded-md">
                        <?= sanitize($_SESSION['success_password']) ?>
                    </div>
                    <?php unset($_SESSION['success_password']); ?>
                <?php endif; ?>

                <form method="POST" class="space-y-2">
                    <div>
                        <label for="current_password" class="block mb-2 text-gray-300">Current Password 
                            <?php if (!empty($error['currentPassword'])): ?>
                                <span class="text-red-500">- <?= sanitize($error['currentPassword']) ?></span>
                            <?php endif; ?>
                        </label>
                        <input type="password" id="current_password" name="current_password" class="glob-input <?= !empty($error['currentPassword']) ? '!border-red-500' : 'border-gray-600' ?>" required>
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-gray-300">New Password
                            <?php if (!empty($error['newPassword'])): ?>
                                <span class="text-red-500">- <?= sanitize($error['newPassword']) ?></span>
                            <?php endif; ?>
                        </label>
                        <input type="password" id="password" name="password" class="glob-input <?= !empty($error['newPassword']) ? '!border-red-500' : '!border-gray-600' ?>" required>            
                    </div>
                    <div>
                        <label for="confirm_password" class="block mb-2 text-gray-300">Confirm New Password 
                            <?php if (!empty($error['newPassword'])): ?>
                                <span class="text-red-500">- <?= sanitize($error['newPassword']) ?></span>
                            <?php endif; ?>
                        </label>
                        <input type="password" id="confirm_password" name="confirm_password" class="glob-input <?= !empty($error['newPassword']) ? '!border-red-500' : 'border-gray-600' ?>" required>            
                    </div>

                    <div class="flex items-center gap-2 text-white">
                        <input type="checkbox" id="showPassword" class="" style="width: 16px; height: 16px; cursor: pointer;">
                        <label for="showPassword">Show Password</label>
                    </div>

                    <button type="submit" class="mt-5 bg-blue-500 glob-btn hover:bg-blue-600">Change Password</button>
                </form>
            </div>
        </div>
        <div class="space-y-10">    
            <div class="text-white">
                <p class="mb-5 text-2xl font-bold">Data and Privacy</p>
                <p class="mb-5">We use your data to operate essential features—like saving preferences and displaying your playerdata. You can stop this at any time by disabling or deleting your account.</p>
            </div>

            <div class="space-y-2 text-white">
                <p class="mb-5 text-2xl font-bold">Account Removal</p>
                <p id="state-del">Deleting your account is permanent and cannot be undone. All your playerdata and profile information, will be removed.</p>
                <button onclick="showDeleteForm()" id="expand-delete-form" class="px-4 py-2 mt-5 text-red-400 bg-gray-700 rounded-lg hover:bg-gray-600 hover:cursor-pointer">Delete Account</button>
                <form id="delete-form" action="auth/actions/delete-account.php" method="POST" class="hidden space-y-2">
                    <label for="confirm-username" class="pb-3 text-white"><span class="text-red-500">You're about to delete your account! </span>To confirm, type <b>"<?= sanitize($_SESSION['username']); ?>"</b> in the box below</label>
                    <input id="confirm-username" name="confirmation" class="glob-input" autocomplete="off" onpaste="return false;">
                    <button id="delete-account-btn" type="submit" name="destroy" class="px-4 py-2 mt-5 text-red-400 bg-gray-700 rounded-lg hover:bg-gray-600 hover:cursor-pointer" disabled>Delete Account</button>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    document.getElementById('showPassword').addEventListener('change', function () {
        const current_password = document.getElementById('current_password');
        const password = document.getElementById('password');
        const confirm_password = document.getElementById('confirm_password');
        const type = this.checked ? 'text' : 'password';
        current_password.type = type;
        password.type = type;
        confirm_password.type = type;
    });
    
    function showDeleteForm() {
        const deleteForm = document.getElementById('delete-form');
        const expandDeleteForm = document.getElementById('expand-delete-form');
        deleteForm.classList.remove('hidden');
        expandDeleteForm.classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const input = document.getElementById('confirm-username');
        const button = document.getElementById('delete-account-btn');
        const sessionUsername = <?php echo json_encode($_SESSION['username'], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT); ?>;

        const checkInput = () => {
            if (input.value === sessionUsername) {
                button.removeAttribute('disabled');
            } else {
                button.setAttribute('disabled', 'true');
            }
        };

        input.addEventListener('input', checkInput);
        checkInput();
    });
</script>