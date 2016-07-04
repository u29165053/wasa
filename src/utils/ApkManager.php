<?php 
/**
 * Clase que contiene métodos para analizar un fichero APK.
 * Esta clase se ayuda de un script desarrollado en Python 2.x, por lo que es
 * necesario indicar la ruta de este script.
 *
 * 
 * @package    utils
 * @author     Diego Fernández Valero <u29165053@extremail.ru>
 */

class ApkManager{

	/**
	 *
	 * Constructor de la clase. 
	 * Instancia la clase con el fichero que se quiere analizar
	 *
	 * @param string 	Ruta del fichero a analizar
	 */
	public function ApkManager($file){
		$this->file = $file;
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        	//Servidor Windows
			$this->script = "\"".dirname(__FILE__)."/tools/apktool.bat\"";
			$this->silent = " > NUL";
		} else {
			//Servidor Linux
			$this->script = "\"".dirname(__FILE__)."/tools/apktool.sh\"";
			$this->silent = " > /dev/null";
		}

		$this->permisosScript = dirname(__FILE__)."/py/apkFile.py";


	}

	/** 
	 * Decompila el fichero APK y lo descomprime en un directorio. Tras esto, 
	 * devuelve la ruta del fichero AndroidManifest.xml
	 * @param string Directorio donde se descomprimirá el fichero APK, y se guardarán 
	 * los archivos internos de la APK.
	 * @return string Ruta del fichero AndroidManifest.xml
	 */
	public function getAndroidManifest($output){
		$name = basename($this->file, ".apk");
		$dir = dirname($this->file);
		$out = $dir . DIRECTORY_SEPARATOR . $output;
		$command = $this->script . " d \"" . $this->file . "\" -o " . $out  . $this->silent;
		system($command); 
		return "uploads/".$output."/AndroidManifest.xml";
	}

	/**
	 * Obtiene los permisos solicitados por una aplicación, en base a un fichero AndroidManifest.
	 * @param string Ruta del fichero AndroidManifest
	 * @return string JSON que modela la lista de ficheros.
	 */
	public function getPermisos($fileManifest){
		$cmd = sprintf("python \"%s\" \"%s\"", $this->permisosScript, $fileManifest);
		$command = escapeshellcmd($cmd);
		return shell_exec($command);
	}

	
}

 ?>