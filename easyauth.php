<?php

if (isset($_REQUEST['_SESSION'])) die();
session_start();

$db_host = "localhost";
$db_name = "authdb";
$db_user = "adminuser";
$db_pass = "passw0rd";
$salt = "random^stuff!heretochange";  // 22 or more random characters
$default_page = "index.php";
$table_prefix = "easy_auth_prefix_";  // can be anything including ""

$easyAuthMySqlI = new mysqli($db_host, $db_user, $db_pass, $db_name);

GLOBAL $easyAuthMySqlI;

$connect_errno = mysqli_connect_errno();
if($connect_errno) {
        if($connect_errno == 1044) {
            echo "<br>database $db_name not found<br>";
        } else
        if($connect_errno == 1045) {
            echo "<br>bad username or password<br>";
        } else
        if($connect_errno == 2002) {
            echo "<br>bad host $db_host<br>";
        } else {
	echo "<br>easyAuthMySqlI connection Failed: " . mysqli_connect_errno() . "<br>" ;
        }
	exit();
}

function createTables() {
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "CREATE TABLE `".$table_prefix."role` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `name_UNIQUE` (`name`)
        ) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    
    
    $sqlCmd = "CREATE TABLE `".$table_prefix."user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(256) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `email_verified` bit(1) DEFAULT b'0',
  `guid` varchar(45) DEFAULT NULL,
  `phone` varchar(45) DEFAULT NULL,
  `firstname` varchar(45) DEFAULT NULL,
  `lastname` varchar(45) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `screen_name_UNIQUE` (`username`),
  UNIQUE KEY `guid_UNIQUE` (`guid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    
    
    $sqlCmd = "CREATE TABLE `".$table_prefix."user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
    
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    
    
}
function truncateTables() {
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "TRUNCATE TABLE `".$table_prefix."role`;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $sqlCmd = "TRUNCATE TABLE `".$table_prefix."user`;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $sqlCmd = "TRUNCATE TABLE `".$table_prefix."user_role`;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
}
function dropTables() {
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "DROP TABLE IF EXISTS  `".$table_prefix."role`;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $sqlCmd = "DROP TABLE IF EXISTS  `".$table_prefix."user`;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $sqlCmd = "DROP TABLE IF EXISTS  `".$table_prefix."user_role`;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
}

function fetchUsers() {
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from ".$table_prefix."user";
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
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from ".$table_prefix."user where id=?";
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
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from ".$table_prefix."user where username=?";
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
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from ".$table_prefix."user where email=?";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("s", $email);
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
function fetchUserByUsernameAndPassword($username,$password) {
    global $easyAuthMySqlI,$table_prefix;
    $hPassword = doubleHash($password);
    $sqlCmd = "select id,username,password,email,email_verified,guid,phone,firstname,lastname,created,updated from ".$table_prefix."user where username=? and password=?";
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
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "select id,name from ".$table_prefix."role";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $stmt->bind_result($id, $name);
    $rows = array();
    while ($stmt->fetch()){
        $rows[$id] = $name;
    }
    return $rows;
}
function fetchUsersAndRoles() {
    global $easyAuthMySqlI,$table_prefix;

    $sqlCmd = "select user_id, role_id from ".$table_prefix."user_role;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $result = $stmt->execute();
    $stmt->bind_result($user_id, $role_id);
    $rows = array();
    while ($stmt->fetch()){
        $rows[] = array('user_id' => $user_id, 'role_id'=>$role_id);
    }
    return $rows;
}
function fetchUserForRole($role_id) {
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "
        select u.id,username from
        ".$table_prefix."user u
        join
        ".$table_prefix."user_role ur
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
    global $easyAuthMySqlI,$table_prefix;
    $guid = generateGuid();
    $hPassword = doubleHash($password);
    $sqlCmd = "insert into ".$table_prefix."user (username,password,email,guid,phone,firstname,lastname,created) values (?,?,?,?,?,?,?,now())";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("sssssss", $username,$hPassword,$email,$guid,$phone,$firstname,$lastname);
    $stmt->execute();
    return $stmt;
}
function addRole($role_name) {
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "insert into ".$table_prefix."role (name) values (?);";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("s", $role_name);
    $result = $stmt->execute();
    $bothret = array('stmt' => $stmt,'result' => $result);
    return $bothret;
}
function addRoleForUser($user_id,$role_id) {
    global $easyAuthMySqlI,$table_prefix;
    //$guid = generateGuid();
    //$hPassword = doubleHash($password);
    $sqlCmd = "insert into ".$table_prefix."user_role (user_id,role_id) values (?,?)";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("ii", $user_id,$role_id);
    $result = $stmt->execute();
    $bothret = array('stmt' => $stmt,'result' => $result);
    return $bothret;
}


function deleteUser($id) {
    global $easyAuthMySqlI,$table_prefix;
    deleteUserRoleForUser($id);
    $sqlCmd = "delete from ".$table_prefix."user where id = ? ;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    return $result;
}

function deleteUserRole($user_id,$role_id) {
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "
        delete from ".$table_prefix."user_role where user_id = ? and role_id = ?;
";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("ii", $user_id,$role_id);
    $result = $stmt->execute();
    return $result;
}
function deleteRole($id) {
    global $easyAuthMySqlI,$table_prefix;
    deleteUserRoleForRole($id);
    $sqlCmd = "delete from ".$table_prefix."role where id = ? ;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    return $result;
}
function deleteUserRoleForUser($id) {
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "delete from ".$table_prefix."user_role where user_id = ? ;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    return $result;
}

function deleteUserRoleForRole($id) {
    global $easyAuthMySqlI,$table_prefix;
    $sqlCmd = "delete from ".$table_prefix."user_role where role_id = ? ;";
    $stmt = $easyAuthMySqlI->prepare($sqlCmd);
    $stmt->bind_param("i", $id);
    $result = $stmt->execute();
    return $result;
}

function clearAllUsers() {
    global $easyAuthMySqlI,$table_prefix;
    $stmt = $easyAuthMySqlI->prepare("truncate table ".$table_prefix."user");
    $result = $stmt->execute();
    echo "<pre>";     print_r($stmt);     echo "</pre>";
}

function clearAllRoles() {
    global $easyAuthMySqlI,$table_prefix;
    $stmt = $easyAuthMySqlI->prepare("truncate table ".$table_prefix."role");
    $result = $stmt->execute();
    echo "<pre>";     print_r($stmt);     echo "</pre>";
}

function clearAllUserRoles() {
    global $easyAuthMySqlI,$table_prefix;
    $stmt = $easyAuthMySqlI->prepare("truncate table ".$table_prefix."user_role");
    $result = $stmt->execute();
    echo "<pre>";     print_r($stmt);     echo "</pre>";
}

function updateUser($firstname,$lastname,$username,$email,$phone,$id){
    global $easyAuthMySqlI,$table_prefix;

    $sqlCmd = "
        update ".$table_prefix."user set
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
    global $salt;
    $hash_format = "$2y$11$";
    $saltAndFormat = $hash_format . $salt;
    $hash = crypt($instring,$saltAndFormat);
    return $hash;
    
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

