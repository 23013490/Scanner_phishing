<input type="text" id="username" name="username" placeholder="Username" required>
<input type="password" id="password" name="password" placeholder="Password" required>
<button type="submit">Login</button>
</form>

<p id="login-error" style="color:red;"></p>

<?php
// Set your valid login details
$validUsername = "admin";
$validPassword = "12345";

// Check if form submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


  $username = trim($_POST["username"]);
  $password = $_POST["password"];

  if ($username === $validUsername && $password === $validPassword) {
    // Successful login
    // You can redirect or load another page
    header("Location: scanner.php");
    exit();
  } else {
    // Failed login
    echo "<p style='color:red;'>Invalid username or password.</p>";
  }
}
?>
=======
<?php
require_once 'db.php';
require_once 'session.php';

// Check if user is already logged in
if (isLoggedIn()) {
  header('Location: index.php');
  exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username']);
  $password = $_POST['password'];

  if (empty($username) || empty($password)) {
    $error = 'Please fill in all fields.';
  } else {
    // Check credentials against database
    $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ? AND is_active = 1");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
      loginUser($user['id'], $user['username']);
      header('Location: index.php');
      exit();
    } else {
      $error = 'Invalid username or password.';
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - PhishGuard Scanner</title>
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

    .login-container {
      background: white;
      padding: 2rem;
      border-radius: 10px;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      width: 100%;
      max-width: 400px;
    }

    .login-container h1 {
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

    .register-link {
      text-align: center;
      margin-top: 1rem;
    }

    .register-link a {
      color: #2563eb;
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="login-container">
    <h1>Login to PhishGuard</h1>

    <?php if ($error): ?>
      <div class="error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
      <div class="form-group">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
      </div>

      <div class="form-group">
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
      </div>

      <button type="submit" class="btn">Login</button>
    </form>

    <div class="register-link">
      <p>Don't have an account? <a href="register.php">Register here</a></p>
    </div>
  </div>
</body>

</html>