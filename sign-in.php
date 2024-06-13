<?php 
include "inc/loader.php";

session_start();

$msg = "";
$section = isset($_GET['section']) ? $_GET['section'] : '';


// STYLY PRO UVODNI FORMULAR KDE SE ZADA EMAIL
$form = "
<form action='?section=check_email' method='post'>
    <input type='email' name='email' required placeholder='Email'>
    <input type='submit' value='Continue with Email'>
</form>";

if ($_SERVER['REQUEST_METHOD'] == "POST")
{
    $Signup = new Signup();
    $email = $_POST['email'];
    
    if ($section == 'check_email') 
    {
        $result = $Signup->EmailIsTaken($email);
        if ($result)
        {
            $section = 'login';
        }
        else 
        {
            $section = 'register';
        }
    }
    elseif ($section == 'login') 
    {
        // Přihlášení
        $Login = new Login();
        $msg = $Login->Evaluate($_POST);

        if ($msg == "") 
        {
            header("Location: index.php");
            exit(); 
        }

    }
    elseif ($section == 'register') 
    {
        // Registrace
        $Signup = new Signup();
        $msg = $Signup->Evaluate($_POST);
        
        if ($msg == "") 
        {
            header("Location: index.php");
            exit(); 
        }

    }
}

if ($section == 'login' && isset($_POST['email'])) {
    // STYLY PRO FORMULAR KDYZ SE UZIVATEL JEN PRIHLASUJE (EMAIL JE ZNAMEJ)
    $email = $_POST['email'];
    $form = "
    <form action='?section=login' method='post'>
        <input type='email' name='email' value='$email' readonly>
        <input type='hidden' name='email' value='$email'>
        <input type='password' name='password' required placeholder='Password'>
        <label>
            <input type='checkbox' name='remember_me'> Remember Me
        </label>
        <input type='submit' value='Login'>
    </form>";
} elseif ($section == 'register' && isset($_POST['email'])) {
    // STYLY PRO FORMULAR KDYZ SE REGISTRUJE (EMAIL NEZNAM)
    $email = $_POST['email'];
    $form = "
    <form action='?section=register' method='post'>
        <label for='username'>Username:</label>
        <input type='text' id='username' name='username' required><br>
        <input type='email' name='email' value='$email' readonly>
        <input type='hidden' name='email' value='$email'>
        <label for='password'>Password:</label>
        <input type='password' id='password' name='password' required><br>
        <label for='passwordTwo'>Confirm password:</label>
        <input type='password' id='passwordTwo' name='passwordTwo' required><br>
        <input type='submit' value='Sign up'>
    </form>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>Sign In Now!</title>
</head>
<body>

<?php 
    if ($msg != "") 
    {
        echo $msg;
    }

    echo $form;
?>

</body>
</html>
