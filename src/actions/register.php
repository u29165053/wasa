<?php 
session_start();

if (isset($GLOBALS["ABSPATH"])){
	require_once($GLOBALS["ABSPATH"]."/include/database.php");
	require_once($GLOBALS["ABSPATH"]."/include/utils.php");
	require_once($GLOBALS["ABSPATH"]."/include/settings.php");
}else{
	require_once("../include/database.php");
	require_once("../include/utils.php");
	require_once("../include/settings.php");
}

function getPost($name){
	return isset($_POST[$name]) && !is_null($_POST[$name]) ? addslashes($_POST[$name]) : NULL;
}

$ref = strtok($_SERVER['HTTP_REFERER'], "?");

$user = array(
	"username" => getPost("username"),
	"password" => getPost("password"),
	"email" => getPost("email")
);

$db = new DAO();
$db->getOption("home");
$code = $db->saveUser($user);


if ( $code == DB_USERNAME_EXISTS ){
	echo json_encode(array("error"=>1, "msg"=>"El nombre de usuario ya existe."));
	die();
}
if ( $code == DB_EMAIL_EXISTS ){
	echo json_encode(array("error"=>1, "msg"=>"El email ya existe."));
	die();
}

if ( $code > 0 ){
	echo json_encode(array("error"=>0, "msg"=>"Usuario registrado correctamente."));
	die();
}




 ?>