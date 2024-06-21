<?php

include "inc/loader.php";
$msg = "";
$formHtml = "";
$DB = new DB();

$colors = ['#CD090F', '#ffcc00', '#00ff00', '#007fff'];
$selected_color = $colors[array_rand($colors)];

if (isset($_GET["k"]) && isset($_GET["email"]) && isset($_GET["action"]) && ($_GET["action"] == "reset") && !isset($_POST["action"])) {
    $key = $_GET['k'];
    $email = $_GET['email'];
    $curDate = date("Y-m-d H:i:s");

    $row = $DB->query("SELECT * FROM password_reset_temp WHERE `key`=? and `email`=? LIMIT 1", $key, $email)->fetchArray();

    if ($row == "") {
        $msg = "<h1>The link is invalid or expired!</h1> <p>Click the link to reset your password: <a href='forgot-password.php'>password recovery</a></p>";
    } else {
        $expDate = $row['expDate'];
        if ($expDate >= $curDate) {
            $formHtml = '<h1>Reset Password</h1>
                    <form action="" method="POST" class="form-container">
                        <input type="hidden" name="action" value="update" />
                        <input type="password" name="pass1" required placeholder="Enter New Password" class="input-field"/><br />
                        <input type="password" name="pass2" required placeholder="Re-Enter New Password" class="input-field"/><br />
                        <input type="hidden" name="email" value="' . htmlspecialchars($email) . '"/>
                        <input type="submit" value="Reset Password" class="submit-btn"/>
                    </form>';
        } else {
            $msg = "<h1>Link is expired!</h1> <p>The link is expired. They stay valid for 24 hours after request!</p>";
        }
    }
}

if (isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"] == "update")) {
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];
    $email = $_POST["email"];

    if ($pass1 != $pass2) {
        $msg .= "<p>Passwords do not match, both passwords should be the same.<br /><br /></p>";
        $formHtml = '<h1>Reset Password</h1>
                    <form action="" method="POST" class="form-container">
                        <input type="hidden" name="action" value="update" />
                        <input type="password" name="pass1" required placeholder="Enter New Password" class="input-field"/><br />
                        <input type="password" name="pass2" required placeholder="Re-Enter New Password" class="input-field"/><br />
                        <input type="hidden" name="email" value="' . htmlspecialchars($email) . '"/>
                        <input type="submit" value="Reset Password" class="submit-btn"/>
                    </form>';
    } else if (strlen($pass1) < 8) {
        $msg .= "<p>Password must be at least 8 characters long!</p>";
        $formHtml = '<h1>Reset Password</h1>
                    <form action="" method="POST" class="form-container">
                        <input type="hidden" name="action" value="update" />
                        <input type="password" name="pass1" required placeholder="Enter New Password" class="input-field"/><br />
                        <input type="password" name="pass2" required placeholder="Re-Enter New Password" class="input-field"/><br />
                        <input type="hidden" name="email" value="' . htmlspecialchars($email) . '"/>
                        <input type="submit" value="Reset Password" class="submit-btn"/>
                    </form>';
    } else {
        $hashedPassword = password_hash($pass1, PASSWORD_DEFAULT);
        $DB->query("UPDATE `users` SET `password`=? WHERE `email`=?", $hashedPassword, $email);
        $DB->query("DELETE FROM `password_reset_temp` WHERE `email`=?", $email);

        $msg = '<div class="success"><p>Congratulations! Your password has been updated successfully.</p>
                <p><a href="sign-in.php">Click here</a> to Login.</p></div><br />';
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="mlogo.png">
    <title>Minigamer | Password Reset</title>
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
        .success {
            background-color: #1e1e1e;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            width: 350px;
            margin-top: 100px;
        }
    </style>
</head>

<body>

<?php
if ($msg != "") {
    echo $msg;
}

if (!empty($formHtml)) {
    echo $formHtml;
}
?>

</body>
</html>
