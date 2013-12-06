php-easy-auth
=============

a single file php library to manage users and roles on web site<br>
<br>
easyauth.php - authentication library<br>
authdb.sql - mysql file to create tables in database<br>
login.php - form and code to login<br>
logout.php - code to logout<br>
setupandtest.php - test authenication<br>
adminform.php - form and code to manage users and roles<br>
<br>


----------- steps to install ------------<br>
<br>
1. create mysql database or use existing one<br>
3. copy easyauth.php,login.php,logout.php to root of your php site<br>
4. add `require_once "easyauth.php";` to php files that need authintication<br>
2. edit configuration variables on top of easyauth.php
    ```$db_host = "localhost";<br>
    $db_name = "authdb";
    $db_user = "adminuser";
    $db_pass = "passw0rd";
    $salt = "random^stuff!heretochange";  // 22 or more random characters
    $default_page = "index.php";
    $table_prefix = "easy_auth_prefix_";  // can be anything including ""
    ```
3. run setupandtst.php , this will create the tables and run a test on all the library functions


        


