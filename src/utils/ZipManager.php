<?php
/**
 *
 * Clase encargada de gestionar las compresiones de las muestras de malware. Además, generá una contraseña 
 * aleatoria, con la que se comprime cada fichero .zip
 *
 * @package		utils
 * @author 		Diego Fernández Valero
 */

class ZipManager{

	/**
	 * Constructor de la clase.
	 * Genera el password del zip en el que se comprimirá la muestra de malware
	 *
	 * @param string 	Nombre del fichero comprimido que se generará
	 * @param string 	Ruta del fichero que se quiere comprimir
	 * 
	 */
	public function ZipManager($outname, $file){
		$this->folder = dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR."muestras".DIRECTORY_SEPARATOR;;
		$this->output = $this->folder.DIRECTORY_SEPARATOR.$outname;
		$this->file = $file;
        $this->password = substr( md5(microtime()), 1, 8);
	}

	/**
	 *
	 * Obtiene el password con el que se ha comprimido el fichero
	 * @return string 	Password del ZIP
	 */
	public function getPassword(){
		return $this->password;
	}

	/**
	 *
	 * Comprime el archivo en un fichero .zip con una contraseña aleatoria,
	 * calculada en el constructor de la calse.
	 *
	 */
	public function comprimir(){
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        	//Servidor Windows
        	$tool = dirname(__FILE__).'\\tools\\7z.exe';
        	$cmd = sprintf("%s a -y -p%s %s %s > nul", $tool, $this->password, $this->output, $this->file);
		    system($cmd);
		} else {
			//Servidor Linux
			$cmd = sprintf("zip --password %s %s %s >/dev/null 2>/dev/null", $this->password, $this->output, $this->file);
		    system($cmd);
		}
	}


	public function comprimirDirectorio(){
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
        	//Servidor Windows
        	$tool = dirname(__FILE__).'\\tools\\7z.exe';
        	$cmd = sprintf("%s a -y %s %s > nul", $tool, $this->output, $this->file);
		    system($cmd);
		} else {
			//Servidor Linux
			$cmd = sprintf("zip %s %s >/dev/null 2>/dev/null", $this->output, $this->file);
		    system($cmd);
		}
	}

	public function getOutput(){
		return $this->output;
	}
	
}
?>