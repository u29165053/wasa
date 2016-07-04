<?php 
session_start();

if (isset($GLOBALS["ABSPATH"])){
	require_once($GLOBALS["ABSPATH"]."/include/database.php");
	require_once($GLOBALS["ABSPATH"]."/include/utils.php");
}else{
	require_once("../include/database.php");
	require_once("../include/utils.php");
}

function getPost($name){
	return isset($_POST[$name]) && !is_null($_POST[$name]) ? addslashes($_POST[$name]) : NULL;
}

$ref = strtok($_SERVER['HTTP_REFERER'], "?");

$u = getPost("username");
$p = getPost("password");

$db = new DAO();
$db->getOption("home");
$user = $db->getUser($u, $p);

$error = $db->getOption("home")."?error=login";

unset($u);
unset($p);

if ( !is_null($user) ){
	$_SESSION["iu"] = $user->getId();
	$_SESSION["ius"] = Utils::rc4_encrypt(serialize($user));
	header("Location: ".$ref);
}else{
	header("Location: ".$error);
}

 ?>