<?php 
/** 
 * Clase que modela un objeto Análisis, que se corresponde con la tabla de análisis en la base
 * de datos. Es la clase padre de otros análisis, por lo que define los atributos comunes a todos
 * los análisis, independientemente de qué fichero se analice.
 * 
 * @package includes\dto
 * @author Diego Fernández Valero <u29165053@extremail.ru>
 */
class Analisis{


	/**
	 * Constructor de la clase. Es un constructor vacío.
	 */
	public function Analisis(){
		
	}

	/**
	 * Obtiene el identificador del análisis.
	 * @return int Identificador del análisis.
	 */
 	public function getId(){
 		return $this->id;
    }

   	/**
	 * Obtiene el identificador de usuario del análisis.
	 * @return int Identificador de usuario del análisis.
	 */
	public function getIdUser(){
		return $this->id_user;
	}

   	/**
	 * Obtiene el Hash SHA1 del análisis.
	 * @return string Hash SHA1 del análisis.
	 */
	public function getSha1(){
		return $this->sha1;
	}

   	/**
	 * Obtiene el Hash MD5 del análisis.
	 * @return string Hash MD5 del análisis.
	 */
	public function getMd5(){
		return $this->md5;
	}

   	/**
	 * Obtiene el Hash SHA256 del análisis.
	 * @return string Hash SHA256 del análisis.
	 */
	public function getSha256(){
		return $this->sha256;
	}

	/**
	 * Obtiene el Nombre del fichero del análisis.
	 * @return string Nombre del fichero del análisis.
	 */
	public function getFilename(){
		return $this->filename;
	}

	/**
	 * Obtiene el Tamaño del fichero del análisis.
	 * @return int Tamaño dle fichero del análisis.
	 */
	public function getSize(){
		return $this->size;
	}

	/**
	 * Obtiene la ruta de la muestra del fichero (comprimida) del análisis.
	 * @return string Ruta de la muestra.
	 */
	public function getMuestra(){
		return $this->muestra;
	}

	/**
	 * Obtiene el password del archivo comprimido de la muestra del fichero.
	 * @return string Password del archivo comprimido.
	 */
	public function getPwdMuestra(){
		return $this->pwd_muestra;
	}

	/**
	 * Obtiene el tipo de fichero. El tipo 0 es un fichero EXE, y el 1 un fichero APK.
	 * @return int Tipo de fichero del análisis.
	 */
	public function getType(){
		return $this->type;
	}

	/**
	 * Establece el identificador del análisis
	 * @param int Identificador del análisis
	 */
	public function setId($value){
 		$this->id = $value;
    }

	/**
	 * Establece el identificador del usuario del análisis
	 * @param int Identificador del usuario del análisis
	 */
	public function setIdUser($value){
		$this->id_user = $value;
	}

	/**
	 * Establece el Hash SHA1 del análisis
	 * @param string Hash SHA1 del análisis
	 */
	public function setSha1($value){
		$this->sha1 = $value;
	}

	/**
	 * Establece el Hash MD5 del análisis
	 * @param string Hash MD5 del análisis
	 */
	public function setMd5($value){
		$this->md5 = $value;
	}

	/**
	 * Establece el Hash SHA256 del análisis
	 * @param string Hash SHA256 del análisis
	 */
	public function setSha256($value){
		$this->sha256 = $value;
	}

	/**
	 * Establece el nombre del fichero del análisis
	 * @param string Nombre del fichero del análisis
	 */
	public function setFilename($value){
		$this->filename = $value;
	}

	/**
	 * Establece el tamaño del fichero del análisis
	 * @param int Tamaño del fichero del análisis
	 */
	public function setSize($value){
		$this->size = $value;
	}

	/**
	 * Establece la ruta de la muestra del fichero
	 * @param string Ruta de la muestra dle fichero comprimido
	 */
	public function setMuestra($value){
		$this->muestra = $value;
	}

	/**
	 * Establece la contraseña de la muestra del fichero
	 * @param string Contraseña de la muestra dle fichero comprimido
	 */
	public function setPwdMuestra($value){
		$this->pwd_muestra = $value;
	}

	/**
	 * Establece el tipo del fichero
	 * @param string Tipo del fichero
	 */
	public function setType($value){
		$this->type = $value;
	}


	/**
	 * Obtiene la cadena por defecto que se mostrará cuando 
	 * se intente imprimir este objeto por pantalla
	 * @return string cadena que representa el objeto.
	 */
	public function __toString(){
		return $this->getId();
	}
}


 ?>


