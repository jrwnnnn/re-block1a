<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/core/security-headers.php';
require_once __DIR__ . '/core/session.php';
require_once __DIR__ . '/core/database.php';
require_once __DIR__ . '/core/RBAC.php';
RBAC ('user', 'login.php');

$uuid = $_SESSION['uuid'];
$user = query("SELECT * FROM users WHERE uuid = ?", [$uuid], "s");
$playerData = query("SELECT * FROM player_data WHERE uuid = ?", [$uuid], "s");
?>

<!doctype html>
<html>
    <head>
        <?php
        $title = "Settings - Block1A";
        include 'views/partials/meta.php';
        ?>
        <link rel="icon" href="public/assets/icons/favicon.ico" type="image/x-icon">
        <link href="public/css/output.css" rel="stylesheet">
        <title>Settings - Block1A</title>
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
        <script src="public/js/dateUtils.js"></script>
    </head>
    <body class="bg-[#2D3748]">
        <?php require 'views/partials/navigation.php'; ?>
        <main class="flex flex-col min-h-[100dvh] md:px-60 px-5 py-10">
            <a href="profile.php" class="mb-10 glob-link">Back to Profile</a>
            <section class="grid md:grid-cols-2 md:gap-15 gap-10">  
                <div class="space-y-10">
                </div>
                <div class="space-y-10">
                    <div class="text-white space-y-2">
                        <p class="text-2xl font-bold">Profile Settings</p>
                        <form action="controllers/action-router.php?action=updateProfile" method="POST" class="space-y-2">
                            <div>
                                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                                <label for="banner_link" class="block mb-2 text-gray-300">Banner Image<span class="text-red-500"><?= isset($_SESSION['banner_error']) ? " - " . sanitize($_SESSION['banner_error']) : '' ?></span></label>
                                <img id="bannerPreview" class="object-cover w-full h-25 mb-2 rounded-lg" src="<?= !empty($user['bannerUrl']) ? sanitize($user['bannerUrl']) : 'public/assets/images/backgrounds/s2-background.webp' ?>">
                                <input type="text" name="banner_link" class="glob-input <?= isset($_SESSION['banner_error']) ? "!border-red-500" : '' ?>" value="<?= isset($_SESSION['rejected_banner_link']) ? sanitize($_SESSION['rejected_banner_link']) : (isset($user['bannerUrl']) ? sanitize($user['bannerUrl']) : '') ?>" autocomplete="off">
                                <p class="text-gray-400 text-sm mt-1">Link to an image to use as your profile banner. Leave blank to remove.</p>
                            </div>
                            <?php unset($_SESSION['banner_error'], $_SESSION['rejected_banner_link']) ?>
                            <button type="submit" name="updateProfile" class="glob-btn mt-5 bg-blue-500 hover:bg-blue-600 hover:cursor-pointer mr-5">Save Settings</button>
                        </form>
                    </div>
                    <div class="text-white space-y-2">
                        <p class="text-2xl font-bold">Data and Privacy</p>
                        <p>We use your data to operate essential features like saving preferences and displaying your playerdata. You can stop this at any time by disabling or deleting your account.</p>
                        <form action="controllers/action-router.php?action=updatePrivacy" method="POST" class="mt-5 space-y-2">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <div class="flex items-center gap-2 text-white">
                                <input type="checkbox" name="privateProfile" class="accent-blue-500 hover:cursor-pointer" style="width: 16px; height: 16px;" <?= $user['isPrivate'] ? 'checked' : '' ?>>
                                <label for="privateProfile">Private Profile</label>
                            </div>
                            <div class="flex items-center gap-2 text-white">
                                <input type="checkbox" name="hideDeathLog" class="accent-blue-500 hover:cursor-pointer" style="width: 16px; height: 16px;" <?= $user['hideDeathLog'] ? 'checked' : '' ?>>
                                <label for="hideDeathLog">Hide Death Log</label>
                            </div>
                            <button type="submit" name="updatePrivacy" class="mt-5 bg-blue-500 glob-btn hover:bg-blue-600">Save Settings</button>
                        </form>
                    </div>
                    <div class="text-white space-y-2 ">
                        <p class="text-2xl font-bold">Account Removal</p>
                        <p>Deleting your account is permanent and cannot be undone. All your playerdata and profile information, will be removed.</p>
                        <form action="controllers/action-router.php?action=deleteAccount" method="POST" class="space-y-2" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
                            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                            <button type="submit" name="destroy" class="px-4 py-2 mt-5 text-red-400 bg-gray-700 rounded-lg hover:bg-gray-600 hover:cursor-pointer">Delete Account</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bannerInput = document.querySelector('input[name="banner_link"]');
            const bannerPreview = document.getElementById('bannerPreview');
            const defaultBanner = 'public/assets/images/backgrounds/s2-background.webp';
            
            bannerInput.addEventListener('input', function() {
                const url = this.value.trim();
                if (url) {
                    bannerPreview.src = url;
                } else {
                    bannerPreview.src = defaultBanner;
                }
            });
        });
    </script>
    </body>
</html>