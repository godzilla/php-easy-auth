<?php
require_once "easyauth.php";

if(!isLoggedIn()){
    header("location: login.php?return_page=authtest.php");
} else {
    echo "<pre>"; print_r($_SESSION); echo "</pre>";
}

echo "authenticated<br>";
