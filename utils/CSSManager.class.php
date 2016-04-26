<?php

abstract class CSSManager {
	
	/**
	 * Ecrit l'appel javascript avec les nouvelles variables.
	 *
	 * @param sFolderPath 
	 * @param sCSSFile
	 */
	public static function writeScript($sFolderPath, $sCSSFile = 'styles.css'){
		if(file_exists($sFolderPath.$sCSSFile))
			return '<script>CSSManager.load("'.$sFolderPath.$sCSSFile.'");</script>';
		else
			return '<script>CSSManager.unload();</script>';
	}

}
?>
