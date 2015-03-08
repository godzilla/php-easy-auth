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

2. copy easyauth.php,login.php,logout.php,settings.php.example,setupandtest.php to root of your php site

3. rename settings.php.example to settings.php and edit every line<br>

4. Run setupandtest.php , this will create the tables and run a test on all the library functions

You now have 3 new tables in your database<br>
    easy_auth_prefix_user           // 1 row for every user, should be empty after test
    easy_auth_prefix_role           // 1 row for every role, should have admin and guest
    easy_auth_prefix_user_role      // 1 row for every relation between the other 2 tables, should be empty after test


5. If the tests pass remove setupadntest.php from production env

6. add `require_once "easyauth.php"` to very top of php files that need authentication

7. add to same file:
    <pre>
    if(!isLoggedIn()){
        header("location: login.php?return_page=index.php");
    } else {
        $user_id = $_SESSION['easyauth'][$company_domain][$product_name]['id'];
        $username = $_SESSION['easyauth'][$company_domain][$product_name]['username'];
    }
    </pre>

you can copy adminform.php (temporarily) to site to create initial users, do not leave this file on production site!

    




        


