
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db.php';

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows == 1) {
        $stmt->bind_result($id, $hash);
        $stmt->fetch();
        if (password_verify($password, $hash)) {
            $_SESSION['user_id'] = $id;
            header("Location: dashboard.php");
            exit;
        } else {
            $message = "❌ Wrong password.";
        }
    } else {
        $message = "❌ User not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link rel="stylesheet" href="login.css?v=2">
</head>
<body>

<button class="dark-toggle" onclick="toggleDarkMode()">🌙</button>

<div class="login-box">
  <h2>Welcome Back</h2>
  <?php if (!empty($message)): ?>
    <div class="message"><?php echo $message; ?></div>
  <?php endif; ?>

  <form method="post">
    <input type="email" name="email" placeholder="Email" required>
    
    <input type="password" name="password" id="password" placeholder="Password" required>
    <span class="toggle-password" onclick="togglePassword()">👁️ Show Password</span>
    
    <button class="btn1"type="submit">Login</button>
  </form>

  <div class="link">
    Don't have an account? <a href="signup.php">Sign Up</a>
  </div>
</div>

<script src="login.js"></script>


</body>
</html>
