php-easy-auth
=============

a single file php library to manage users and roles on web site<br>
<br>
easyauth.php - authentication library<br>
login.php - form and code to login<br>
logout.php - code to logout<br>
setupandtest.php - test authentication, remove from production site<br>
adminform.php - form and code to manage users and roles, should not be on production site!<br>
authtest.php - file to check that you are authenticated or not
<br>


steps to install
----------------

1. create mysql database or use existing one
2. copy easyauth.php,login.php,logout.php to root of your php site
3. add `require_once "easyauth.php";` to php files that need authentication
4. rename settings.php.example to settings.php and edit<br>
5. Run setupandtst.php , this will create the tables and run a test on all the library functions



        


