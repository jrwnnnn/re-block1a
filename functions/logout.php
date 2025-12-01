<?php
// Logs out the user by destroying the session and redirecting to index.php

require_once '../includes/session-init.php';
require_once '../includes/RBAC.php';
RBAC ('user', '../index.php');

unset($_SESSION['uuid']);
session_unset();
session_destroy();
header("Location: ../index.php");
exit;
?>