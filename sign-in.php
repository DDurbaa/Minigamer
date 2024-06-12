<?php 

    include "inc/loader.php";

    $msg = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST")
    {
        $Login = new Login();
        $msg = $Login->Evaluate($_POST);

        if ($msg != "") echo $msg;
        else 
        {
            session_start();
            $_SESSION['user_id'] = $result['id'];
            $_SESSION['username'] = $result['username'];
            header("Location: index.php");
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In Now!</title>
</head>
<body>
<form action="" method="post">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Password">
    <label>
        <input type="checkbox" name="remember_me"> Remember Me
    </label>
    <input type="submit" value="Login">
</form>
</body>
</html>
