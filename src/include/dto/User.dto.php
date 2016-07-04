<?php 
/**
 * Clase que modela un objeto User, que se corresponde con la tabla de usuarios en la base
 * de datos.
 *
 * @package includes\dto
 * @author Diego Fernández Valero <u29165053@extremail.ru>
 */
class User{

	/**
	 * Constructor de la clase. 
	 * @param int Identificador del usuario
	 * @param string Nombre de usuario
	 * @param string Correo electrónico.
	 */
	public function User($id, $username, $email){
		$this->id = $id;
		$this->username = $username;
		$this->email = $email;
	}

	/**
	 * Obtiene el identificador del usuario
	 * @return int Identificador de usuario
	 */
	public function getId(){
		return $this->id;
	}

	/**
	 * Obtiene el nombre de usuario
	 * @return string Nombre de usuario
	 */
	public function getUsername(){
		return $this->username;
	}

	/**
	 * Obtiene el email del usuario
	 * @return string correo electrónico del usuario
	 */
	public function getEmail(){
		return $this->email;
	}

	/**
	 * Obtiene la cadena por defecto que se mostrará cuando se intente imprimir este objeto por pantalla.
	 * @return string Cadena que modela este objeto.
	 */
	public function __toString(){
		return $this->getUsername();
	}
}

 ?>