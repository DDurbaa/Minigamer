<?php

    require_once "db.class.php";
    require_once "signup.class.php";
    require_once "login.class.php";

    $Login = new Login();
    $Login->checkRememberMe();