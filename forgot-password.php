<?php
include "inc/loader.php";
$msg = "";

$colors = ['#CD090F', '#ffcc00', '#00ff00', '#007fff'];
$selected_color = $colors[array_rand($colors)];

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
        .form-container {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px;
            margin-top: 100px;
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
    </style>
</head>
<body>

<?php 
    if ($msg != "") 
    {
        echo $msg;
    }
?>

<h1>Forgotten Password</h1>
<form action="" method="POST" class="form-container">
    <input type="email" name="email" required placeholder="E-mail" class="input-field">
    <input type="submit" value="Send e-mail" class="submit-btn">
</form>

</body>
</html>
