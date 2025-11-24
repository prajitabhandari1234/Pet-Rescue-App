<?php
require 'db.php';
$row = $pdo->query("SELECT DATABASE() AS db")->fetch();
echo "Connected to database: " . htmlspecialchars($row['db']);
?>