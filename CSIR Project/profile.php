<?php
require_once 'db.php';
require_once 'session.php';
requireLogin();

$user = getUserData();
if (!$user) {
  logoutUser();
  header('Location: login.php');
  exit();
}

// Handle profile update
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $fullName = trim($_POST['full_name'] ?? '');
  $email = trim($_POST['email'] ?? '');

  if (empty($email)) {
    $message = 'Email is required.';
  } else {
    // Check if email is already taken by another user
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $stmt->execute([$email, $user['id']]);
    if ($stmt->fetch()) {
      $message = 'Email is already taken.';
    } else {
      // Update profile
      $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
      $stmt->execute([$fullName, $email, $user['id']]);
      $message = 'Profile updated successfully!';
      $user = getUserData(); // Refresh user data
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>User Profile - PhishGuard Scanner</title>
  <style>
    :root {
      --primary: #2563eb;
      --primary-dark: #1d4ed8;
      --primary-light: #3b82f6;
      --danger: #dc2626;
      --success: #16a34a;
      --warning: #f59e0b;
      --bg: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --card: #ffffff;
      --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
      --shadow-hover: 0 15px 35px rgba(0, 0, 0, 0.15);
    }

    * {
      box-sizing: border-box;
      font-family: "Segoe UI", system-ui, sans-serif;
    }

    body {
      background: var(--bg);
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      margin: 0;
      padding: 20px;
      position: relative;
    }

    body::before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      z-index: -1;
    }

    .profile-container {
      background: var(--card);
      padding: 2.5rem;
      border-radius: 16px;
      box-shadow: var(--shadow);
      width: 100%;
      max-width: 650px;
      transition: all 0.3s ease;
      border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .profile-container:hover {
      box-shadow: var(--shadow-hover);
      transform: translateY(-2px);
    }

    .nav-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      padding-bottom: 1.5rem;
      border-bottom: 2px solid #e2e8f0;
    }

    .nav-header h2 {
      margin: 0;
      color: #1e293b;
      font-size: 1.2rem;
    }

    .header-buttons {
      display: flex;
      gap: 10px;
    }

    .header-buttons button {
      padding: 8px 16px;
      font-size: 0.85rem;
      width: auto;
      max-width: 120px;
      background: linear-gradient(135deg,
          var(--primary) 0%,
          var(--primary-light) 100%);
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      transition: all 0.3s ease;
      box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
    }

    .header-buttons button:hover {
      transform: translateY(-2px);
      box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .logout-btn {
      background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
      box-shadow: 0 2px 8px rgba(220, 38, 38, 0.2) !important;
    }

    .logout-btn:hover {
      box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3) !important;
    }

    .user-info {
      background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
      border: 2px solid #bbf7d0;
      padding: 1.5rem;
      border-radius: 12px;
      margin-bottom: 1.5rem;
    }

    .user-info h3 {
      margin: 0 0 1rem 0;
      color: var(--success);
      font-size: 1.1rem;
    }

    .user-info-item {
      margin: 0.75rem 0;
      color: #1e293b;
    }

    .user-info-item strong {
      color: var(--success);
    }

    .settings-section {
      margin-bottom: 2rem;
    }

    .settings-section h3 {
      color: #1e293b;
      font-size: 1.1rem;
      margin-bottom: 1rem;
    }

    .settings-section p {
      color: #64748b;
      margin-bottom: 1.5rem;
      line-height: 1.5;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-group label {
      display: block;
      margin-bottom: 0.5rem;
      color: #374151;
      font-weight: 600;
    }

    .form-group input {
      width: 100%;
      padding: 12px 16px;
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
      background: #f9fafb;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
      box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
    }

    .action-buttons {
      display: flex;
      gap: 15px;
      justify-content: flex-end;
    }

    .action-buttons button {
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: 600;
      font-size: 0.95rem;
      transition: all 0.3s ease;
    }

    .btn-primary {
      background: linear-gradient(135deg,
          var(--primary) 0%,
          var(--primary-light) 100%);
      color: white;
      box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
    }

    .btn-primary:hover {
      transform: translateY(-2px);
      box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
    }

    .btn-secondary {
      background: #f3f4f6;
      color: #374151;
      border: 2px solid #d1d5db;
    }

    .btn-secondary:hover {
      background: #e5e7eb;
      border-color: #9ca3af;
    }

    .message {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 1.5rem;
      font-weight: 500;
    }

    .message.success {
      background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
      border: 2px solid #bbf7d0;
      color: var(--success);
    }

    .message.error {
      background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
      border: 2px solid #fecaca;
      color: var(--danger);
    }

    footer {
      text-align: center;
      padding: 1.5rem;
      color: #64748b;
      font-size: 0.85rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      margin-top: 2rem;
    }
  </style>
</head>

<body>
  <div class="profile-container">
    <div class="nav-header">
      <h2>User Profile</h2>
      <div class="header-buttons">
        <button onclick="window.location.href='index.php'">Scanner</button>
        <button onclick="window.location.href='logout.php'" class="logout-btn">Logout</button>
      </div>
    </div>

    <?php if ($message): ?>
      <div class="message <?php echo strpos($message, 'successfully') !== false ? 'success' : 'error'; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <div class="user-info">
      <h3>Account Information</h3>
      <div class="user-info-item">
        <strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?>
      </div>
      <div class="user-info-item">
        <strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?>
      </div>
      <div class="user-info-item">
        <strong>Full Name:</strong> <?php echo htmlspecialchars($user['full_name'] ?: 'Not set'); ?>
      </div>
      <div class="user-info-item">
        <strong>Subscription:</strong> <?php echo htmlspecialchars(ucfirst($user['subscription_plan'])); ?>
      </div>
      <div class="user-info-item">
        <strong>Member Since:</strong> <?php echo date('F j, Y', strtotime($user['created_at'])); ?>
      </div>
      <?php if ($user['last_login']): ?>
        <div class="user-info-item">
          <strong>Last Login:</strong> <?php echo date('F j, Y g:i A', strtotime($user['last_login'])); ?>
        </div>
      <?php endif; ?>
    </div>

    <div class="settings-section">
      <h3>Update Profile</h3>
      <p>Update your account information below. Changes will be saved immediately.</p>

      <form method="POST" action="profile.php">
        <div class="form-group">
          <label for="full_name">Full Name:</label>
          <input type="text" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>">
        </div>

        <div class="form-group">
          <label for="email">Email Address:</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
        </div>

        <div class="action-buttons">
          <button type="button" class="btn-secondary" onclick="window.location.href='index.php'">Cancel</button>
          <button type="submit" class="btn-primary">Update Profile</button>
        </div>
      </form>
    </div>
  </div>

  <footer>
    <p>
      &copy; 2025 PhishGuard Scanner. All rights reserved. |
      <a href="#" style="color: #64748b; text-decoration: none">Privacy Policy</a> |
      <a href="#" style="color: #64748b; text-decoration: none">Terms of Service</a>
    </p>
  </footer>
</body>

</html>