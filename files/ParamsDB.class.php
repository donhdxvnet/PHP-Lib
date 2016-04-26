<?php

require_once realpath(dirname(__FILE__).'/../utils/FSUtils.class.php');
require_once realpath(dirname(__FILE__).'/../core/Config.class.php');

abstract class ParamsDB {
	
	public static function write($client){
            //TODO : changer ce fichier en XML
		$path = Config::getSharedDataRootPath().$client->nomStream.'/includes_client/database.xml';
		$content = '<?xml version="1.0" encoding="UTF-8" ?>';
                $content .='<paramsDB>';
                $content .='<param name="db_host" value="'.Config::getDbHost().'"/>';
                $content .='<param name="db_name" value="'.$client->dbName.'"/>';
                $content .='<param name="db_user" value="'.$client->dbLogin.'"/>';
                $content .='<param name="db_passwd" value="'.$client->dbPassword.'"/>';
                $content .='</paramsDB>';
		FSUtils::write($path, $content);
	}
}
?>
