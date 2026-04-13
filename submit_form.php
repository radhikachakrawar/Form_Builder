<?php
require 'PHPMailer-master/src/PHPMailer.php';
require 'PHPMailer-master/src/SMTP.php';
require 'PHPMailer-master/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'db.php';

$form_link = $_GET['f'] ?? '';
if (!$form_link) die("❌ Form link missing");

// Get form fields
$stmt = $conn->prepare("SELECT id, title, form_fields FROM forms WHERE form_link=?");
$stmt->bind_param("s", $form_link);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows != 1) die("❌ Form not found");

$stmt->bind_result($form_id, $title, $fields_json);
$stmt->fetch();
$fields = json_decode($fields_json, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cleaned_post = array_map('strip_tags', $_POST);
    $file_uploaded = [];

    // Handle file uploads
    foreach ($fields as $field) {
        if ($field['type'] === 'file') {
            $inputName = $field['name'];
            if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === 0) {
                $target = 'upload/';
                $filename = uniqid() . "_" . basename($_FILES[$inputName]['name']);
                if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $target . $filename)) {
                    $file_uploaded[$inputName] = $filename;
                    $cleaned_post[$inputName] = $filename;
                } else {
                    die("❌ Failed to upload file.");
                }
            }
        }
    }

    // Build labeled response
    $labeled_response = [];
    foreach ($fields as $field) {
        $name = $field['name'];
        $label = $field['label'] ?? $name;

        if ($field['type'] === 'checkbox') {
            $value = isset($_POST[$name]) ? 'Yes' : 'No';
        } elseif ($field['type'] === 'countrycode') {
            $code = $_POST['country_code'] ?? '';
            $phone = $_POST['phone_number'] ?? '';
            $value = "$code $phone";
            $label = "Phone";
        } elseif ($field['type'] === 'file' && !empty($cleaned_post[$name])) {
            $value = $cleaned_post[$name];
        } else {
            $value = $_POST[$name] ?? '';
        }

        $labeled_response[$label] = $value;
    }

    $response_data = json_encode($labeled_response, JSON_UNESCAPED_UNICODE);

    // Save to DB
    $stmt2 = $conn->prepare("INSERT INTO responses (form_id, response_data) VALUES (?, ?)");
    $stmt2->bind_param("is", $form_id, $response_data);
    if (!$stmt2->execute()) die("❌ Failed to save form submission.");

    // Prepare email
    $user_email = $_POST['email'] ?? '';
    if (filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'radhikaac06@gmail.com';  //add admin email
            $mail->Password = 'zhnqbjtdpdpuynod'; // App password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('radhikaac06@gmail.com', 'Form Bot'); //admin
            $mail->addAddress($user_email);
            $mail->isHTML(true);
            $mail->Subject = '✅ Your Form Submission Received';

            // Build email HTML
            $emailBody = "<body style='margin:0; padding:0; background:#f5f5f5;'>
  <div style='width:100%; padding:20px 0; text-align:center;'>
    <div style='display:inline-block; max-width:600px; background:#fff; padding:25px; border-radius:10px; text-align:left;'>
      <h2 style='text-align:center; color:#28a745; margin-bottom:10px;'>Thank You for Your Submission!</h2>
      <p style='text-align:center; color:#555; margin-bottom:20px;'>Your response has been recorded and a copy is sent to this email.</p>
      
      <table style='width:100%; border-collapse: collapse; margin:0 auto;'>
        <thead>
          <tr style='background:#007BFF; color:white;'>
            <th style='padding:10px; text-align:left;'>Field</th>
            <th style='padding:10px; text-align:left;'>Information</th>
          </tr>
        </thead>
        <tbody>";

            foreach ($labeled_response as $label => $value) {
                if (in_array($label, array_keys($file_uploaded))) {
                    $cleanFileName = preg_replace('/^[^_]+_/', '', $value);
                    $value = "<a href='http://localhost/form_builder_project/upload/$value' target='_blank' style='color:#28a745;text-decoration:none;'>Download $cleanFileName</a>";
                } else {
                    $value = htmlspecialchars($value);
                }
                $emailBody .= "<tr><td style='border:1px solid #ddd; padding:8px;'>$label</td><td style='border:1px solid #ddd; padding:8px;'>$value</td></tr>";
            }
            $emailBody .= "</tbody></table></div></div>";

            $mail->Body = $emailBody;
            $mail->send();
        } catch (Exception $e) {
        }
    }















    echo "<!DOCTYPE html>
<html lang='en'>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width, initial-scale=1.0'>
<title>Thank You</title>
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
.container { background:#fff; padding:40px; border-radius:15px; box-shadow:0 4px 15px rgba(0,0,0,0.2); text-align:center; max-width:500px; }
h1 { color:#28a745; margin-bottom:10px; }
p { color:#555; font-size:16px; margin-bottom:20px; }
a { display:inline-block; padding:10px 20px; background:#007BFF; color:#fff; text-decoration:none; border-radius:5px; transition:0.3s; }
a:hover { background:#0056b3; }
</style>
</head>
<body>
<div class='container'>
<h1>Thank You!</h1>
<p>Your response has been saved and a copy has been sent to your email.</p>
<a href='form.php?f=$form_link'>Go Back to Form</a>
</div>
</body>
</html>";
    exit;
}
?>
