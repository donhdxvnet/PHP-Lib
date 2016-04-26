<?php

 abstract class Crypt {
	
 	const KEY = "je represente ceux qui utilisent leur cervelle a la detruire"; 
 	
 	/**
 	 *  Methode qui permet de crypter une chaine de caractere.
 	 * 
 	 * @param string $clear
 	 * @return string
 	 */
	public final static function encrypt( $clear ){
		$td = self::init(self::KEY);
		/* Chiffre les données */
		$encrypted =   mcrypt_generic($td, $clear);
		self::close($td);
		return  base64_encode($encrypted);
	}
	
	/**
	 * Methode qui permet de decrypter une chaine de caractere encrypter avec la methode Crypt::encrypt().
	 * 
	 * @param string $encrypted
	 * @return string
	 */
	public final static function decrypt( $encrypted ){
		$td = self::init(self::KEY);
		/* Déchiffre les données */
		$clear = mdecrypt_generic($td,base64_decode( $encrypted));
		self::close($td);
		return trim($clear);
	}
	
	private final static function init($key){
		$td = mcrypt_module_open(MCRYPT_BLOWFISH,'' ,'ecb','');
		/* Crée le VI et détermine la taille de la clé */
		$iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_DEV_RANDOM);
		$ks = mcrypt_enc_get_key_size($td);
		/* Crée la clé */
		$key = substr(md5($key), 0, $ks);
		/* Initialise le module de chiffrement pour le déchiffrement */
		mcrypt_generic_init($td, $key, $iv);
		return $td;
	}
	
	private final static function close($td){
		/* Libère le gestionnaire de déchiffrement, et ferme le module */
		mcrypt_generic_deinit($td);
		mcrypt_module_close($td);
	}
}
?>
