<?php 
session_start();


require_once(dirname(dirname(__FILE__)) . '/utils/HashManager.php'); 
require_once(dirname(dirname(__FILE__)) . '/utils/ExeManager.php'); 
require_once(dirname(dirname(__FILE__)) . '/utils/ApkManager.php'); 
require_once(dirname(dirname(__FILE__)) . '/include/database.php'); 
require_once(dirname(dirname(__FILE__)) . '/include/dto/Analisis.dto.php'); 
require_once(dirname(dirname(__FILE__)) . '/include/dto/AnalisisExe.dto.php'); 
require_once(dirname(dirname(__FILE__)) . '/include/dto/AnalisisApk.dto.php'); 

$max_file_size = 1024 * 1024 * 100; //100MB
$paramFile = "mlwr_muestra";
$extensiones = array("exe", "apk");

$uploadDir = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR;
$basename = basename($_FILES[$paramFile]['name']);
$extFile = strtolower(pathinfo($basename, PATHINFO_EXTENSION));

if (!in_array($extFile, $extensiones)){
	echo json_encode(array("error" => "Extension no permitida"));
	die();
}

	if($_FILES[$paramFile]['size'] > $max_file_size){
		echo json_encode(array("error"=>"El fichero es demasiado grande"));
		die();
	}



		$filePath = $uploadDir . $basename;

		if (move_uploaded_file($_FILES[$paramFile]['tmp_name'], $filePath)) {
			require(dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR.'utils'.DIRECTORY_SEPARATOR.'ZipManager.php');

			//Comprobar si existe el analisis
			
			$db = new DAO();
			$hm = new HashManager($filePath);
			$sha256 = $hm->SHA256();
			$iu = isset($_SESSION["iu"]) ? $_SESSION["iu"] : 0;
			if (($cid = $db->existsAnalysis($sha256, $iu)) > 0 ){
				//YA EXISTE UN ANALISIS
				$link = '<a class="ajax-load" rel-method="post" rel-data="analysis='.$sha256.'" data-href="report.php" href="#">aqui</a>';
				echo json_encode(array("error"=>"Ya existe un analisis de este fichero. Puedes verlo ".$link."."));
				die();
			}else{
				//Realizar analisis
				if ($extFile == "exe"){
					$analisis = new AnalisisExe();
					$exem = new ExeManager($filePath);
					$analisis->setSections($exem->getSections());
					$analisis->setDlls($exem->getDlls());
					$analisis->setCode($exem->getCode());
				}else if($extFile == "apk"){
					$analisis = new AnalisisApk();
					$apkm = new ApkManager($filePath);
					$am = $apkm->getAndroidManifest($hm->SHA1());
					$home = $db->getOption("home"); 
					$manifest = $home . "/" . $am;
					$analisis->setManifest($manifest);
					$abspath = $db->getOption("abspath");
					$fileManifest = $abspath . DIRECTORY_SEPARATOR . $am;
					$p = $apkm->getPermisos($fileManifest);
					$analisis->setPermisos($p);
				}else{
					$analisis = new Analisis();
					die("No es una extension permitida");
				}
				if(isset($_SESSION["iu"])){
					$analisis->setIdUser($_SESSION["iu"]);
				}else{
					$analisis->setIdUser(NULL);
				}
				//Se comprime la muestra
				$nameZip = $hm->SHA1().".zip";
				$zip = new ZipManager($nameZip, $filePath);
				$zip->comprimir();

				$uri = $_SERVER['REQUEST_URI'];
				$url = $_SERVER['SERVER_NAME'] . substr($uri, 0, strpos($uri, '/actions/')) . '/muestras/' . $nameZip;
				$analisis->setFilename($basename);
				$analisis->setSize(filesize($filePath));
				$analisis->setMd5($hm->MD5());
				$analisis->setSha1($hm->SHA1());
				$analisis->setSha256($hm->SHA256());
				$analisis->setMuestra($url);
				$analisis->setPwdMuestra($zip->getPassword());

				$analysis_id = $db->saveAnalisis($analisis);
				if($analysis_id == DB_ANALYSIS_EXISTS){
					$link = '<a class="ajax-load" rel-method="post" rel-data="analysis='.$sha256.'" data-href="report.php" href="#">aqui</a>';
					echo json_encode(array("error"=>"Ya existe un analisis de este fichero. Puedes verlo ".$link."."));
				}else{
					//Borrar el fichero subiido
					unlink($filePath);
					echo json_encode(array("pwd" => $zip->getPassword(), "muestra"=> $url, "url"=>'<a class="ajax-load" rel-method="post" rel-data="analysis='.$sha256.'" data-href="report.php" href="#">análisis</a>'));

					
				}
			}
		   	
		} else {
			echo json_encode(array("error"=> $_FILES[$paramFile]['error']));
		}

	


 ?>