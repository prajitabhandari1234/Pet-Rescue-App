<?php
/**
 * add.php — Protected page to add a new animal
 */
require 'db.php'; require_login();

/* Build dropdown options dynamically */
$typeRows = $pdo->query("SELECT DISTINCT animal_type FROM animal ORDER BY animal_type")->fetchAll();
$types = array_map(fn($r) => $r['animal_type'], $typeRows);
if (empty($types)) { $types = ['Dog','Cat','Bird']; } // fallback

$errors = []; $ok = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name       = trim($_POST['animal-name'] ?? '');
  $animalType = trim($_POST['animal-type'] ?? '');
  $adoption   = trim($_POST['adoption-fee'] ?? '');
  $sex        = trim($_POST['sex'] ?? '');
  $desexed    = trim($_POST['desexed'] ?? '');

  // Basic validation
  if ($name === '' || strlen($name) > 100) $errors[] = "Name is required (max 100).";
  if ($animalType === '' || strlen($animalType) > 50) $errors[] = "Animal type is required (max 50).";
  if ($sex !== 'Male' && $sex !== 'Female') $errors[] = "Sex must be Male or Female.";
  if ($desexed !== 'Yes' && $desexed !== 'No') $errors[] = "Desexed must be Yes or No.";
  if ($adoption === '' || !ctype_digit($adoption)) $errors[] = "Adoption fee must be a whole number.";

  if (!$errors) {
    $stmt = $pdo->prepare("INSERT INTO animal (name, animal_type, adoption_fee, sex, desexed) VALUES (?,?,?,?,?)");
    $stmt->execute([$name, $animalType, (int)$adoption, $sex, $desexed === 'Yes']);
    $ok = true;
  }
}

include 'header.php';
?>
<h2 class="page-title">Add New Animal</h2>

<?php if ($ok): ?>
  <p class="alert alert-success">Animal added successfully.</p>
  <p><a class="btn" href="home.php">Return Home</a></p>
<?php else: ?>
  <?php if ($errors): ?>
    <ul class="alert alert-danger"><?php foreach ($errors as $e) echo '<li>'.h($e).'</li>'; ?></ul>
  <?php endif; ?>

  <form method="post" class="form card" autocomplete="off">
    <label for="animal-name">Name</label>
    <input id="animal-name" name="animal-name" maxlength="100" required>

    <label for="animal-type">Type</label>
    <select id="animal-type" name="animal-type" required>
      <?php foreach ($types as $t): ?>
        <option value="<?php echo h($t); ?>"><?php echo h($t); ?></option>
      <?php endforeach; ?>
    </select>

    <label for="adoption-fee">Adoption Fee</label>
    <input id="adoption-fee" name="adoption-fee" type="number" min="0" step="1" required>

    <label for="sex">Sex</label>
    <select id="sex" name="sex" required>
      <option>Male</option><option>Female</option>
    </select>

    <label for="desexed">Desexed?</label>
    <select id="desexed" name="desexed" required>
      <option>Yes</option><option>No</option>
    </select>

    <div class="form-actions">
      <button class="btn" type="submit">Add</button>
      <a class="btn btn-outline" href="home.php">Cancel</a>
    </div>
  </form>
<?php endif; ?>
<?php include 'footer.php'; ?>
