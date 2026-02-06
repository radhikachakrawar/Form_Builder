
 <?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
include('../db.php');
$user_id = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Form Builder</title>
  <link rel="stylesheet" href="dashboard.css?v=2">
</head>
<body>

<h2>Form Builder Dashboard</h2>
<label>Form Title: <input type="text" id="form-title" placeholder="Enter your form title"></label>

<div id="toolbox">
  <div draggable="true" data-type="text">Text Input</div>
    <div draggable="true" data-type="email">Email</div>
  <div draggable="true" data-type="countrycode">Phone Number</div>
  <div draggable="true" data-type="radio">Radio Buttons</div>

  <div draggable="true" data-type="textarea">Textarea</div>
    <div draggable="true" data-type="file"> File Upload</div>
      <div draggable="true" data-type="checkbox">Checkbox</div>
 

</div>

<div id="form-area" ondragover="event.preventDefault()"></div>

<button id="save-form">💾 Save Form</button>

<p id="msg"></p>

<div id="popup">
  <p id="popup-msg"></p>
  <button onclick="document.getElementById('popup').style.display='none'">Close</button>
</div>

<script src="dashboard.js?v=3"></script>

</body>
</html>
