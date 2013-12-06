<style>
    .fail {color: red;}
    .pass {color: green;}
</style>

<?php
//echo "<pre>"; print_r($user2); echo "</pre>";

require_once "easyauth.php";
$passed = true;

$original_salt = "random^stuff!heretochange";

if(isUsingOriginalSalt($original_salt)) {
    $passed = false;
    echo "<h1 class='fail'>using orignal salt of $original_salt<br>please change it in easyauth.php</h1>";
    die();
}

dropTables();
createTables();

echo "<h1>tables created</h1>";

addUser("lfine","abc","ls@stooge.com","555","larry","fine");
addUser("mhoward","123","mh@stooge.com","666","moe","howard");
addUser("choward","xyz","ch@stooge.com","777","curly","howard");

$users = fetchUsers();
//echo "<pre>"; print_r($users); echo "</pre>";
$count = count($users);
if($count != 3) {
    $passed = false;
    echo "<h1 class='fail'>fetchUsers() failed got count of $count</h1>";
}


addUser("showard","rgb","sh@stooge.com","888","shemp","howard");

$users = fetchUsers();
$count = count($users);

if($count != 4) {
    $passed = false;
    echo "<h1 class='fail'>fetchUsers() failed got count of $count</h1>";
}


deleteUser(4);

$users = fetchUsers();
$count = count($users);

if($count != 3) {
    $passed = false;
    echo "<h1 class='fail'>deleteUser() failed got count of $count</h1>";
}



$user = fetchUsersById(2);
//echo "<pre>"; print_r($user); echo "</pre>";    


if($user['username'] != 'mhoward') {
    $passed = false;
    echo "<h1 class='fail'>fetchUsersById() failed</h1>";
}


$user = fetchUserByUsername('mhoward');
//echo "<pre>"; print_r($user); echo "</pre>";

if($user['id'] != 2) {
    $passed = false;
    echo "<h1 class='fail'>fetchUserByUsername() failed</h1>";
}


$user = fetchUserByEmail('mh@stooge.com');
//echo "<pre>"; print_r($user); echo "</pre>";

if($user['username'] != 'mhoward') {
    $passed = false;
    echo "<h1 class='fail'>fetchUserByEmail() failed</h1>";
}


$user = fetchUserByUsernameAndPassword("mhoward","123");
//echo "<pre>"; print_r($user); echo "</pre>";
if($user['username'] != 'mhoward') {
    $passed = false;
    echo "<h1 class='fail'>fetchUserByUsernameAndPassword() failed</h1>";
}


addRole('admin');
addRole('guest');
$roles = fetchRoles();
//echo "<pre>"; print_r($roles); echo "</pre>";
$count = count($roles);

if($count != 2) {
    $passed = false;
    echo "<h1 class='fail'>addRole() or fetchRoles() failed</h1>";
    
}


addRoleForUser(2,1);
addRoleForUser(3,2);
addRoleForUser(1,2);


$usersAndRoles = fetchUsersAndRoles();
//echo "<pre>"; print_r($usersAndRoles); echo "</pre>";
$count = count($usersAndRoles);

if($count != 3) {
    $passed = false;
    echo "<h1 class='fail'>addRoleForUser() or fetchUsersAndRoles() failed</h1>";
    
}



$user = fetchUserForRole(2);
//echo "<pre>"; print_r($user); echo "</pre>";



addRoleForUser(1,1);
addRoleForUser(2,1);
addRoleForUser(3,1);
addRoleForUser(1,2);
addRoleForUser(2,2);
addRoleForUser(3,2);

$usersAndRoles = fetchUsersAndRoles();
//echo "<pre>"; print_r($usersAndRoles); echo "</pre>";

$count = count($usersAndRoles);

if($count != 6) {
    $passed = false;
    echo "<h1 class='fail'>addRoleForUser failed count is $count</h1>";
    
}



deleteUser(3);

$usersAndRoles = fetchUsersAndRoles();
//echo "<pre>"; print_r($usersAndRoles); echo "</pre>";

$count = count($usersAndRoles);

if($count != 4) {
    $passed = false;
    echo "<h1 class='fail'>deleteUser() failed  role_user count is $count</h1>";
    
}


deleteRole(1);


$usersAndRoles = fetchUsersAndRoles();
//echo "<pre>"; print_r($usersAndRoles); echo "</pre>";

$count = count($usersAndRoles);

if($count != 2) {
    $passed = false;
    echo "<h1 class='fail'>deleteRole() failed  role_user count is $count</h1>";
    
}


$usersAndRoles = fetchUsersAndRoles();
//echo "<pre>"; print_r($usersAndRoles); echo "</pre>";


deleteUserRole(2,2);

$usersAndRoles = fetchUsersAndRoles();
//echo "<pre>"; print_r($usersAndRoles); echo "</pre>";
$count = count($usersAndRoles);

if($count != 1) {
    $passed = false;
    echo "<h1 class='fail'>deleteUserRole() failed  role_user count is $count</h1>";
    
}

$users = fetchUsers();
//echo "<pre>"; print_r($users); echo "</pre>";

updateUser('lawrence','fine','lafine','laf@stooge.com','999',1);

$users = fetchUsers();
//echo "<pre>"; print_r($users); echo "</pre>";


if($users[1]['username'] != 'lafine') {
    $passed = false;
    echo "<h1 class='fail'>updateUser() failed</h1>";
}

truncateTables();

if($passed) {
    echo "<h1 class='pass'>all tests passed</h1>";
    
}



?>
