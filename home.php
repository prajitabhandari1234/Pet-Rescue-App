<?php
/**
 * home.php
 * - Public landing page listing animals
 * - Logged-in users also see Edit / Delete actions
 * - Server-side filter by animal type (no JS)
 */
require 'db.php';

/* Flash message (from edit/add/delete) */
$flash = $_SESSION['flash'] ?? '';
if ($flash !== '') { unset($_SESSION['flash']); }

/* Get dropdown types dynamically from DB (no hardcoding) */
$typeRows = $pdo->query("SELECT DISTINCT animal_type FROM animal ORDER BY animal_type")->fetchAll();
$types = array_map(fn($r) => $r['animal_type'], $typeRows);

/* Apply chosen filter (if any) */
$selectedType = trim($_GET['type'] ?? '');
$params = [];
$sql = "SELECT animalid, name, animal_type, adoption_fee, sex, desexed FROM animal";
if ($selectedType !== '') { $sql .= " WHERE animal_type = ?"; $params[] = $selectedType; }
$sql .= " ORDER BY animal_type ASC, name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$animals = $stmt->fetchAll();

/* Refresh display_name for welcome text from DB (not hardcoded) */
if (!empty($_SESSION['username'])) {
  $usr = $pdo->prepare("SELECT username FROM authorized_users WHERE username = ?");
  $usr->execute([$_SESSION['username']]);
  if ($u = $usr->fetch()) $_SESSION['display_name'] = $u['username'];
}

include 'header.php';
?>

<?php if ($flash): ?>
  <p class="alert alert-success"><?= h($flash) ?></p>
<?php endif; ?>

<h2 class="page-title">Animals Available for Adoption</h2>

<form method="get" class="filter-bar stack" aria-label="Filter by animal type">
  <label for="type">Animal type</label>
  <select id="type" name="type">
    <option value="">All</option>
    <?php foreach ($types as $t): ?>
      <option value="<?= h($t) ?>" <?= ($t === $selectedType) ? 'selected' : '' ?>>
        <?= h($t) ?>
      </option>
    <?php endforeach; ?>
  </select>
  <button class="btn" type="submit">Apply Filter</button>
  <?php if ($selectedType !== ''): ?>
    <a class="btn btn-outline" href="home.php">Clear</a>
  <?php endif; ?>
</form>

<table class="data-table">
  <thead>
    <tr>
      <th>Name</th>
      <th>Animal Type</th>
      <th>Adoption Fee</th>
      <th>Sex</th>
      <th>Desexed?</th>
      <?php if (!empty($_SESSION['username'])): ?>
        <th></th><th></th>
      <?php endif; ?>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($animals as $a): ?>
      <tr>
        <td><?= h($a['name']) ?></td>
        <td><?= h($a['animal_type']) ?></td>
        <td><?= '$' . number_format((float)$a['adoption_fee'], 2) ?></td>
        <td><?= h($a['sex']) ?></td>
        <td><?= $a['desexed'] ? 'Yes' : 'No' ?></td>
        <?php if (!empty($_SESSION['username'])): ?>
          <td class="actions"><a class="btn btn-small" href="edit.php?id=<?= (int)$a['animalid'] ?>">Edit</a></td>
          <td class="actions"><a class="btn btn-small btn-danger" href="delete.php?id=<?= (int)$a['animalid'] ?>">Delete</a></td>
        <?php endif; ?>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php include 'footer.php'; ?>
