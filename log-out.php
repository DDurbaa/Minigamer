<?php
// Zahájení nebo obnovení relace
session_start();

// Zrušení všech relací
session_unset();

// Zničení relace
session_destroy();

$previous_page = $_SERVER['HTTP_REFERER'] ?? 'index.php';
header("Location: $previous_page");
exit();
?>
