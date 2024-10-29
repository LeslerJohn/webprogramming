<?php
require_once('../tools/functions.php');
require_once('../classes/account.class.php');

$first_name = $last_name = $username = $role = $password = $confirm_password = $is_admin = $is_staff = '';
$first_nameErr = $last_nameErr = $usernameErr = $roleErr = $passwordErr = $confirm_passwordErr = '';

$accountObj = new Account();

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    $first_name = clean_input($_POST['first_name']);
    $last_name = clean_input($_POST['last_name']);
    $username = clean_input($_POST['username']);
    $role = clean_input($_POST['role']);
    $password = clean_input($_POST['password']);
    $confirm_password = clean_input($_POST['confirm_password']);
    $is_admin = isset($_POST['is_admin']) ? clean_input($_POST['is_admin']) : '0';
    $is_staff = isset($_POST['is_staff']) ? clean_input($_POST['is_staff']) : '0';

    if(empty($username)){
        $usernameErr = 'Username is required.';
    } else if ($accountObj->usernameExist($username)){
        $usernameErr = 'Username already exists.';
    }

    if(empty($last_name)){
        $last_nameErr = 'Name is required.';
    }

    if(empty($first_name)){
        $first_nameErr = 'First name is required.';
    }

    if(empty($role)){
        $roleErr = 'Role is required.';
    }

    if(empty($password)){
        $passwordErr = 'Password is required.';
    }

    if(empty($confirm_password)){
        $confirm_passwordErr = 'Confirm password is required.';
    } else if($password !== $confirm_password){
        $confirm_passwordErr = 'Passwords do not match.';
    }

    // If there are validation errors, return them as JSON
    if(!empty($first_nameErr) || !empty($last_nameErr) || !empty($usernameErr) || !empty($roleErr) || !empty($passwordErr) || !empty($confirm_passwordErr)){
        echo json_encode([
            'status' => 'error',
            'first_nameErr' => $first_nameErr,
            'last_nameErr' => $last_nameErr,
            'usernameErr' => $usernameErr,
            'roleErr' => $roleErr,
            'passwordErr' => $passwordErr,
            'confirm_passwordErr' => $confirm_passwordErr
        ]);
        exit;
    }

    if(empty($first_nameErr) && empty($last_nameErr) && empty($usernameErr) && empty($roleErr) && empty($passwordErr) && empty($confirm_passwordErr)){
        $accountObj->first_name = $first_name;
        $accountObj->last_name = $last_name;
        $accountObj->username = $username;
        $accountObj->role = $role;
        $accountObj->password = password_hash($password, PASSWORD_DEFAULT);
        $accountObj->is_admin = $is_admin;
        $accountObj->is_staff = $is_staff;


        if($accountObj->add()){
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong when adding the new account.']);
        }
        exit;
    }
}

?>
