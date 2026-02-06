<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('../db.php');

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $message = "⚠️ User already exists.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (name,email,password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $password);
        if ($stmt->execute()) {
            $message = "✅ Registered successfully. <a href='login.php'>Login here</a>";
        } else {
            $message = "❌ Error: " . $stmt->error;
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css?v=2">

  
</head>
<body>
<button class="dark-toggle" onclick="toggleDarkMode()">🌙</button>

<div class="signup-container">
  <h2>Create an Account</h2>
  <?php if (!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email Address" required>

    <input type="password" name="password" id="password" placeholder="Password" required>
    <span class="toggle-password" onclick="togglePassword()">👁️ Show Password</span>

    <div class="strength"><div class="strength-fill" id="strengthBar"></div></div>

    <button  class="btn1" type="submit">Sign Up</button>
  </form>

  <div class="link">
    Already have an account? <a href="login.php">Login</a>
  </div>
</div>


</body>
</html>
