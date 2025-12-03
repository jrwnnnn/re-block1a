<?php
// Deletes an article based on the provided ID in the URL query parameters.

require_once '../includes/session-init.php';
require_once '../includes/RBAC.php';
RBAC ('editor', '../news.php');
require_once '../functions/connect.php';

$stmt = query("DELETE FROM articles WHERE id = ?", [$_GET['id']], "s");
if ($stmt) {
    header('Location: ../news.php');
    exit;
} else {
    echo "Error deleting article: " . $stmt->error;
    exit;
}
?>