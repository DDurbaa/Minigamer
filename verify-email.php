<?php 

include "inc/loader.php";
$msg = "";

if (isset($_GET['t'])) 
{
    $token = $_GET['t'];
    $DB = new DB();
    $DB->query("SELECT * FROM users WHERE token = ?", $token);
    
    if ($DB->numRows() > 0) 
    {
        $DB->query("UPDATE users SET verified = 1 WHERE token = ?", $token);
        $msg =  "Email verified successfully!";
    } 
    else 
    {
        $msg = "Invalid token!";
    }
} 
else 
{
    header("Location: index.html");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
</head>
<body>

    <?php 
        if ($msg != "")
        {
            echo $msg;
        }
    ?>
    
</body>
</html>
