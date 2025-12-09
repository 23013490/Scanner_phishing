<?php
require_once 'db.php';
require_once 'session.php';

// Check if user is already logged in
if (isLoggedIn()) {
  header('Location: index.php');
  exit();
}

$message = '';
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $confirmPassword = $_POST['confirm_password'];
  $fullName = trim($_POST['full_name']);

  // Validation
  if (empty($username) || empty($email) || empty($password) || empty($confirmPassword)) {
    $message = 'Please fill in all required fields.';
  } elseif ($password !== $confirmPassword) {
    $message = 'Passwords do not match.';
  } elseif (strlen($password) < 6) {
    $message = 'Password must be at least 6 characters long.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $message = 'Please enter a valid email address.';
  } else {
    // Check if username or email already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    if ($stmt->fetch()) {
      $message = 'Username or email already exists.';
    } else {
      // Hash password and insert user
      $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
      if ($stmt->execute([$username, $email, $hashedPassword, $fullName])) {
        $success = true;
        $message = 'Registration successful! <a href="login.php">Click here to login</a>';
      } else {
        $message = 'Registration failed. Please try again.';
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - PhishGuard Scanner</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
    }

    .register-container {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .register-container h1 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #333;
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      color: #555;
    }

    .form-group input {
      width: 100%;
      padding: 0.75rem;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 1rem;
    }

    .btn {
      width: 100%;
      padding: 0.75rem;
      background: #2563eb;
      color: white;
      border: none;
      border-radius: 5px;
      font-size: 1rem;
      cursor: pointer;
      margin-top: 1rem;
    }

    .btn:hover {
      background: #1d4ed8;
    }

    .error {
      color: #dc2626;
      text-align: center;
      margin-bottom: 1rem;
    }

    .success {
      color: #16a34a;
      text-align: center;
      margin-bottom: 1rem;
    }

    .login-link {
      text-align: center;
      margin-top: 1rem;
    }

    .login-link a {
      color: #2563eb;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="register-container">
    <h1>Register for PhishGuard</h1>

    <?php if ($message): ?>
      <div class="<?php echo $success ? 'success' : 'error'; ?>">
        <?php echo $message; ?>
      </div>
    <?php endif; ?>

    <form method="POST" action="register.php">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="full_name">Full Name:</label>
        <input type="text" id="full_name" name="full_name">
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="confirm_password">Confirm Password:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
      </div>

      <button type="submit" class="btn">Register</button>
    </form>

    <div class="login-link">
      <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
  </div>
</body>

</html>