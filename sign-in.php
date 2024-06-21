<?php 
include "inc/loader.php";

$msg = "";
$section = isset($_GET['section']) ? $_GET['section'] : '';

// Define an array of colors
$colors = ['#CD090F', '#ffcc00', '#00ff00', '#007fff'];

// Randomly select a color
$selected_color = $colors[array_rand($colors)];

$form = "
<form action='?section=check_email' method='post' class='form-container'>
    <input type='email' name='email' required placeholder='E-mail' class='input-field'>
    <input type='submit' value='Continue with E-mail' class='submit-btn'>
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
    $email = $_POST['email'];
    $form = "
    <form action='?section=login' method='post' class='form-container'>
        <input type='email' name='email' value='$email' readonly class='input-field'>
        <input type='hidden' name='email' value='$email'>
        <input type='password' name='password' required placeholder='Password' class='input-field'>
        <a href='forgot-password.php' class='forgot-password-link'>Forgot your password?</a>
        <label class='checkbox-label'>
            <input type='checkbox' name='remember_me' class='checkbox-input'>
            <span class='checkbox-custom'></span>
            Remember Me
        </label>
        <input type='submit' value='Login' class='submit-btn'>
    </form>";
} elseif ($section == 'register' && isset($_POST['email'])) {
    $email = $_POST['email'];
    $form = "
    <form action='?section=register' method='post' class='form-container'>
        <input type='text' id='username' name='username' placeholder='Username' required class='input-field'><br>
        <input type='email' name='email' value='$email' readonly class='input-field'>
        <input type='hidden' name='email' value='$email'>
        <input type='password' id='password' name='password' placeholder='Must have at least 8 characters' required class='input-field'><br>
        <input type='password' id='passwordTwo' name='passwordTwo' placeholder='Password again' required class='input-field'><br>
        <input type='submit' value='Sign up' class='submit-btn'>
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
    <style>
        body {
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .logo-container {
            position: absolute;
            top: 20px;
        }
        .form-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px;
            margin-top: 100px; /* To create space between the logo and form */
        }
        .input-field {
            width: 100%;
            padding: 15px;
            margin: 10px 0;
            border: 1px solid #333;
            border-radius: 4px;
            background-color: #2a2a2a;
            color: #ffffff;
            box-sizing: border-box;
            font-size: 18px;
            outline: none;
            transition: border-color 0.3s;
        }
        .input-field:focus {
            border-color: <?php echo $selected_color; ?>;
        }
        .submit-btn {
            background-color: #2a2a2a;
            width: 100%;
            padding: 15px;
            border: none;
            border-radius: 4px;
            border: 2px solid <?php echo $selected_color; ?>;
            text-decoration: none;
            color: white;
            font-weight: bold;
            cursor: pointer;
            box-sizing: border-box;
            font-size: 18px;
            transition: background-color 0.3s, color 0.3s;
            margin-top: 10px;
        }
        .submit-btn:hover {
            background-color: <?php echo $selected_color; ?>;
            color: #333;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            margin: 10px 0 20px 0; /* Increased margin below */
            font-size: 16px;
            position: relative;
            cursor: pointer;
            user-select: none;
        }
        .checkbox-input {
            display: none;
        }
        .checkbox-custom {
            width: 20px;
            height: 20px;
            background-color: #2a2a2a;
            border: 2px solid <?php echo $selected_color; ?>;
            border-radius: 4px;
            margin-right: 10px;
            transition: background-color 0.3s, border-color 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }
        .checkbox-input:checked + .checkbox-custom {
            background-color: <?php echo $selected_color; ?>;
            border-color: <?php echo $selected_color; ?>;
        }
        .checkbox-input:checked + .checkbox-custom::after {
            content: "";
            display: block;
            width: 6px;
            height: 10px;
            border: solid #333;
            border-width: 0 2px 2px 0;
            transform: rotate(45deg);
            position: absolute;
            top: 3px;
            left: 5px;
        }
        .forgot-password-link {
            color: <?php echo $selected_color; ?>;
            text-decoration: none;
            display: block;
            margin-top: 10px;
            margin-bottom: 20px; /* Added margin to create space below */
        }
        .forgot-password-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="logo-container">
    <img src="mlogo.png" alt="Logo" class="logo">
</div>

<?php 
    if ($msg != "") 
    {
        echo $msg;
    }

    echo $form;
?>

</body>
</html>
