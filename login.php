<?php 
require_once "easyauth.php";
//print_r($_SESSION);
//print_r($_REQUEST);
$badpass = NULL;
if(!empty($_REQUEST)) {
    if(@$_REQUEST['username']  &&  $_REQUEST['password'] )  {
        $ret = fetchUserByUsernameAndPassword($_REQUEST['username'],$_REQUEST['password']);
        if(!empty($ret)) {
            $_SESSION['easyauth_user']=fetchUserByUsername($_REQUEST['username']);
            $user_id = $_SESSION['easyauth_user']['id'];
            $_SESSION['easyauth_user']['roles'] = fetchRolesForUserIdEx($user_id);
            //print_r($_SESSION);
            //print_r($_REQUEST);
            if(@$_REQUEST['return_page']) {
                $return_page = $_REQUEST['return_page'];
                //echo "header(location: $return_page);<br>";
                header("location: $return_page");
            } else {
                //echo "defpage<br>";
                gotoDefaultPage();
            }
        } else {
            $badpass = true;
        }
    } else {
        //logout();
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
            <?php
            if(@$_REQUEST['return_page']) {
                echo "<input type='hidden'  name='return_page' value='{$_REQUEST['return_page']}'>";
            }
            ?>
            <button type="submit" >Login</button>
        </form>        
        <?php
            if($badpass) {
                echo "<h2>bad name or password</h2>";
            }
        ?>


    
</body>
</html>
