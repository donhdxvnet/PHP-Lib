<?php

class BackendTexts {

	private static /*string*/ $_langue;
	private static /*string*/ $_path;
	private static /*string*/ $_modulepath;
	
	private static /*BackendText*/ $_instance;

	private static /*DOMDocument*/ $_text;
	private static /*DOMDocument*/ $_moduletext = null;
	
	private function __construct($path, $langue){
		self::$_langue = $langue;
		self::$_path = $path;
		if ( !file_exists($path))
			throw new Exception(__METHOD__.' '.$path. ' not found.');
		$dom = new DOMDocument();
		$dom->load($path);
		self::$_text = $dom;
	}

	public static /*BackendText*/ function getInstance($path, $langue='en'){
		if ( is_null(self::$_instance)){
			self::$_instance = new BackendTexts($path, $langue);
		}
		return self::$_instance;
	}
	
	public static /*BackendText*/ function loadModuleText($modulepath){
		self::$_modulepath = $modulepath;
		if (!file_exists($modulepath)){
			self::$_moduletext = null;
			return;
		}
		$dom = new DOMDocument();
		$dom->load($modulepath);
		self::$_moduletext = $dom;
	}

	public static /*void*/ function get($id, $params=null){
		if ( is_null(self::$_instance))
			throw new Exception(__METHOD__.' : class static non initialisée.');
		$bEmptyModuleText = is_null(self::$_moduletext);
		if(!$bEmptyModuleText){
			$xpath = new DOMXPath(self::$_moduletext);
			$query = "//texts/text[@id='$id']/langue[@name='".self::$_langue."']";
			$el = $xpath->query($query);
		}
		if ($bEmptyModuleText || $el->length < 1){
			$xpath = new DOMXPath(self::$_text);
			$query = "//texts/text[@id='$id']/langue[@name='".self::$_langue."']";
			$el = $xpath->query($query);
			if ($el->length < 1){
				//throw new Exception(__METHOD__.' : id => '.$id.' not found.');
				return $id;
			}
		}
		$s = $el->item(0)->nodeValue;
		
		if ( !is_null($params)){
			if ( is_array($params))
				return vsprintf($s, $params);
			if ( is_string($params))
				return sprintf($s, $params);
		}
		return $s;	
	}
}
?>
