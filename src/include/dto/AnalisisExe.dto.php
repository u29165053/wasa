<?php 
/**
 * Clase que modela un objeto AnálisisExe, que se corresponde con la tabla de análisis en la base
 * de datos, para ficheros de tipo 0. 
 *
 * @package includes\dto
 * @author Diego Fernández Valero <u29165053@extremail.ru>
 */
class AnalisisExe extends Analisis{

	/**
	 * Constructor de la clase. Es un constructor vacío.
	 */
	public function AnalisisExe(){
		
	}

	/**
	 * Obtiene las cadenas del fichero.
	 * @return string Strings del fichero.
	 */
 	public function getStrings(){
 		return $this->strings;
    }

    /**
     * Obtiene la lista de ficheros dll y funciones.
     * @return array Lista de ficheros DLL y funciones
     */
	public function getDlls(){
		return $this->dlls;
	}

	/**
	 * Obtiene la lista de secciones de código del fichero
	 * @return array Lista de secciones
	 */
	public function getSections(){
		return $this->sections;
	}

	/**
	 * Obtiene el código ASM del fichero.
	 * @return string Código ASM
	 */
	public function getCode(){
		return $this->code;
	}
	
	/**
	 * Establece el valor de las cadenas ASCII del fichero
	 * @param string Cadenas del fichero
	 */
	public function setStrings($value){
 		$this->strings = $value;
    }

    /**
	 * Establece el valor de la lista de librerias y funciones importadas
	 * @param array Lista de DLL y funciones
	 */
	public function setDlls($value){
		$this->dlls = $value;
	}

	/**
	 * Establece el valor de las secciones de código del fichero
	 * @param array Lista de secciones de código del fichero
	 */
	public function setSections($value){
		$this->sections = $value;
	}

	/**
	 * Establece el valor del código ASM del fichero
	 * @param string Código ASM del fichero
	 */
	public function setCode($value){
		$this->code = $value;
	}
	
}


 ?>


