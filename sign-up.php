<?php

include "inc/loader.php";

$result = "";

$Login = new Login();
$Login->checkRememberMe();

if (isset($_SESSION['user_id']))
{
    $Login->checkVerification($_SESSION['user_id']);
}

if ($_SERVER['REQUEST_METHOD'] == "POST") 
{
    $Signup = new Signup();
    $result = $Signup->Evaluate($_POST);

    if ($result != "") echo $result;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>Sign Up Now!</title>
</head>
<body>

    <?php 
        if ($result != "") 
        {
            echo $result;
        }
    ?>

    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        
        <label for="passwordTwo">Confirm password:</label>
        <input type="password" id="passwordTwo" name="passwordTwo" required><br>
        
        <input type="submit" value="Sign up">
    </form>
</body>
</html>
