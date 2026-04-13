<?php
include 'db.php';

$form_link = $_GET['f'] ?? '';
if (!$form_link) die("❌ Form link missing");

$stmt = $conn->prepare("SELECT id, title, form_fields FROM forms WHERE form_link=?");
$stmt->bind_param("s", $form_link);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows != 1) die("❌ Form not found");

$stmt->bind_result($form_id, $title, $fields_json);
$stmt->fetch();
$fields = json_decode($fields_json, true);

$message = $_GET['msg'] ?? '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title><?php echo htmlspecialchars($title); ?></title>
<link rel="stylesheet" href="form.css">
</head>
<body>

<h2><?php echo htmlspecialchars($title); ?></h2>

<?php if (!empty($message)): ?>
    <div class="message <?php echo strpos($message,'✅')!==false ? 'success' : 'error'; ?>">
      <?php echo $message; ?>
    </div>
<?php endif; ?>

<form action="submit_form.php?f=<?php echo $form_link; ?>" method="post" enctype="multipart/form-data">
<?php
foreach ($fields as $field) {
    $name = htmlspecialchars($field['name']);
    $label = htmlspecialchars($field['label'] ?? $name);
    $type = $field['type'];

    if ($type !== 'checkbox' && $type !== 'radio') {
        echo "<label for='$name'>$label</label>";
    }

    if ($type === 'textarea') {
        echo "<textarea name='$name' id='$name' required></textarea>";
    } elseif ($type === 'checkbox') {
        echo "<div class='checkbox-group'><label><input type='checkbox' name='$name'> $label</label></div>";
    } 



    elseif ($type === 'radio' && !empty($field['options'])) {
    echo "<label style='display:block; margin-bottom:5px;'>$label</label>";

    echo "<div class='radio-group'>";
    foreach ($field['options'] as $option) {
        $opt = htmlspecialchars($option);
        echo "<label style='margin-right:15px;'><input type='radio' name='$name' value='$opt'> $opt</label>";
    }
    echo "</div>";



    } elseif ($type === 'countrycode') {
        echo "<div style='display:flex; gap:10px;'>";
        echo "<select name='country_code'>
                <option value='+91'>India (+91)</option>
                <option value='+1'>USA (+1)</option>
                <option value='+44'>UK (+44)</option>
              </select>";
        echo "<input type='text' name='phone_number' placeholder='Phone Number'>";
        echo "</div>";
    } elseif ($type === 'file') {
        echo "<input type='file' name='$name' accept='.pdf' required>";
    } else {
        echo "<input type='$type' name='$name' id='$name' required>";
    }
}
?>
<button type="submit">Submit</button>
</form>

<script src="form.js"></script>
</body>
</html>
