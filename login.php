<?php
/**
 * login.php
 * - Authenticates using 'authorized_users' table (SHA1 per spec)
 * - Redirects to home on success
 * - Hides page from already logged-in users
 */
require 'db.php';
if (!empty($_SESSION['username'])) { header('Location: home.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';

  $stmt = $pdo->prepare("SELECT username FROM authorized_users WHERE username = ? AND password = SHA1(?)");
  $stmt->execute([$username, $password]);
  if ($row = $stmt->fetch()) {
    $_SESSION['username'] = $row['username'];
    $_SESSION['display_name'] = $row['username'];
    header('Location: home.php');
    exit;
  } else {
    $error = 'Invalid username or password';
  }
}

include 'header.php';
?>
<h2 class="page-title">Login</h2>
<?php if ($error): ?><p class="alert alert-danger"><?php echo h($error); ?></p><?php endif; ?>

<form method="post" class="form card" autocomplete="off">
  <label for="username">Username</label>
  <input id="username" name="username" required maxlength="20">

  <label for="password">Password</label>
  <input id="password" name="password" type="password" required maxlength="50">

  <div class="form-actions">
    <button class="btn" type="submit">Login</button>
    <a class="btn btn-outline" href="home.php">Cancel</a>
  </div>
</form>
<?php include 'footer.php'; ?>
