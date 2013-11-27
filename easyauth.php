<?php

if (isset($_REQUEST['_SESSION'])) die();
session_start();

$db_host = "localhost";
$db_name = "authdb";
$db_user = "adminuser";
$db_pass = "passw0rd";
$hash_seed = "changme just type random stuff here blah^^^ggg";
$default_page = "test.php";

$easyAuthMySqlI = new mysqli($db_host, $db_user, $db_pass, $db_name);
GLOBAL $easyAuthMySqlI;
if(mysqli_connect_errno()) {
	echo "easyAuthMySqlI connection Failed: " . mysqli_connect_errno();
	exit();
}


function fetchUsers() {
    global $easyAuthMySqlI;
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from user";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $stmt->bind_result($id, $username, $password,$email,$email_verified,$guid,$phone,$firstname,$lastname,$created,$updated);
    $rows = array();
    while ($stmt->fetch()){
        $rows[$id] = array( 'username' => $username,
                            'password' => $password,
                            'email' => $email,
                            'email_verified' => $email_verified,
                            'guid' => $guid,
                            'phone' => $phone,
                            'firstname' => $firstname,
                            'lastname' => $lastname,
                            'created'=>$created,
                            'updated'=>$updated
                    );
    }
    return $rows;
}

function fetchUsersById($id) {
    global $easyAuthMySqlI;
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from user where id=?";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->bind_result($id, $username, $password,$email,$email_verified,$guid,$phone,$firstname,$lastname,$created,$updated);
    $row = array();
    while ($stmt->fetch()){
        $row = array( 'username' => $username,
                            'password' => $password,
                            'email' => $email,
                            'email_verified' => $email_verified,
                            'guid' => $guid,
                            'phone' => $phone,
                            'firstname' => $firstname,
                            'lastname' => $lastname,
                            'created'=>$created,
                            'updated'=>$updated
                    );
    }
    return $row;
}
function fetchUserByUsername($username) {
    global $easyAuthMySqlI;
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from user where username=?";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("s", $username);
    $result = $stmt->execute();
    $stmt->bind_result($id, $username, $password,$email,$email_verified,$guid,$phone,$firstname,$lastname,$created,$updated);
    $row = array();
    while ($stmt->fetch()){
        $row = array(       'id' => $id,
                            'username' => $username,
                            'password' => $password,
                            'email' => $email,
                            'email_verified' => $email_verified,
                            'guid' => $guid,
                            'phone' => $phone,
                            'firstname' => $firstname,
                            'lastname' => $lastname,
                            'created'=>$created,
                            'updated'=>$updated
                    );
    }
    return $row;
}
function fetchUserByEmail($email) {
    global $easyAuthMySqlI;
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from user where email=?";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("s", $email);
    $result = $stmt->execute();
    $stmt->bind_result($id, $username, $password,$email,$email_verified,$guid,$phone,$firstname,$lastname,$created,$updated);
    $row = array();
    while ($stmt->fetch()){
        $row = array( 'username' => $username,
                            'password' => $password,
                            'email' => $email,
                            'email_verified' => $email_verified,
                            'guid' => $guid,
                            'phone' => $phone,
                            'firstname' => $firstname,
                            'lastname' => $lastname,
                            'created'=>$created,
                            'updated'=>$updated
                    );
    }
    return $row;
}
function fetchUserByUsernameAndPassword($username,$password) {
    global $easyAuthMySqlI;
    $hPassword = doubleHash($password);
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from user where username=? and password=?";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("ss", $username,$hPassword);
    $result = $stmt->execute();
    $stmt->bind_result($id, $username, $password,$email,$email_verified,$guid,$phone,$firstname,$lastname,$created,$updated);
    $row = array();
    while ($stmt->fetch()){
        $row = array(
                            'id' => $id,
                            'username' => $username,
                            'password' => $password,
                            'email' => $email,
                            'email_verified' => $email_verified,
                            'guid' => $guid,
                            'phone' => $phone,
                            'firstname' => $firstname,
                            'lastname' => $lastname,
                            'created'=>$created,
                            'updated'=>$updated
                    );
    }
    return $row;
}
function fetchRoles() {
    global $easyAuthMySqlI;
    $sqlCmd = "select id,name from role";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $stmt->bind_result($id, $name);
    $rows = array();
    while ($stmt->fetch()){
        $rows[$id] = $name;
    }
    return $rows;
}

function fetchRolesForUserIdEx($id) {
    global $easyAuthMySqlI;
    $sqlCmd = "
        select ur.role_id,r.name
        from
        user_role ur
        join
        role r
        on
        r.id = ur.role_id
        where ur.user_id = ?;
";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    $stmt->bind_result($role_id,$role_name);
    $rows = array();
    while ($stmt->fetch()){
        //$rows[] = array('role_id' =>  $role_id, 'role_name' => $role_name);
        $rows[$role_id] =  $role_name;
    }
    return $rows;
}


function fetchUsersAndRoles() {
    global $easyAuthMySqlI;

    $sqlCmd = "select user_id, role_id from user_role;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $stmt->bind_result($user_id, $role_id);
    $rows = array();
    while ($stmt->fetch()){
        $rows[] = array('user_id' => $user_id, 'role_id'=>$role_id);
    }
    return $rows;
}


function fetchUsersAndRolesEx() {
    global $easyAuthMySqlI;

    $sqlCmd = "
        select u.id, username, ur.role_id , r.name
        from
        user u
        left join
        user_role ur
        on
        u.id = ur.user_id
        left join
        role r
        on
        r.id = ur.role_id;
        ";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $stmt->bind_result($aa, $bb, $cc,$dd);
    $rows = array();
    while ($stmt->fetch()){
        //$rows[$aa] = array();
        //$rows[$aa][] = $cc;
        $rows[] = array('id' => $aa, 'username'=>$bb, 'role_id'=> $cc, 'role_name'=>$dd);

    }
    return $rows;

}



function fetchUserForRole($role_id) {
    global $easyAuthMySqlI;
    $sqlCmd = "
        select u.id,username from
        user u
        join
        user_role ur
        on
        u.id = ur.user_id
        where role_id = ?;
        ;

";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $role_id);
    $result = $stmt->execute();
    $stmt->bind_result($user_id,$user_name);
    $rows = array();
    while ($stmt->fetch()){
        $rows[$user_id] = $user_name;
    }
    return $rows;
}

function addUser($username,$password,$email,$phone,$firstname,$lastname) {
    global $easyAuthMySqlI;
    $guid = generateGuid();
    $hPassword = doubleHash($password);
    $sqlCmd = "insert into user (username,password,email,guid,phone,firstname,lastname,created) values (?,?,?,?,?,?,?,now())";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("sssssss", $username,$hPassword,$email,$guid,$phone,$firstname,$lastname);
    $stmt->execute();
    return $stmt;
}
function addRole($role_name) {
    global $easyAuthMySqlI;
    $sqlCmd = "insert into role (name) values (?);";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("s", $role_name);
    $result = $stmt->execute();
    $bothret = array('stmt' => $stmt,'result' => $result);
    return $bothret;
}
function addRoleForUser($user_id,$role_id) {
    global $easyAuthMySqlI;
    //$guid = generateGuid();
    //$hPassword = doubleHash($password);
    $sqlCmd = "insert into user_role (user_id,role_id) values (?,?)";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("ii", $user_id,$role_id);
    $result = $stmt->execute();
    $bothret = array('stmt' => $stmt,'result' => $result);
    return $bothret;
}


function deleteUser($id) {
    global $easyAuthMySqlI;
    $sqlCmd = "delete from user where id = ? ;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    return $result;
}

function deleteUserRole($user_id,$role_id) {
    global $easyAuthMySqlI;
    $sqlCmd = "
        delete from user_role where user_id = ? and role_id = ?;
";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("ii", $user_id,$role_id);
    $result = $stmt->execute();
    return $result;
}
function deleteRole($id) {
    global $easyAuthMySqlI;
    $sqlCmd = "delete from role where id = ? ;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    return $result;
}
function deleteUserRoleForUser($id) {
    global $easyAuthMySqlI;
    $sqlCmd = "delete from user_role where user_id = ? ;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    return $result;
}


function deleteUserRoleForRole($id) {
    global $easyAuthMySqlI;
    $sqlCmd = "delete from user_role where role_id = ? ;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    return $result;
}




function clearAllUsers() {
    global $easyAuthMySqlI;
    $stmt = $easyAuthMySqlI->prepare("truncate table user");
    $result = $stmt->execute();
    echo "<pre>";     print_r($stmt);     echo "</pre>";
}


function clearAllRoles() {
    global $easyAuthMySqlI;
    $stmt = $easyAuthMySqlI->prepare("truncate table role");
    $result = $stmt->execute();
    echo "<pre>";     print_r($stmt);     echo "</pre>";
}

function clearAllUserRoles() {
    global $easyAuthMySqlI;
    $stmt = $easyAuthMySqlI->prepare("truncate table user_role");
    $result = $stmt->execute();
    echo "<pre>";     print_r($stmt);     echo "</pre>";
}



function updateUser($firstname,$lastname,$username,$email,$phone,$id){
    global $easyAuthMySqlI;
    logme("updateUser($firstname,$lastname,$username,$email,$phone,$id)");

    $sqlCmd = "
        update user set
        firstname=?,
        lastname=?,
        username=?,
        email=?,
        phone=?,
        updated=now()
        where
        id=?;
        ;
     ";

    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("sssssi", $firstname,$lastname,$username,$email,$phone,$id);
    $result = $stmt->execute();
    return $result;


}

function generateGuid(){
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = ""    //chr(123)// "{"
                . substr($charid, 0, 8).$hyphen
                . substr($charid, 8, 4).$hyphen
                . substr($charid,12, 4).$hyphen
                . substr($charid,16, 4).$hyphen
                . substr($charid,20,12);
                //.chr(125);// "}"

        return $uuid;
}
function doubleHash($instring){
    global $hash_seed;
    $hash2 =  $instring . $hash_seed;
    $hash3 = md5($hash2);
    $hash4 = sha1($hash3);
    return $hash4;
}
function logout() {
    // http://php.net/manual/en/function.session-unset.php
    @session_start();
    session_unset();
    session_destroy();
    session_write_close();
    setcookie(session_name(),'',0,'/');
    session_regenerate_id(true);
    // my stuff
    session_start();
    session_destroy();
    session_unset();
     unset ($_SESSION['user']);
    $_SESSION['user'] = null;
}

function isLoggedIn() {
    if(!empty($_SESSION) && $_SESSION['easyauth_user'])  {
        return true;
    }
    return FALSE;
}
function isLoggedInWithRoleName($inRole) {
    if(!empty($_SESSION) && $_SESSION['easyauth_user'])  {
        foreach($_SESSION['easyauth_user']['roles'] as $role_id => $role_name) {
            if($role_name == $inRole) {
                return true;
            }
        }
        return false;
    }
    return FALSE;
}





function gotoDefaultPage() {
    global $default_page;
    header("location: $default_page");
}



