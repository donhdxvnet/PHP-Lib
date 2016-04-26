<?php

class Context
{    
    private static /*array<stdClass>*/ $_models;
	private static $cPath;

	public static function getConfig($version=null)
	{
		if ($version == null && isset($_SESSION["datas_version"])) $version = $_SESSION["datas_version"];
		self::$cPath = dirname(__FILE__).'/../../../config/' . $version . '/config.xml';
		$dom = new DOMDocument();
        $dom->load(self::$cPath);      
		return new DOMXPath($dom);
    }

	public static function getOption($version, $mname, $oname)
	{	
		$xpath = self::getConfig($version);
        $l = $xpath->query('//config/modules/module[@name="'.$mname.'"]/option[@name="'.$oname.'"]');		
        if ($l->length < 1) return false;
		else return true;
	}

    public static function getModel($name)
	{      
		$xpath = self::getConfig();
        $l = $xpath->query('//config/modules/module[@name="'.$name.'"]');
        if ( $l->length < 1)
            throw new Exception(BackendTexts::get("exception_mod_problem") . $name);
        $n = $l->item(0);
        $class = $n->getAttribute('model');
        $mversion = $n->getAttribute('version');
        $mpath = dirname(__FILE__).'/../../../modules/'.$name.'/'.$mversion.'/'.$class.'.class.php';		
        require_once $mpath;
        
        return new $class($_SESSION['oUser'], $_SESSION['oClient'], $_SESSION['oEvent'], self::$cPath);
    }

	/* Recuperer la version du module */
	public static function getVersion($name)
	{
		$xpath = self::getConfig();
        $l = $xpath->query('//config/modules/module[@name="'.$name.'"]');
        if ( $l->length < 1)
            throw new Exception(BackendTexts::get("exception_mod_problem") . $name);
        $n = $l->item(0);       
        $mversion = $n->getAttribute('version');
		
		return $mversion; 				
	}

    public static function getModuleUrl($name)
		{
		$xpath = self::getConfig();
        $l = $xpath->query('//config/modules/module[@name="'.$name.'"]');
        if ( $l->length < 1)
            throw new Exception(BackendTexts::get("exception_mod_problem") . $name);
        $n = $l->item(0);
        $class = $n->getAttribute('model');
        $mversion = $n->getAttribute('version');
        return 'modules/'.$name.'/'.$mversion.'/';
    }
	
    public static function getModulePath($name)
	{
		$xpath = self::getConfig();
        $l = $xpath->query('//config/modules/module[@name="'.$name.'"]');
        if ( $l->length < 1)
            throw new Exception(BackendTexts::get("exception_mod_not_exists") . $name);
        $n = $l->item(0);
        $class = $n->getAttribute('model');
        $mversion = $n->getAttribute('version');
        return dirname(__FILE__).'/../../../modules/'.$name.'/'.$mversion.'/';
    }
}
?>
