<?php

include "inc/loader.php";
$msg = "";

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $email = $_POST['email'];
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $email = filter_var($email, FILTER_VALIDATE_EMAIL);
    $Signup = new Signup();
    $result = $Signup->EmailIsTaken($email);

    if (!$result)
    {
        $msg = "E-mail is not being used";
    }
    else 
    {
        $Signup->sendPasswordResetEmail($email);
        $msg = "Password reset e-mail has been sent to " . $email;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>Minigamer | Forgot Password</title>
</head>
<body>

    <?php 

        if ($msg != "") 
        {
            echo $msg;
        }

    ?>

    <h1>Forgotten Password</h1>
    <form action="" method="POST">
        <input type="email" name="email">
        <input type='submit' value='Send e-mail'>
    </form>

</body>
</html>