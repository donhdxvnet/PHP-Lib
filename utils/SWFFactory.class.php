<?php

abstract class SWFFactory {
	
	/**
	 * Method: get
	 * 		Retourne un element <object ... pour y include le swf.
	 * 
	 * Parameters:
	 * 		$swfPath - string le chemin du fichier swf
	 * 		$params - array associatif parametre de type FlashVars
	 * 		$ssl - Boolean https ou http
	 * 		$width - int 
	 * 		$height - int
	 * 		$bgcolor - string La couleur de fond.
	 * 
	 * Exception:
	 * 		Exception - controle des parametres.
	 * 
	 * Return:
	 * 		HTMLObject 
	 */
	public static function get($swfPath, $params=null, $ssl=false, $width=400, $height=300, $bgcolor="#FFFFFF"){
		if ( ! is_string($swfPath))
			throw new Exception('$swfPath : Invalid Type Parameter, string required.');
		if ( ! is_int($width))
			throw new Exception('$width : Invalid Type Parameter, int required.');
		if ( ! is_int($height))
			throw new Exception('$height : Invalid Type Parameter, int required.');
		/*
		 if ( ! file_exists($swfPath))
			throw new Exception('file '.$swfPath.' not found.');
		*/

		$paramStr = '';
		
		$ret = '<object width="'.$width.'" height="'.$height.'"';
		if ( ! $ssl) 
			$ret .= ' codebase="http://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">';
		else
			$ret .= ' codebase="https://fpdownload.macromedia.com/get/flashplayer/current/swflash.cab">';
		$ret .= '<param name="movie" value="'.$swfPath.'" />';
		$ret .= '<param name="quality" value="high" />';
		
		if ( $params != null){
			$i = 0;
			foreach ($params as $k =>$v){
				$paramStr .= $k.'='.$v;
				if ( $i < (sizeof($params) - 1))
					$paramStr .= '&';
				$i++;
			}
			$ret .= '<param name="flashvars" value="'.$paramStr.'"/>';
		}
		
		$ret .= '<param name="bgcolor" value="'.$bgcolor.'" />';
		$ret .= '<param name="allowScriptAccess" value="sameDomain" />';
		if ( $params != null )
			$ret .= '<embed wmode="transparent" src="'.$swfPath.'" width="'.$width.'" height="'.$height.'" flashvars="'.$paramStr.'">';
		else
			$ret .= '<embed wmode="transparent" src="'.$swfPath.'" width="'.$width.'" height="'.$height.'">';
		$ret .= '</object>';
		return $ret;
	}
}
?>
