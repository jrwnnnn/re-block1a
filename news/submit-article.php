<?php
// CODEX RATING
// Efficiency: 9/10
// Security: 10/10
// Readability: 9/10

require_once '../includes/security-headers.php';
require_once '../includes/session-init.php';
require_once '../functions/connect.php';
require_once '../includes/RBAC.php';
RBAC ('editor', '../news.php');

// Function to create a unique article ID
// Example: 'a1b2c3d4e5f6g7h8i9j0k1l2m3n4o5p6'
function createID(){
    $id = bin2hex(random_bytes(16));
    $stmt = query("SELECT id FROM articles WHERE id = ?", [$id], "s");
    if ($stmt) {
        return createID();
    } else {
        return $id;
    }
}

$action = $_POST['action'];
$article_id = $_POST['id'] ?? null; 
$title = $_POST['title'];
$subtitle = $_POST['subtitle'];
$cover = $_POST['cover'];
$tag = $_POST['tag'];
$spotlight = isset($_POST['spotlight']) ? 1 : 0;
$author = $_SESSION['username'];
$date_posted = date("Y-m-d");
$content = $_POST['content'];

if ($action == 'create') {
    $article_id = createID();
    // Insert new article into the database
    $stmt = query("INSERT INTO articles (id, title, subtitle, cover, tag, spotlight, author, date_posted, content) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [$article_id, $title, $subtitle, $cover, $tag, $spotlight, $author, $date_posted, $content], "sssssisss");
    header("Location: ../news/article.php?id=$article_id");
} elseif ($action == 'edit') {
    $last_edited = date("Y-m-d");
    // Update existing article in the database
    $stmt = query("UPDATE articles SET title = ?, subtitle = ?, cover = ?, tag = ?, spotlight = ?, content = ?, last_edited = ? WHERE id = ?", [$title, $subtitle, $cover, $tag, $spotlight, $content, $last_edited, $article_id], "ssssisss");
    header("Location: ../news/article.php?id=$article_id");
} else {
    echo "Invalid action";
}
?>