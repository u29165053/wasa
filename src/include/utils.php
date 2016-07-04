<?php 
/**
 * Clase que contiene métodos estáticos que permiten resolver problemas concretos.
 *
 * @package includes
 * @author Diego Fernández Valero <u29165053@extremail.ru>
 */
require_once('database.php');

class Utils{


	

	/**
	 * Algoritmo de cifrado y descifrado RC4. 
	 * Fuente original:
	 * 		https://gist.github.com/farhadi/2185197
	 * 
	 * @license Public Domain
	 * @param string Clave utilizada
	 * @param string Cadena a cifrar o descifrar
	 * @return string Resultado de la operación de cifrado/descifrado
	 */
	public static function rc4($key, $str) {
		$s = array();
		for ($i = 0; $i < 256; $i++) {
			$s[$i] = $i;
		}
		$j = 0;
		for ($i = 0; $i < 256; $i++) {
			$j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
		}
		$i = 0;
		$j = 0;
		$res = '';
		for ($y = 0; $y < strlen($str); $y++) {
			$i = ($i + 1) % 256;
			$j = ($j + $s[$i]) % 256;
			$x = $s[$i];
			$s[$i] = $s[$j];
			$s[$j] = $x;
			$res .= $str[$y] ^ chr($s[($s[$i] + $s[$j]) % 256]);
		}
		return $res;
	}

	/**
	 * Devuelve la clave simétrica de RC4
	 *
	 */
	private static function getSecretKey(){
		$db = new DAO();
		$key = $db->getOption("secret_key_rc4");
		unset($db);
		return $key;
	}


	/**
	 *
	 * Wrapper que utiliza la función rc4 para cifrar datos y devolverlos
	 * en representación base64. Utiliza la clave de la aplicación, definida
	 * en $_GLOBALS["SECRET_KEY"]
	 *
	 * @param string Datos a cifrar
	 * @return string Cadena en Base64 con los datos cifrados
	 *
	 */
	public static function rc4_encrypt($str){
		$key = Utils::getSecretKey();
		return base64_encode(Utils::rc4($key, $str));
	}

	/**
	 *
	 * Wrapper que utiliza la función rc4 para descifrar datos y devolverlos
	 * en representación base64. Utiliza la clave de la aplicación, definida
	 * en $_GLOBALS["SECRET_KEY"]
	 *
	 * @param string Cadena en Base64 con los datos a descifrar
	 * @return string Datos descifrados
	 *
	 */
	public static function rc4_decrypt($str){
		$key = Utils::getSecretKey();
		return Utils::rc4($key, base64_decode($str));
	}

	/**
	 * Obtiene una cadena aleatoria
	 * @param 
	 */
	public static function randomString($length = 16) {
    	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
} 

?>