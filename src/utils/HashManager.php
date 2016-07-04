<?php 
/**
 * Clase que contiene métodos para calcular diferentes hashes de un fichero.
 * Esta clase se ayuda de un script desarrollado en Python 2.x, por lo que es
 * necesario indicar la ruta de este script.
 *
 * La ruta de dicho archivo python, se debe indicar en el constructor de la clase,
 * en la variable "pythonScript"
 * 
 * @package    utils
 * @author     Diego Fernández Valero 
 */

class HashManager{

	/**
	 *
	 * Constructor de la clase. 
	 * Instancia la clase con el fichero del que se quiere obtener el hash
	 *
	 * @param string 	Ruta del fichero del que se obtendrá los hashes
	 */
	public function HashManager($file){
		$this->file = $file;
		$this->pythonScript = "\"".dirname(__FILE__)."/py/hash.py\"";
	}

	/**
	 *
	 * Obtiene el hash SHA1 del fichero indicado en el contructor
	 *
	 * @return string Hash SHA1 del fichero
	 */
	public function SHA1(){
		return sha1_file($this->file, FALSE);
	}

	/**
	 *
	 * Obtiene el hash MD5 del fichero indicado en el contructor
	 *
	 * @return string Hash MD5 del fichero
	 */
	public function MD5(){
		return md5_file($this->file);
	}

	/**
	 *
	 * Obtiene el hash SHA256 del fichero indicado en el contructor
	 *
	 * @return string Hash SHA256 del fichero
	 */
	public function SHA256(){
		$cmd = sprintf("python \"%s\" sha256 \"%s\"", $this->pythonScript, $this->file);
		$command = escapeshellcmd($cmd);
		$output = shell_exec($command);
		$output = str_replace("\n", "", $output);
		return trim($output);
	}

	/**
	 *
	 * Obtiene el hash SHA512 del fichero indicado en el contructor
	 *
	 * @return string Hash SHA512 del fichero
	 */
	public function SHA512(){
		$cmd = sprintf("python \"%s\" sha512 \"%s\"", $this->pythonScript, $this->file);
		$command = escapeshellcmd($cmd);
		$output = shell_exec($command);
		return $output;
	}
}

 ?>