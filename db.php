<?php
/**
 * db.php
 * - Central PDO connection and session starter
 * - Include this at the top of every page that needs DB access
 */

declare(strict_types=1);

// Show errors while developing (comment these 3 lines before submitting if required)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$host = '127.0.0.1';     // Use loopback IP to avoid socket issues
$port = 3306;            // Change to 3307 only if you changed MySQL port
$db   = 'requiredsql';   // Your database name
$user = 'webauth';       // DB user created in phpMyAdmin
$pass = 'webauth';       // DB user password

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";

try {
  $pdo = new PDO($dsn, $user, $pass, [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
  ]);
} catch (PDOException $e) {
  // TEMP for debugging: shows exact reason.
  // For final submission you can change to: die("Database connection failed.");
  die("DB connect error: " . $e->getMessage());
}

// Shared session start
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/** Small helpers **/
function h($v) { return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8'); }

/** Require login for protected pages */
function require_login(): void {
  if (empty($_SESSION['username'])) {
    header('Location: login.php');
    exit;
  }
}
?>