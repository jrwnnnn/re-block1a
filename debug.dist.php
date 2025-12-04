<?php
require_once __DIR__ . '/config/config.php';

if ($baseUrl !== 'http://localhost/priv-block1a/') {
    header('Location: ' . $baseUrl . 'debug.php');
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function display_debug_data($label, $data) {
    echo "<div style='margin: 20px 0; border: 1px solid #ccc; border-radius: 5px; overflow: hidden;'>";
    echo "<div style='background: #333; color: #fff; padding: 10px; font-weight: bold;'>$label</div>";
    echo "<div style='padding: 10px; background: #f9f9f9;'>";
    
    if (empty($data)) {
        echo "<span style='color: #888; font-style: italic;'>Empty</span>";
    } else {
        echo "<table style='width: 100%; border-collapse: collapse;'>";
        echo "<thead><tr style='background: #eee; border-bottom: 2px solid #ddd;'>";
        echo "<th style='text-align: left; padding: 8px;'>Key</th>";
        echo "<th style='text-align: left; padding: 8px;'>Type</th>";
        echo "<th style='text-align: left; padding: 8px;'>Value</th>";
        echo "</tr></thead><tbody>";
        
        foreach ($data as $key => $value) {
            $type = gettype($value);
            $display_value = $value;
            
            if (is_bool($value)) {
                $display_value = $value ? 'true' : 'false';
                $color = '#0000FF'; // Blue for bool
            } elseif (is_null($value)) {
                $display_value = 'null';
                $color = '#888888'; // Gray for null
            } elseif (is_string($value)) {
                $display_value = '"' . htmlspecialchars($value) . '"';
                $color = '#008000'; // Green for string
            } elseif (is_numeric($value)) {
                $color = '#FF0000'; // Red for numbers
            } elseif (is_array($value) || is_object($value)) {
                $display_value = '<pre style="margin: 0; font-size: 12px;">' . htmlspecialchars(print_r($value, true)) . '</pre>';
                $color = '#000';
            } else {
                $color = '#000';
            }
            
            echo "<tr style='border-bottom: 1px solid #eee;'>";
            echo "<td style='padding: 8px; font-weight: bold;'>" . htmlspecialchars($key) . "</td>";
            echo "<td style='padding: 8px; font-family: monospace; color: #666;'>$type</td>";
            echo "<td style='padding: 8px; color: $color;'>$display_value</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
    }
    echo "</div></div>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Debug Information</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 20px; background: #fff; color: #333; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 10px; }
    </style>
</head>
<body>
    <h1>Debug Information</h1>
    <p><strong>Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
    <p><strong>Session ID:</strong> <?php echo session_id(); ?></p>

    <?php
    display_debug_data('$_SESSION Variables', $_SESSION);
    display_debug_data('$_POST Variables', $_POST);
    display_debug_data('$_GET Variables', $_GET);
    display_debug_data('$_COOKIE Variables', $_COOKIE);
    display_debug_data('$_SERVER Variables', $_SERVER);
    ?>
</body>
</html>
