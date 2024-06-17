<?php

session_start();
include "inc/loader.php";

$Login = new Login();
$Login->checkRememberMe();

if (isset($_SESSION['user_id'])) 
{
    
    $DB = new DB();
    $result = $DB->query("SELECT verified FROM users WHERE id = ? LIMIT 1", $_SESSION['user_id']);
    $user = $result->fetchArray();

    if ($user['verified'] == 1) 
    {
        header("Location: index.php");
        exit();
    }

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>Verify Your Account!</title>
</head>
<body>

    <h1>Verify Your Account Before Proceeding!</h1>
    <p>A verification e-mail has been sent to your e-mail address, verify your account to proceed.</p>
    
</body>
</html>
