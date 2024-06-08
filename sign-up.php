<?php
// Include the necessary classes
include "inc/loader.php";

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
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        form {
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 300px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px;
            border: none;
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border-radius: 3px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
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
