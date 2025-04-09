<?php
include_once "include/header.php";

//check if logged in 
if(!$user_obj->checkLoginStatus($_SESSION['user'] ['id'])){
	header("Location: login.php");
}

print_r($_SESSION['user'] ['role']);
$result = $user_obj->checkUserRole($_SESSION['user'] ['role'],  300);

print_r($result)
?>