<?php
header("Strict-Transport-Security: max-age=31536000; includeSubDomains");
// header("Content-Security-Policy: default-src 'self'");
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("Referrer-Policy: no-referrer-when-downgrade");
header("Permissions-Policy: geolocation=(), microphone=()");
?>