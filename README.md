php-easy-auth
=============

a single file php library to manage users and roles on web site

easyauth.php - authentication library
authdb.sql - mysql file to create tables in database
login.php - form and code to login
logout.php - code to logout
test.php - test authenication
adminform.php - form and code to manage users and roles



----------- steps to install ------------

1. create mysql database
2. run authdb.sql on that database
3. copy easyauth.php,login.php,logout.php to root of your php site
4. add require_once "easyauth.php"; to php files that need authintication
5. edit configuration variables on top of easyauth.php
        $db_host = "localhost"; 
        $db_name = "authdb";
        $db_user = "adminuser"; 
        $db_pass = "passw0rd"; 
        $hash_seed = "changme just type random stuff here blah^^^ggg";
        $default_page = "test.php";
        

        


