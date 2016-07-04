<?php 

require_once('database.php');
require_once('../utils/ZipManager.php');

if(isset($_GET['sha256'])){
	$sha256 = $_GET['sha256'];
	$db = new DAO();
	$analisis = $db->getAnalysis($sha256);
	$mp = $analisis->getManifest();

	$home = $db->getOption("home");
	$abspath = $db->getOption("abspath");

	$mp = str_replace($home, $abspath, $mp);
	$mp = str_replace("/", DIRECTORY_SEPARATOR, $mp);
	$folder = dirname($mp);

	$smaliDir = $folder . DIRECTORY_SEPARATOR . 'smali'. DIRECTORY_SEPARATOR;
	$name = $analisis->getMD5().".zip";
	$zip = new ZipManager($name, $smaliDir);
	$zip->comprimirDirectorio();
	$output = $zip->getOutput();

	$urlZip = $db->getOption("url_muestras")."/".$name;
	header("Location: ".$urlZip);


}
?>