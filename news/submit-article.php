<?php
    require_once '../includes/session-init.php';
    require_once 'connect.php';
    require_once 'RBAC-1.php';
        $fallback = '../news.php';
    
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

        $stmt = query("INSERT INTO articles (id, title, subtitle, cover, tag, spotlight, author, date_posted, content) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [$article_id, $title, $subtitle, $cover, $tag, $spotlight, $author, $date_posted, $content], "sssssisss");
                
        header("Location: ../news/article.php?id=$article_id");
    } elseif ($action == 'edit') {
        $last_edited = date("Y-m-d");
        $stmt = query("UPDATE articles SET title = ?, subtitle = ?, cover = ?, tag = ?, spotlight = ?, content = ?, last_edited = ? WHERE id = ?", [$title, $subtitle, $cover, $tag, $spotlight, $content, $last_edited, $article_id], "ssssisss");

        header("Location: ../news/article.php?id=$article_id");
    } else {
        echo "Invalid action";
    }
?>