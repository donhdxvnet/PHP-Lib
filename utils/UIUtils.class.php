<?php

abstract class UIUtils {
	
	private static $picTRUE = 'check.png';
	
	private static $picFALSE = 'delete.png';
	
	public static function setBooleanPic($bool){
		if($bool) $picname = self::$picTRUE;
		else $picname = self::$picFALSE;
		return '<img src="assets/images/'. $picname .'" />';
	}
	
}
?>
