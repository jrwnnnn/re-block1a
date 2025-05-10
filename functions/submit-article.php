<?php
    session_start();

    function createSlug($title) {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }

    if (isset($_SESSION['permission_level']) && $_SESSION['permission_level'] == 1) {

        require 'connect.php';
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        
        $data = $_POST; 
        $action = $data['action'];
        $article_id = $data['id'] ?? null; 
    
        if ($action == 'create') {
            $baseId = createSlug($data['title']);
            $counter = 0;
            $finalId = $baseId;
            while (true) {
                $check = $conn->prepare("SELECT COUNT(*) FROM articles WHERE id = ?");
                $check->bind_param("s", $finalId);
                $check->execute();
                $check->bind_result($count);
                $check->fetch();
                $check->close();

                if ($count == 0) break;
                $counter++;
                $finalId = $baseId . '-' . $counter;
            }
            $id = $finalId; // <-- generated ID for new articles
        } else {
            $id = $article_id; // <-- Use the existing ID for editing
        }

        $title = $data['title'];
        $subtitle = $data['subtitle'];
        $cover = $data['cover'];
        $tag = $data['tag'];
        $spotlight = isset($data['spotlight']) ? 1 : 0;
        $content = $data['content'];
        $author = $_SESSION['username'];
        $date_posted = date("Y-m-d");

        if ($action == 'create') {

            $stmt = $conn->prepare("INSERT INTO articles (id, title, subtitle, cover, tag, spotlight, author, date_posted, content)
                                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssisss", $id, $title, $subtitle, $cover, $tag, $spotlight, $author, $date_posted, $content);
            $stmt->execute();
            $stmt->close();

            header("Location: ../news/article.php?id=$id");

        } elseif ($action == 'edit') {
            
            $last_edited = date("Y-m-d");

            $stmt = $conn->prepare("UPDATE articles SET title = ?, subtitle = ?, cover = ?, tag = ?, spotlight = ?, content = ?, last_edited = ? WHERE id = ?");
            $stmt->bind_param("ssssisss", $title, $subtitle, $cover, $tag, $spotlight, $content, $last_edited, $id);
            $stmt->execute();
            $stmt->close();

            header("Location: ../news/article.php?id=$id");

        } else {

            echo "Invalid action";

        }

        $conn->close();

    }
    else {
        header('Location: ../news.php');
        exit;
    }
?>