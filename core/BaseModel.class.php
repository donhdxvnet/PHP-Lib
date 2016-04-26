<?php

require_once realpath(dirname(__FILE__).'/ConnectionHelper.class.php');
require_once realpath(dirname(__FILE__).'/../files/EventParamsXML.class.php');
require_once realpath(dirname(__FILE__).'/Config.class.php');
require_once realpath(dirname(__FILE__).'/../utils/FSUtils.class.php');
require_once realpath(dirname(__FILE__).'/../utils/StringUtils.class.php');
require_once realpath(dirname(__FILE__).'/../utils/XSLT.class.php');
require_once realpath(dirname(__FILE__).'/../utils/ConversionUtils.class.php');

class BaseModel {

    protected $_oUser;
    protected $_oClient;
    protected $_oEvent;
    protected $_path;
    protected $_rootPath;
    protected $_rootUrl;

    public function __construct($oUser, $oClient, $oEvent, $cPath){
        $this->_oUser = $oUser;
        $this->_oClient = $oClient;
        $this->_oEvent = $oEvent;
        $rPath = Config::getEventDataPath($oClient, $oEvent);
        $this->_rootUrl = Config::getEventSharedDataUrl($oClient, $oEvent);
        $this->initialisePath($rPath, $cPath);
    }

    private function initialisePath($rPath, $cPath){
        if ( !file_exists($cPath))
            throw new Exception(__METHOD__.' : le fichier '.$cPath.' n\'existe pas');

        $this->_rootPath = $rPath;
        $dom = new DOMDocument();
        $dom->load($cPath);
        $elPath = $dom->getElementsByTagName('path');
        foreach ($elPath as $path)
        {
        	$name = $path->getAttribute('name');
            $value = $path->getAttribute('value');
            $this->_path[$name] = $value;
        }
    }

    protected function getAbsolutePath($name){
        return $this->_rootPath.$this->getRelatifPath($name);
    }

    protected function getRelatifPath($name){
        if (empty($name))
                throw new Exception('Le parametre $name est vide.');

        if ( !isset($this->_path[$name]))
                throw new Exception('Le parametre '.$name.' n\existe pas');
        return $this->_path[$name];
    }

    public function getAllLangs()
    {
        $con = ConnectionHelper::getConnection($this->_oClient->dbName);
        $sql = "SELECT Langues_idLangue, _default, idoplangue
                    FROM Operations_Langues
                    WHERE Operations_idOperation=?";
        $stmt = $con->prepare($sql);
        $stmt->execute(array($_SESSION['oEvent']->idOperation));
        $result = array();
        foreach ($stmt->fetchAll(PDO::FETCH_OBJ) as $lang) {
            $lang->code = $lang->Langues_idLangue;
            $result[] = $lang;
        }
        return $result;
    }

	/**
	* Fonction de recuperation de la langue par default de l'op�ration
	* @return StdObject { 
	*      Langues_idLangue = (string) 'en' ; //label de la langue par defaut
	*      idoplangue = (int) 1 ; //id de la langue par defaut 
	*      }
	*/
	
	public function getDefaultLanguage()
	{
		$con = ConnectionHelper::getConnection($this->_oClient->dbName);
		$sql = "
			SELECT Langues_idLangue, idoplangue
			FROM Operations_Langues
			WHERE Operations_idOperation = ?
			AND _default = 1
		";
		$stmt = $con->prepare($sql);
		$stmt->execute(array($_SESSION['oEvent']->idOperation));
		$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		
		if (count($result) > 0) $result = $result[0];
		else {
			$result = new stdClass();
			$result->Langues_idLangue = "";
			$result->idoplangue = "";			
		}
		return $result;
	}
	
	public function getOUser() { return $this->_oUser; }	
	public function getOEvent() { return $this->_oEvent; }
	public function getOClient() { return $this->_oClient; }
	
}
?>
