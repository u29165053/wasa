<?php 
/** 
 * Clase que modela un objeto AnálisisApk, que se corresponde con la tabla de análisis en la base
 * de datos, para ficheros de tipo 1. 
 *
 * @package includes\dto
 * @author Diego Fernández Valero <u29165053@extremail.ru>
 */
class AnalisisApk extends Analisis{

	/**
	 * Constructor de la clase. Es un constructor vacío.
	 */
	public function AnalisisApk(){
		
	}

	/**
	 * Obtiene la ruta del fichero AndoridManifest.xml
	 * @return string Ruta del fichero AndroidManifest
	 */
 	public function getManifest(){
 		return $this->manifest;
    }

    /** 
     * Obtiene la lista de permisos de la aplicación.
     * @return array Lista con los permisos
     */
	public function getPermisos(){
		return $this->permisos;
	}
	
	/** 
	 * Establece la ruta del fichero AndroidManifest.xml
	 * @param string Ruta del fichero AndroidManifest
	 */
	public function setManifest($value){
 		$this->manifest = $value;
    }

    /**
     * Establece la lista con los permisos de la aplicación.
     * @param array Lista con los permisos de la aplicación.
     */
	public function setPermisos($value){
		$this->permisos = $value;
	}
	
}


 ?>


