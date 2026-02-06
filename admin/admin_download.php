<?php
include('../db.php');

$id = $_GET['id'] ?? 0;
$id = intval($id);

// Fetch response by ID
$stmt = $conn->prepare("SELECT response_data FROM responses WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("❌ File not found");
}

$row = $result->fetch_assoc();
$data = json_decode($row['response_data'], true);

// Find file in response
$filePath = '';
foreach ($data as $key => $value) {
    if (preg_match('/\.(pdf|docx?|jpg|jpeg|png)$/i', $value)) {
        $filePath = __DIR__ . "/upload/" . basename($value); // ✅ correct folder
        $downloadName = preg_replace('/^[^_]+_/', '', basename($value)); // strip prefix
        break;
    }
}

if (!$filePath || !file_exists($filePath)) {
    die("❌ File missing from server");
}

// Serve file
$mime = mime_content_type($filePath);
header("Content-Type: " . $mime);
header("Content-Disposition: attachment; filename=\"" . $downloadName . "\"");
header("Content-Length: " . filesize($filePath));

readfile($filePath);
exit;
