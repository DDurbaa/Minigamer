<?php
session_start();

// Include necessary files
include "inc/loader.php";

// Destroy the remember_me token in the database if the user is logged in
if (isset($_SESSION['user_id'])) {
    $DB = new DB();
    $DB->query("UPDATE users SET remember_me_token = NULL WHERE id = ?", $_SESSION['user_id']);
}

// Clear the remember_me cookie
setcookie('remember_me', '', time() - 3600, "/");

// Clear session variables
$_SESSION = array();

// Destroy the session
session_unset();
session_destroy();


$previous_page = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $previous_page");
exit();
?>
