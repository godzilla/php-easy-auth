<?php 
require_once "easyauth.php";
$badpass = NULL;
if(!empty($_REQUEST)) {
    if(@$_REQUEST['username']  &&  $_REQUEST['password'] )  {
        $ret = fetchUserByUsernameAndPassword($_REQUEST['username'],$_REQUEST['password']);
        if(!empty($ret)) {
            $_SESSION['easyauth_user']=fetchUserByUsername($_REQUEST['username']);
            $user_id = $_SESSION['easyauth_user']['id'];
            $_SESSION['easyauth_user']['roles'] = fetchRolesForUserIdEx($user_id);
            if(@$_REQUEST['return_page']) {
                $return_page = $_REQUEST['return_page'];
                header("location: $return_page");
            } else {
                gotoDefaultPage();
            }
        } else {
            $badpass = true;
        }
    } else {
        logout();
    }
}

?>

<!doctype html>
<html>
<head>
    <title>login</title>
</head>
<body>
        
        <form>
            <input type="text" placeholder="username" name="username">
            <input type="password" placeholder="password" name="password">
            <button type="submit" >Login</button>
        </form>        
        <?php
            if($badpass) {
                echo "<h2>bad name or password</h2>";
            }
        ?>


    
</body>
</html>