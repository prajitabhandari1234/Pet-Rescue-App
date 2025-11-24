<?php
/**
 * logout.php — clears the session and returns to home
 */
require 'db.php';
session_unset();
session_destroy();
header('Location: home.php');
exit;
