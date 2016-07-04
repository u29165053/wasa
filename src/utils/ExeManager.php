<?php 
/**
 * Clase que contiene métodos para analizar un fichero PE, en formato EXE.
 * Esta clase se ayuda de un script desarrollado en Python 2.x, por lo que es
 * necesario indicar la ruta de este script.
 *
 * La ruta de dicho archivo python, se debe indicar en el constructor de la clase,
 * en la variable "pythonScript"
 * 
 * @package    utils
 * @author     Diego Fernández Valero 
 */

class ExeManager{

	/**
	 *
	 * Constructor de la clase. 
	 * Instancia la clase con el fichero que se quiere analizar
	 *
	 * @param string 	Ruta del fichero a analizar
	 */
	public function ExeManager($file){
		$this->file = $file;
		$this->pythonScript = dirname(__FILE__)."/py/exeFile.py";
	}


	/**
	 * Obtiene las secciones de código de un fichero PE, previamente cargado desde el constructor
	 * @return string JSON con la lista de secciones de código
	 */
	public function getSections(){
		$cmd = sprintf("python %s %s sections", $this->pythonScript, $this->file);
		$command = escapeshellcmd($cmd);
		$output = shell_exec($command);
		return $output;
	}

	/**
	 * Obtiene la lista de librerías DLL y sus funciones importadas, utilizadas por el fichero
	 * declarado en el constructor.
	 * @return string JSON con la lista de librerías DLL y funciones importadas
	 */
	public function getDlls(){
		$cmd = sprintf("python %s %s dll", $this->pythonScript, $this->file);
		$command = escapeshellcmd($cmd);
		$output = shell_exec($command);
		return $output;
	}

	/**
	 * Obtiene el código ensamblador del fichero declarado en el constructor.
	 * @return string Código ASM del fichero
	 */	
	public function getCode(){
		$cmd = sprintf("python %s %s code", $this->pythonScript, $this->file);
		$command = escapeshellcmd($cmd);
		$output = shell_exec($command);
		return $output;
	}

	
}

 ?>