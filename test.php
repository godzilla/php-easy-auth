<?php

require_once "easyauth.php";


if(!isLoggedIn()){
    header("location: login.php?return_page=test.php");
} else {
    $user_id = $_SESSION['easyauth_user']['id'];
    $username = $_SESSION['easyauth_user']['username'];
}


echo "<h1>test top</h1>";
echo "user: $username ($user_id)<br>";

echo "<pre>_SESSION: ";
print_r($_SESSION);
echo "</pre>";





echo "ok<br>";

?>
