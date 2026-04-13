<?php
include 'db.php';

// Fetch all responses
$sql = "SELECT * FROM responses ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Form Responses</title>
<link rel="stylesheet" href="admin_responses.css">
</head>
<body>
<div class="container">
    <h2>Form Responses</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Submitted At</th>
            <th>Response Data</th>
            <th>Uploaded Document</th>
        </tr>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $data = json_decode($row['response_data'], true);

                echo "<tr>";
                echo "<td>".$row['id']."</td>";
                echo "<td>".$row['submitted_at']."</td>";

                // Show all response details
                echo "<td>";
                $filePath = "";
                $fileLabel = "";
                if ($data) {
                    foreach ($data as $key => $value) {
                        // Check if the value is a file (from uploads folder)
                        if (preg_match('/\.(pdf|jpg|jpeg|png|docx?)$/i', $value)) {
                            $filePath = $value;
                            $fileLabel = $key; // Admin-given label like Resume/Document

                            // Remove random prefix before underscore
                            $cleanFileName = preg_replace('/^[^_]+_/', '', basename($value));

                            echo "<strong>".htmlspecialchars($key).":</strong> ".htmlspecialchars($cleanFileName)."<br>";
                        } else {
                            echo "<strong>".htmlspecialchars($key).":</strong> ".htmlspecialchars($value)."<br>";
                        }
                    }
                }
                echo "</td>";
if (!empty($filePath)) {
    $cleanFileName = preg_replace('/^[^_]+_/', '', basename($filePath)); 
    echo "<td><a class='download-btn' href='admin_download.php?id=".$row['id']."' download='".htmlspecialchars($cleanFileName)."'>Download</a></td>";
} else {
    echo "<td>No File</td>";
}    
     echo "</tr>";
    }
        } else {
            echo "<tr><td colspan='4'>No responses yet</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>
