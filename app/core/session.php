<?php
if (session_status() === PHP_SESSION_NONE) {
    $lifetime = 60 * 60 * 24 * 7;
    session_set_cookie_params([
        'lifetime' => $lifetime,
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    session_start();
}
?>
