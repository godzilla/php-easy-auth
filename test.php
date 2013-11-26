<?php

require_once "easyauth.php";


if(!isLoggedIn()){
    header("location: login.php?return_page=test.php");
} else {
    $user_id = key($_SESSION['user']['user']);
    $username = $_SESSION['user']['user'][$user_id]['username'];
}


echo "<h1>test top</h1>";
echo "user: $username ($user_id)<br>";

echo "<pre>_SESSION: ";
print_r($_SESSION);
echo "</pre>";





echo "ok<br>";

?>
