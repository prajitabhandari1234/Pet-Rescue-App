<?php
/**
 * edit.php — Protected page to update an existing record
 */
require 'db.php';
require_login();

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

/* Load current record */
$stmt = $pdo->prepare("SELECT animalid, name, animal_type, adoption_fee, sex, desexed
                       FROM animal WHERE animalid = ?");
$stmt->execute([$id]);
$animal = $stmt->fetch();
if (!$animal) { header('Location: home.php'); exit; }

/* Dropdown options from DB */
$typeRows = $pdo->query("SELECT DISTINCT animal_type FROM animal ORDER BY animal_type")->fetchAll();
$types = array_map(fn($r) => $r['animal_type'], $typeRows);
if (empty($types)) { $types = ['Dog','Cat','Bird']; }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name       = trim($_POST['animal-name'] ?? '');
  $animalType = trim($_POST['animal-type'] ?? '');
  $adoption   = trim($_POST['adoption-fee'] ?? '');
  $sex        = trim($_POST['sex'] ?? '');
  $desexed    = trim($_POST['desexed'] ?? '');

  // Basic validation
  if ($name === '' || mb_strlen($name) > 100)      { $errors[] = "Name is required (max 100)."; }
  if ($animalType === '' || mb_strlen($animalType) > 50) { $errors[] = "Animal type is required (max 50)."; }
  if ($sex !== 'Male' && $sex !== 'Female')        { $errors[] = "Sex must be Male or Female."; }
  if ($desexed !== 'Yes' && $desexed !== 'No')     { $errors[] = "Desexed must be Yes or No."; }
  if ($adoption === '' || filter_var($adoption, FILTER_VALIDATE_INT, ['options'=>['min_range'=>0]]) === false) {
    $errors[] = "Adoption fee must be a whole number (0 or more).";
  }

  if (!$errors) {
    $upd = $pdo->prepare("UPDATE animal
                          SET name=?, animal_type=?, adoption_fee=?, sex=?, desexed=?
                          WHERE animalid=?");
    $upd->execute([$name, $animalType, (int)$adoption, $sex, ($desexed === 'Yes') ? 1 : 0, $id]);

    // One-time success message for home.php
    $_SESSION['flash'] = " Updated “{$name}” successfully.";
    header('Location: home.php');
    exit;
  } else {
    // Rebind user-entered values back into $animal for the form
    $animal['name']         = $name;
    $animal['animal_type']  = $animalType;
    $animal['adoption_fee'] = (int)$adoption;
    $animal['sex']          = $sex;
    $animal['desexed']      = ($desexed === 'Yes');
  }
}

include 'header.php';
?>
<h2 class="page-title">Edit Animal</h2>

<?php if ($errors): ?>
  <ul class="alert alert-danger">
    <?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?>
  </ul>
<?php endif; ?>

<form method="post" class="form card" autocomplete="off">
  <label for="animal-name">Name</label>
  <input id="animal-name" name="animal-name" value="<?= h($animal['name']) ?>" maxlength="100" required>

  <label for="animal-type">Type</label>
  <select id="animal-type" name="animal-type" required>
    <?php foreach ($types as $t): ?>
      <option value="<?= h($t) ?>" <?= ($t === $animal['animal_type']) ? 'selected' : '' ?>>
        <?= h($t) ?>
      </option>
    <?php endforeach; ?>
  </select>

  <label for="adoption-fee">Adoption Fee</label>
  <input id="adoption-fee" name="adoption-fee" type="number" min="0" step="1"
         value="<?= (int)$animal['adoption_fee'] ?>" required>

  <label for="sex">Sex</label>
  <select id="sex" name="sex" required>
    <option <?= ($animal['sex']==='Male') ? 'selected' : '' ?>>Male</option>
    <option <?= ($animal['sex']==='Female') ? 'selected' : '' ?>>Female</option>
  </select>

  <label for="desexed">Desexed?</label>
  <select id="desexed" name="desexed" required>
    <option <?= $animal['desexed'] ? 'selected' : '' ?>>Yes</option>
    <option <?= !$animal['desexed'] ? 'selected' : '' ?>>No</option>
  </select>

  <div class="form-actions">
    <button class="btn" type="submit">Update</button>
    <a class="btn btn-outline" href="home.php">Cancel</a>
  </div>
</form>
<?php include 'footer.php'; ?>
