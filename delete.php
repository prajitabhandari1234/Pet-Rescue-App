<?php
/**
 * delete.php — Protected page to confirm & delete a record
 */
require 'db.php'; require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* Load record to confirm deletion */
$stmt = $pdo->prepare("SELECT animalid, name, animal_type, adoption_fee, sex, desexed FROM animal WHERE animalid = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();
if (!$animal) { header('Location: home.php'); exit; }

$deleted = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['confirm'] ?? '') === 'yes') {
  $del = $pdo->prepare("DELETE FROM animal WHERE animalid = ?");
  $del->execute([$id]);
  $deleted = true;
}

include 'header.php';
?>
<h2 class="page-title">Delete Animal</h2>

<?php if ($deleted): ?>
  <p class="alert alert-success">Animal deleted successfully.</p>
  <p><a class="btn" href="home.php">Return Home</a></p>
<?php else: ?>
  <p class="alert alert-warning">Are you sure you want to delete this record?</p>
  <table class="data-table">
    <tr><th>Name</th><td><?php echo h($animal['name']); ?></td></tr>
    <tr><th>Animal Type</th><td><?php echo h($animal['animal_type']); ?></td></tr>
    <tr><th>Adoption Fee</th><td><?php echo '$' . number_format((float)$animal['adoption_fee'], 2); ?></td></tr>
    <tr><th>Sex</th><td><?php echo h($animal['sex']); ?></td></tr>
    <tr><th>Desexed?</th><td><?php echo $animal['desexed'] ? 'Yes' : 'No'; ?></td></tr>
  </table>

  <form method="post" class="stack" style="margin-top:12px">
    <input type="hidden" name="confirm" value="yes">
    <button class="btn btn-danger" type="submit">Delete</button>
    <a class="btn btn-outline" href="home.php">Cancel</a>
  </form>
<?php endif; ?>

<?php include 'footer.php'; ?>
