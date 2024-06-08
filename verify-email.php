<?php 

include "inc/loader.php";

if (isset($_GET['t'])) 
{
    $token = $_GET['t'];
    $DB = new DB();
    $DB->query("SELECT * FROM users WHERE token = ?", $token);
    
    if ($DB->numRows() > 0) 
    {
        $DB->query("UPDATE users SET verified = 1 WHERE token = ?", $token);
        echo "Email verified successfully!";
    } 
    else 
    {
        echo "Invalid token!";
    }
} 
else 
{
    header("Location: index.html");
}


?>