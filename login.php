<?php 
require_once "easyauth.php";
$badpass = NULL;
if(!empty($_REQUEST)) {
    if(@$_REQUEST['username']  &&  $_REQUEST['password'] )  {
        $ret = fetchUserByUsernameAndPassword($_REQUEST['username'],$_REQUEST['password']);
        if(!empty($ret)) {
            $_SESSION['easyauth'][$company_domain][$product_name]=fetchUserByUsername($_REQUEST['username']);
            $user_id = $_SESSION['easyauth'][$company_domain][$product_name]['id'];
            $_SESSION['easyauth'][$company_domain][$product_name]['roles'] = fetchRolesForUserIdEx($user_id);
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
    <form method='post'>
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
