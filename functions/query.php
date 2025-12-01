<?php
// Usage: query("SELECT * FROM table WHERE id = ?", [$id], "s");
// Returns: associative array for SELECT, insert ID for INSERT, affected rows for UPDATE/DELETE
// s = string, i = integer, d = double, b = blob

function query($sql, $params = [], $types = "") {
    global $conn;
    $stmt = $conn->prepare($sql);

     if ($params && $types) {
        $stmt->bind_param($types, ...$params);
    }

    $success = $stmt->execute();

    if (stripos(trim($sql), "SELECT") === 0) {
        $result = $stmt->get_result();
        $query = $result->num_rows === 1 ? $result->fetch_assoc() : $result->fetch_all(MYSQLI_ASSOC);
    } elseif (stripos(trim($sql), "INSERT") === 0) {
        $query = $conn->insert_id;
    } else {
        $query = $stmt->affected_rows;
    }

    $stmt->close();
    return $query;
}
?>