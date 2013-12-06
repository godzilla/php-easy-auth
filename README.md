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



----------- steps to install ------------

1. create mysql database or use existing one
2. copy easyauth.php,login.php,logout.php to root of your php site
3. add `require_once "easyauth.php";` to php files that need authintication
4. edit configuration variables on top of easyauth.php<br>
<code>
$db_host = "localhost";<br>
$db_name = "authdb";<br>
$db_user = "adminuser";<br>
$db_pass = "passw0rd";<br>
$salt = "random^stuff!heretochange";  // 22 or more random characters<br>
$default_page = "index.php";<br>
$table_prefix = "easy_auth_prefix_";  // can be anything including ""<br>
</code>
5. Run setupandtst.php , this will create the tables and run a test on all the library functions



        


