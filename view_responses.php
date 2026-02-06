

   <?php
session_start();
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$form_id = intval($_GET['form_id'] ?? 0);
if (!$form_id) {
    die("Form ID missing");
}

$stmt = $conn->prepare("SELECT title FROM forms WHERE id=? AND user_id=?");
$stmt->bind_param("ii", $form_id, $_SESSION['user_id']);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows != 1) {
    die("Form not found or you don't have access.");
}
$stmt->bind_result($title);
$stmt->fetch();

$stmt = $conn->prepare("SELECT id,response_data,submitted_at FROM responses WHERE form_id=? ORDER BY submitted_at DESC");
$stmt->bind_param("i", $form_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html>
<head>
     <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Responses for <?php echo htmlspecialchars($title); ?></title>
</head>
<body>
<div class="container mt-4">
<h2>Responses for <?php echo htmlspecialchars($title); ?></h2>
<a href="dashboard.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

<?php
if ($result->num_rows == 0) {
    echo "<p>No responses yet.</p>";
} else {
    while ($row = $result->fetch_assoc()) {
        $response = json_decode($row['response_data'], true);
        echo '<div class="card mb-3"><div class="card-body">';
        echo '<h5>Response #' . $row['id'] . ' - ' . $row['submitted_at'] . '</h5><ul>';
        foreach ($response as $key => $value) {
            $val = is_array($value) ? implode(', ', $value) : htmlspecialchars($value);
            echo "<li><strong>" . htmlspecialchars($key) . ":</strong> $val</li>";
        }
        echo '</ul></div></div>';
    }
}

?>
</div>
</body>
</html> 





