<?php 
/**
 * Clase utilizada para interactuar con la base de datos. Se trata de un DAO,
 * es decir, un Data Access Object, que encapsula todas las funcionalidades de
 * acceso a datos.
 * @package includes
 * @author Diego Fernández Valero <u29165053@extremail.ru>
 */



/**
 * Prefijo utilizado en las tablas de la base de datos.
 * Se utiliza como una medida de seguridad que evite preveer el nombre
 * de las tablas.
 */
define('DB_PREFIX', 'tfg_');

// IMPORTS

require_once('utils.php');
require_once('settings.php');
require_once('dto/User.dto.php');
require_once('dto/Analisis.dto.php');
require_once('dto/AnalisisExe.dto.php');
require_once('dto/AnalisisApk.dto.php');


class DAO{

	/**
	 * Host que almacena la base de datos
	 */
	private static $host = 'localhost';
	/**
	 * Usuario de la base de datos
	 */
	private static $user = 'root';
	/**
	 * Contraseña de la base de datos
	 */
	private static $password = '';
	/**
	 * Nombre de la base de datos
	 */
	private static $database = 'tfgunir';
	/**
	 * Prefijo de las tablas de la base de datos
	 */
	private static $prefix = 'tfg_';

	/**
	 * Array con las tablas que contiene la base de datos
	 */
	private static $tables = array(
			"users" => DB_PREFIX."users",
			"options" => DB_PREFIX."options",
			"analisis" => DB_PREFIX."analysis",

		);


	/**
	 * Constructor de la clase. Abre una conexión con la base de datos.
	 */
	public function DAO(){
		$this->instance = mysqli_connect(DAO::$host, DAO::$user, DAO::$password, DAO::$database);
	}

	/**
	 * 
	 * Escapa una consulta SQL para evitar problemas con los caracteres
	 * 
	 * @param string Query SQL a escapar
	 * @return string Query SQL escapada.
	 */
	public function escape($query){
		return mysqli_real_escape_string($this->instance, $query);
	}


	/**
	 *
	 * Obtiene el valor de una opción de la tabla de opciones.
	 *
	 * @param string Nombre de la opción
	 * @return string Valor de la opción
	 */
	public function getOption($name){
		$stm = $this->instance->prepare("SELECT value FROM ".DAO::$tables["options"]." WHERE name=?");
		$stm->bind_param("s", $name);
		$stm->execute();
		$result = $stm->get_result();
		$row = $result->fetch_assoc();
		return $row['value'];

	}

	/**
	 *
	 * Establece el valor de una opción de la tabla de opciones.
	 *
	 * @param string Nombre de la opción
	 * @param string Valor de la opción
	 * @return boolean True si se ha guardado correctamente, False en caso contrario
	 */
	public function setOption($name, $value){
		$option = $this->getOption($name);
		if(is_null($option)){
			$stm = $this->instance->prepare("INSERT INTO ".DAO::$tables["options"]." VALUES (?,?)");
			$stm->bind_param("ss", $name, $value);
			return $stm->execute();
		}else{
			if($option != $value){
				$stm = $this->instance->prepare("UPDATE ".DAO::$tables["options"]." SET value = ? WHERE name = ?");
				$stm->bind_param("ss", $value, $name);
				return $stm->execute();
			}
		}
		
	}

	/**
	 *
	 * Obtiene la semilla para hacer el hash SHA1 de la contraseña.
	 *
	 * @param string Nombre de usuario 
	 * @return string Semilla utilizada por ese usuario
	 */
	public function getSaltFromUser($username){
		$stm = $this->instance->prepare("SELECT salt FROM ".DAO::$tables["users"]." WHERE username=?");
		$stm->bind_param("s", $username);
		$stm->execute();
		$result = $stm->get_result();
		$row = $result->fetch_assoc();
		return $row['salt'];

	}

	/**
	 *
	 * Obtiene una instancia del usuario si las credenciales son correctas.
	 *
	 * @param string Nombre de usuario
	 * @param string Contraseña 
	 * @return User Devuelve una instancia del usuario si el nombre de usuario y contraseña
	 * 				son correctos. Devuelve NULL en caso contrario.
	 */
	public function getUser($username, $password){
		$salt = $this->getSaltFromUser($username);
		$hash = sha1($salt.$password.$salt);
		$stm = $this->instance->prepare("SELECT * FROM ".DAO::$tables["users"]." WHERE username=? AND password=?");
		$stm->bind_param("ss", $username, $hash);
		$stm->execute();
		$result = $stm->get_result();
		$row = $result->fetch_assoc();
		if ($row['id'] > 0){
			$user = new User($row['id'], $row['username'], $row['email']);
			return $user;
		}else{
			return NULL;
		}
		return $row['id'] > 0 ? $row['id'] : NULL;
	}
	

	/**
	 * Comprueba si un nombre de usuario ya está en uso.
	 *
	 * @param string Nombre de usuario
	 * @return boolean Devuelve TRUE si el nombre de usuario existe, o FALSE en caso contrario.
	 */
	private function existsUsername($username){
		$stm = $this->instance->prepare("SELECT id FROM ".DAO::$tables["users"]." WHERE username=?");
		$stm->bind_param("s", $username);
		$stm->execute();
		$result = $stm->get_result();
		$row = $result->fetch_assoc();
		return $row['id'] > 0 ? $row['id'] : NULL;
	}

	/**
	 * Comprueba si un email de usuario ya está en uso.
	 *
	 * @param string Dirección de correo
	 * @return boolean Devuelve TRUE si el email existe, o FALSE en caso contrario.
	 */
	private function existsEmail($email){
		$stm = $this->instance->prepare("SELECT id FROM ".DAO::$tables["users"]." WHERE email=?");
		$stm->bind_param("s", $email);
		$stm->execute();
		$result = $stm->get_result();
		$row = $result->fetch_assoc();
		return $row['id'] > 0 ? $row['id'] : NULL;
	}


	/**
	 * Almacena un usuario en la base de datos.
	 * 
	 * @param array Array con los datos del usuario. Contiene las claves 'username', 'email' y 'password'
	 * @return int Devuelve el identificador de base de datos del usuario guardado.
	 */
	public function saveUser($data){
		if( $this->existsUsername($data["username"])){
			return DB_USERNAME_EXISTS;
		} 
		if ( $this->existsEmail($data["email"]) ){
			return DB_EMAIL_EXISTS;
		}

		$salt = Utils::randomString(16);
		$password = sha1($salt . $data["password"] . $salt);
		$stm = $this->instance->prepare("INSERT INTO ".DAO::$tables["users"]." (`username`,`email`,`password`,`salt`) VALUES (?,?,?,?)");
		$stm->bind_param("ssss", $data["username"], $data["email"], $password, $salt);
		$stm->execute();
		$id = $this->instance->insert_id;
		$this->instance->commit();
		return $id;
	}

	/**
	 * Comprueba si un análisis ya se encuentra en la base de datos.
	 *
	 * @param string Hash SHA256 del fichero a analizar
	 * @param int Identificador de base de datos del usuario que realiza el analisis, o NULL si es un análisis anónimo.
	 * @return boolean Devuelve TRUE si el análisis existe, o FALSE en caso contrario.
	 */
	public function existsAnalysis($sha256, $idUser = NULL){
		$stm = $this->instance->prepare("SELECT id FROM ".DAO::$tables["analisis"]." WHERE sha256=? AND (id_user IS NULL OR id_user = ?)");
		$stm->bind_param("si", $sha256, $idUser);
		$stm->execute();
		$result = $stm->get_result();
		$row = $result->fetch_assoc();
		return $row['id'] > 0 ? $row['id'] : NULL;
	}

	/**
	 * Almacena un análisis de un fichero en la base de datos.
	 * 
	 * @param Analisis Es un objeto de la clase Analisis. Puede ser una subclase, como AnalisisExe o AnalisisApk
	 * @return int Devuelve el identificador de base de datos del análisis guardado.
	 */
	public function saveAnalisis($analisis){
		$currentId = $this->existsAnalysis($analisis->getSha256(), $analisis->getIdUser());
		if ( $currentId > 0 ){
			return DB_ANALYSIS_EXISTS;
		}

		if($analisis instanceof AnalisisExe){
			$sqlQuery = "INSERT INTO ".DAO::$tables["analisis"]." (`id_user`, `sha1`, `md5`, `sha256`, `filename`, `size`, `muestra`, `pwd_muestra`, `type`, `sections`, `dlls`, `code`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, ?, ?,?);";
			$stm = $this->instance->prepare($sqlQuery);
			$stm->bind_param("issssisssss", $analisis->getIdUser(), $analisis->getSha1(), $analisis->getMd5(), $analisis->getSha256(), 
				$analisis->getFilename(), $analisis->getSize(), $analisis->getMuestra(), $analisis->getPwdMuestra(), $analisis->getSections(), $analisis->getDlls(), $analisis->getCode());
			$stm->execute();
			$id = $this->instance->insert_id;
			$this->instance->commit();
			return $id;
		}else if($analisis instanceof AnalisisApk){
			$sqlQuery = "INSERT INTO ".DAO::$tables["analisis"]." (`id_user`, `sha1`, `md5`, `sha256`, `filename`, `size`, `muestra`, `pwd_muestra`, `type`, `manifest_path`, `permisos`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ?);";
			$stm = $this->instance->prepare($sqlQuery);
			$stm->bind_param("issssissss", $analisis->getIdUser(), $analisis->getSha1(), $analisis->getMd5(), $analisis->getSha256(), 
				$analisis->getFilename(), $analisis->getSize(), $analisis->getMuestra(), $analisis->getPwdMuestra(), $analisis->getManifest(), $analisis->getPermisos());
			$stm->execute();
			$id = $this->instance->insert_id;
			$this->instance->commit();
		}else{
			return NULL;
		}
	}


	/**
	 * Obtiene los análisis públicos realizados
	 *
	 * @return Array Devuelve una lista de objetos Análisis, con los datos del análisis realizado.
	 */
	public function getPublicAnalysis(){
		$output = array();
		$stm = $this->instance->prepare("SELECT * FROM ".DAO::$tables["analisis"]." WHERE id_user IS NULL");
		$stm->execute();
		$result = $stm->get_result();
		while ($data = $result->fetch_assoc()){
		    $output[] = $data;
		}
		return $output;
	}

	/**
	 * Obtiene los análisis privados realizados
	 * @param int Identificado de base de datos del usuario propietario del análisis.
	 * @return Array Devuelve una lista de objetos Análisis, con los datos del análisis realizado.
	 */
	public function getPrivateAnalysis($id){
		$output = array();
		$stm = $this->instance->prepare("SELECT * FROM ".DAO::$tables["analisis"]." WHERE id_user = ?");
		$stm->bind_param("i", $id);
		$stm->execute();
		$result = $stm->get_result();
		while ($data = $result->fetch_assoc()){
		    $output[] = $data;
		}
		return $output;
	}

	/**
	 * Obtiene un análisis en concreto. 
	 *
	 * @param string Hash SHA256 del fichero que se ha analizado
	 * @return Analisis Devuelve un objeto Análisis con los datos del análisis realizado
	 */
	public function getAnalysis($sha256){
		$stm = $this->instance->prepare("SELECT * FROM ".DAO::$tables["analisis"]." WHERE sha256=?");
		$stm->bind_param("s", $sha256);
		$stm->execute();
		$result = $stm->get_result();
		$data = $result->fetch_assoc();
		if($data["type"] == 0){
			//EXE
			$an = new AnalisisExe();
			$an->setId($data["id"]);
			$an->setIdUser($data["id_user"]);
			$an->setSha1($data["sha1"]);
			$an->setMd5($data["md5"]);
			$an->setSha256($data["sha256"]);
			$an->setFilename($data["filename"]);
			$an->setSize($data["size"]);
			$an->setMuestra($data["muestra"]);
			$an->setPwdMuestra($data["pwd_muestra"]);
			$an->setType($data["type"]);
			$an->setSections(json_decode($data["sections"]));
			$an->setDlls(json_decode($data["dlls"]));
			$an->setCode($data["code"]);
			//$an->setStrings($data["strings"]); TO-DO
			return $an;

		}else if($data["type"] == 1){
			//APK			
			$an = new AnalisisApk();
			$an->setId($data["id"]);
			$an->setIdUser($data["id_user"]);
			$an->setSha1($data["sha1"]);
			$an->setMd5($data["md5"]);
			$an->setSha256($data["sha256"]);
			$an->setFilename($data["filename"]);
			$an->setSize($data["size"]);
			$an->setMuestra($data["muestra"]);
			$an->setPwdMuestra($data["pwd_muestra"]);
			$an->setType($data["type"]);
			$an->setManifest($data["manifest_path"]);
			$an->setPermisos(json_decode($data["permisos"]));

			return $an;
		}
	}


	/**
	 * Cierra la conexión a la base de datos
	 */
	public function __destruct(){
		mysqli_close($this->instance);
	}

	/**
	 * Cierra la conexión a la base de datos
	 */
	public function close(){
		mysqli_close($this->instance);
	}
}

 ?>