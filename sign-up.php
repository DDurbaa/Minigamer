<?php

include "inc/loader.php";

$result = "";

if ($_SERVER['REQUEST_METHOD'] == "POST") {
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
    <title>Sign-Up Now!</title>
</head>
<body>
    <form action="" method="POST">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required><br>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required><br>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        
        <label for="passwordTwo">Confirm password:</label>
        <input type="password" id="passwordTwo" name="passwordTwo" required><br>
        
        <input type="submit" value="Sign-up">
    </form>
</body>
</html>
