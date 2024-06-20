<?php

include "inc/loader.php";
$msg = "";
$formHtml = ""; // Přidána proměnná pro uchování HTML formuláře
$DB = new DB(); // Inicializace objektu DB mimo podmínky pro reset hesla

if (isset($_GET["k"]) && isset($_GET["email"]) && isset($_GET["action"]) && ($_GET["action"] == "reset") && !isset($_POST["action"])) {
    $key = $_GET['k'];
    $email = $_GET['email'];
    $curDate = date("Y-m-d H:i:s");

    // Pokud se provede reset hesla, je třeba inicializovat objekt DB
    $DB = new DB();

    $row = $DB->query("SELECT * FROM password_reset_temp WHERE `key`=? and `email`=? LIMIT 1", $key, $email)->fetchArray();

    if ($row == "") {
        $msg = "<h1>The link is invalid or expired!</h1> <p>Click the link to reset your password: <a href='forgot-password.php'>password recovery</a></p>";
    } else {
        $expDate = $row['expDate'];
        if ($expDate >= $curDate) {
            $formHtml = '<h1>Reset Password</h1>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="update" />
                        <label>Enter New Password:</label><br />
                        <input type="password" name="pass1" required /><br /><br />
                        <label>Re-Enter New Password:</label><br />
                        <input type="password" name="pass2" required/><br /><br />
                        <input type="hidden" name="email" value="' . htmlspecialchars($email) . '"/>
                        <input type="submit" value="Reset Password" />
                    </form>';
        } else {
            $msg = "<h1>Link is expired!</h1> <p>The link is expired. They stay valid for 24 hours after request!</p>";
        }
    }
} else {
    //header("Location: index.php");
}

if (isset($_POST["email"]) && isset($_POST["action"]) && ($_POST["action"] == "update")) {
    $pass1 = $_POST["pass1"];
    $pass2 = $_POST["pass2"];
    $email = $_POST["email"];

    if ($pass1 != $pass2) {
        $msg .= "<p>Passwords do not match, both passwords should be the same.<br /><br /></p>";
        $formHtml = '<h1>Reset Password</h1>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="update" />
                        <label>Enter New Password:</label><br />
                        <input type="password" name="pass1" required /><br /><br />
                        <label>Re-Enter New Password:</label><br />
                        <input type="password" name="pass2" required/><br /><br />
                        <input type="hidden" name="email" value="' . htmlspecialchars($email) . '"/>
                        <input type="submit" value="Reset Password" />
                    </form>';
    } else if (strlen($pass1) < 8) {
        $msg .= "<p>Password must be at least 8 characters long!</p>";
        $formHtml = '<h1>Reset Password</h1>
                    <form action="" method="POST">
                        <input type="hidden" name="action" value="update" />
                        <label>Enter New Password:</label><br />
                        <input type="password" name="pass1" required /><br /><br />
                        <label>Re-Enter New Password:</label><br />
                        <input type="password" name="pass2" required/><br /><br />
                        <input type="hidden" name="email" value="' . htmlspecialchars($email) . '"/>
                        <input type="submit" value="Reset Password" />
                    </form>';
    } else {
        $hashedPassword = password_hash($pass1, PASSWORD_DEFAULT);
        $DB->query("UPDATE `users` SET `password`=? WHERE `email`=?", $hashedPassword, $email);
        $DB->query("DELETE FROM `password_reset_temp` WHERE `email`=?", $email);

        $msg = '<div class="success"><p>Congratulations! Your password has been updated successfully.</p>
                <p><a href="sign-in.php">Click here</a> to Login.</p></div><br />';
        // Není třeba nastavovat $formHtml, protože formulář již není potřeba zobrazovat po úspěšném resetování hesla.
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
</head>

<body>

    <?php
    if ($msg != "") {
        echo $msg;
    }
    
    // Zobrazit formulář, pokud je $formHtml nastavený
    if (!empty($formHtml)) {
        echo $formHtml;
    }
    ?>

</body>
</html>
