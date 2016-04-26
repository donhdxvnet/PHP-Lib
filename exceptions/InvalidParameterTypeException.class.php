<?php

class InvalidParameterTypeException extends Exception {
	
	static private $msg = '%s : le type %s est incorrect.  %s demandé';
	
	public function __construct($name, $type_received, $type_asked){
		$message =sprintf(self::$msg, $name, $type_received, $type_asked);
		parent::__construct($message,0);
	}
}
?>
