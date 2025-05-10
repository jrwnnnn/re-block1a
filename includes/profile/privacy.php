<?php
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['user_id'])) {
        header('Location: auth/login.php');
        exit();
    }
?>

<div class="space-y-10 md:pr-110">    
    <div class="text-white">
        <p class="mb-5 text-2xl font-bold">Data and Privacy</p>
        <p class="mb-5">We use your data to operate essential features—like saving preferences and displaying your content. You can stop this at any time by disabling or deleting your account.</p>
    </div>
    <div class="text-white space-y-2">
        <p class="mb-5 text-2xl font-bold">Account Deactivation</p>
        <p>Disabling your account will make it inactive and hide your profile and posts from other users. You can reactivate it by logging back in. </p>
        <form action="functions/logout.php" method="POST">
            <button type="submit" class="mt-5 px-3 py-2 text-white bg-red-500 rounded-md white hover:bg-red-600 hover:text-white hover:cursor-pointer">Disable Account</button>
        </form>
    </div>

    <div class="text-white space-y-2">
        <p class="mb-5 text-2xl font-bold">Account Removal</p>
        <p id="state-del">Deleting your account is permanent and cannot be undone. All your data, including posts and profile information, will be removed.</p>
        <button onclick="showDeleteForm()" id="expand-delete-form" class="mt-5 px-4 py-2 text-red-400 bg-gray-700 rounded-lg hover:bg-gray-600 hover:cursor-pointer">Delete Account</button>
        <form id="delete-form" action="functions/delete-account.php" method="POST" class="space-y-2 hidden">
            <label for="confirm-username" class="text-white pb-3"><span class="text-red-500">You're about to delete your account! </span>To confirm, type <b>"<?= htmlspecialchars($_SESSION['username'], ENT_QUOTES< 'UTF_8'); ?>"</b> in the box below</label>
            <input id="confirm-username" name="confirmation" class="glob-input" autocomplete="off" onpaste="return false;">
            <button id="delete-account-btn" type="submit" name="destroy" class="mt-5 px-4 py-2 text-red-400 bg-gray-700 rounded-lg hover:bg-gray-600 hover:cursor-pointer" disabled>Delete Account</button>
        </form>
    </div>
</div>

<script>
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