<?php
require_once __DIR__ . '/../../config/config.php';
class newsControllers {
    public function create($article_id, $title, $subtitle, $cover, $tag, $spotlight, $author, $date_posted, $content) {
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

        $article_id = createID();
        $stmt = query("INSERT INTO articles (id, title, subtitle, cover, tag, spotlight, author, date_posted, content) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)", [$article_id, $title, $subtitle, $cover, $tag, $spotlight, $author, $date_posted, $content], "sssssisss");
        header("Location: ../article.php?id=$article_id");
    }

    public function edit($article_id, $title, $subtitle, $cover, $tag, $spotlight, $content) {
        $last_edited = date("Y-m-d");
        $stmt = query("UPDATE articles SET title = ?, subtitle = ?, cover = ?, tag = ?, spotlight = ?, content = ?, last_edited = ? WHERE id = ?", [$title, $subtitle, $cover, $tag, $spotlight, $content, $last_edited, $article_id], "ssssisss");
        header("Location: ../article.php?id=$article_id");
    }

    public function delete($article_id) {
        $stmt = query("DELETE FROM articles WHERE id = ?", [$article_id], "s");
        header("Location: ../news.php");
    }
}
