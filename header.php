<?php
// header.php — included at the top of every page that outputs HTML
if (session_status() === PHP_SESSION_NONE) { session_start(); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Pet Adoption</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Global stylesheet lives in styles.css -->
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<header class="site-header stack">
  <h1 class="site-title">Pet Adoption Portal</h1>
  <?php if (!empty($_SESSION['username'])): ?>
    <span class="welcome">Welcome, <?php echo h($_SESSION['display_name'] ?? $_SESSION['username']); ?></span>
  <?php endif; ?>
</header>
<main class="site-main">
