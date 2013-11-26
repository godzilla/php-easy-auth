<?php

require_once "easyauth.php";

$action = @$_REQUEST['action'];
if($action) {
    if($action == 'add_role'){
        $name = $_REQUEST['name'];
        addRole($name);
    }
    
    if($action == 'add_user'){
        $username = @$_REQUEST['username'];
        $password = @$_REQUEST['password'];
        $email = @$_REQUEST['email'];
        $phone = @$_REQUEST['phone'];
        $firstname = @$_REQUEST['firstname'];
        $lastname = @$_REQUEST['lastname'];
        addUser($username,$password,$email,$phone,$firstname,$lastname);
    }
    
    
    if($action == 'add_user_role'){
        $user_id = @$_REQUEST['user_id'];
        $role_id = @$_REQUEST['role_id'];
        addRoleForUser($user_id,$role_id);
    }
    
    

    if($action == 'del_user'){
        $user_id = $_REQUEST['user_id'];
        deleteUserRoleForUser($user_id);
        deleteUser($user_id);
    }
    
    
    if($action == 'del_role'){
        $role_id = $_REQUEST['role_id'];
        deleteUserRoleForRole($role_id);
        deleteRole($role_id);
    }
    
    if($action == 'del_user_role'){
        $user_role_id = $_REQUEST['user_role_id'];
        $user_role_id_array = explode(',',$user_role_id);
        $user_id = $user_role_id_array[0];
        $role_id = $user_role_id_array[1];
        deleteUserRole($user_id,$role_id);
    }
    
    
    
}



$users = fetchUsers();
echo "<pre>users: "; print_r($users); echo "</pre>";

$roles = fetchRoles();
echo "<pre>roles: "; print_r($roles); echo "</pre>";



$usersAndRoles = fetchUsersAndRoles();
//echo "<pre>users and roles: "; print_r($usersAndRoles); echo "</pre>";




$userRoleArray = array();
foreach($usersAndRoles as $usersAndRole) {
    $user_id = $usersAndRole['user_id'];
    $role_id = $usersAndRole['role_id'];
    $user = $users[$user_id];
    $username = $user['username'];
    $role_name = $roles[$role_id];
    $namepair = "$username,$role_name";
    $idpair = "$user_id,$role_id";
    $userRoleArray[$idpair] = $namepair;
}


echo "<pre>userRoleArray: "; print_r($userRoleArray); echo "</pre>";

?>

<hr>



<form>
    <input type="text" name="username" placeholder="username" required>
    <input type="password" name="password" placeholder="password" required>
    <input type="email" name="email" placeholder="email" required>
    <input type="tel" name="phone" placeholder="phone" required>
    <input type="text" name="firstname" placeholder="firstname" required>
    <input type="text" name="lastname" placeholder="lastname" required>
    <input type="submit" name="action" value="add_user">
</form>



<form>
    <select name='user_id'>
        <?php
        foreach($users as $user_id => $user) {
            
            echo "<option value='$user_id'>{$user['username']}</option>";
            
        }
        ?>
        
    </select>
    <input type="submit" name="action" value="del_user">
</form>






<form>
    <input type="text" name="name" placeholder="role_name" required>
    <input type="submit" name="action" value="add_role">
</form>



<form>
    <select name='role_id'>
        <?php
        foreach($roles as $role_id => $role_name) {
            echo "<option value='$role_id'>$role_name</option>";
        }
        ?>
    </select>
    <input type="submit" name="action" value="del_role">
</form>


<form>
    <select name='user_id'>
        <?php
        foreach($users as $user_id => $user) {
            echo "<option value='$user_id'>{$user['username']}</option>";
        }
        ?>
    </select>
    
    <select name='role_id'>
        <?php
        foreach($roles as $role_id => $role_name) {
            echo "<option value='$role_id'>$role_name</option>";
        }
        ?>
    </select>
    
    <input type="submit" name="action" value="add_user_role">
</form>



<form>
    <select name='user_role_id'>
        <?php
        foreach($userRoleArray as $id_pair => $name_pair) {
            echo "<option value='$id_pair'>$name_pair</option>";
        }
        ?>
    </select>
    <input type="submit" name="action" value="del_user_role">
</form>
