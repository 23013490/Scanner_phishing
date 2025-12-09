<?php
// Database configuration
$host = 'localhost'; // Change this to your database host
$dbname = 'signature_scanner'; // Change this to your database name
$username = 'root'; // Change this to your database username
$password = 'Andrew@mysql'; // Change this to your database password

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  die("Connection failed: " . $e->getMessage());
}
