
<?php
session_start();

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

/**
 * Check if user is logged in
 */
function isLoggedIn()
{
  return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function getCurrentUserId()
{
  return $_SESSION['user_id'] ?? null;
}

/**
 * Get current username
 */
function getCurrentUsername()
{
  return $_SESSION['username'] ?? null;
}

/**
 * Login user by setting session variables
 */
function loginUser($userId, $username)
{
  $_SESSION['user_id'] = $userId;
  $_SESSION['username'] = $username;
  $_SESSION['login_time'] = time();

  // Update last login in database
  global $pdo;
  if ($pdo) {
    $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
    $stmt->execute([$userId]);
  }
}

/**
 * Logout user by destroying session
 */
function logoutUser()
{
  session_unset();
  session_destroy();
  session_start(); // Start new session
}

/**
 * Require login - redirect to login page if not logged in
 */
function requireLogin()
{
  if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
  }
}

/**
 * Get user data from database
 */
function getUserData()
{
  if (!isLoggedIn()) {
    return null;
  }

  global $pdo;
  if (!$pdo) {
    return null;
  }

  $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
  $stmt->execute([getCurrentUserId()]);
  return $stmt->fetch();
}

/**
 * Check if user has specific role/permission
 */
function hasPermission($permission)
{
  // For now, just check subscription plan
  $user = getUserData();
  if (!$user) {
    return false;
  }

  $planPermissions = [
    'free' => ['scan_basic'],
    'pro' => ['scan_basic', 'scan_advanced', 'api_access'],
    'enterprise' => ['scan_basic', 'scan_advanced', 'api_access', 'admin']
  ];

  return in_array($permission, $planPermissions[$user['subscription_plan']] ?? []);
}
