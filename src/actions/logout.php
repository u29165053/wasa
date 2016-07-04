<?php 
session_start();

require_once("../include/database.php");
require_once("../include/utils.php");

$ref = strtok($_SERVER['HTTP_REFERER'], "?");

unset($_SESSION["iu"]);
unset($_SESSION["ius"]);

session_destroy();
header("Location: ".$ref);

 ?>