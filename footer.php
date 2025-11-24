  </main>
  <footer class="site-footer stack">
    <?php if (!empty($_SESSION['username'])): ?>
      <a class="btn" href="home.php">Home</a>
      <a class="btn" href="add.php">Add New Animal</a>
      <a class="btn btn-outline" href="logout.php">Logout</a>
    <?php else: ?>
      <a class="btn" href="home.php">Home</a>
      <a class="btn btn-outline" href="login.php">Login</a>
    <?php endif; ?>
  </footer>
</body>
</html>
