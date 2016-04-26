<?php

abstract class Config {

	private static $_sharedDataRootPath;

	private static $_dbHost;

	private static $_webRootUrl;

	private static $_sitemapRootUrl;

	private static $_webRootPath;

	private static $_isInit = false;

	private static $_datasVersion;

	private static $_ccVersion;
	
	private static $_ccLastMod;
	
	private static $_ccCopyright;

	private static $_eventMode;

	private static $_previewUrl;

	private static $_sitemapUrl;
	
	private static $_eventId;

	private static function init(){
		if ( ! self::$_isInit){
			include 'switch_host.php';
			switch($switch_host_profile) {
				case 'devpt':
					self::$_sharedDataRootPath = '';
					self::$_dbHost = 'localhost';
					self::$_webRootUrl = '';
					self::$_sitemapRootUrl = '';
					self::$_webRootPath = '';
					self::$_eventMode = 'dev';
					if (isset($_SESSION["oClient"])) {
						$event = Context::getModel('event');					
						$infos = $event::getById($_SESSION["oClient"], self::$_eventId);
						self::$_previewUrl = self::$_webRootUrl . strtolower($_SESSION['oClient']->nomStream) . $infos->video_root_dir; //Not PROD		
						self::$_sitemapUrl = self::$_sitemapRootUrl . strtolower($_SESSION['oClient']->nomStream) . $infos->video_root_dir; //Not PROD		
						//self::$_previewUrl = "http://" . $infos->prod_domain . $infos->web_root_dir; //PROD (Test)	
					}
				break;

				case 'preprod':
					self::$_sharedDataRootPath = '';
					self::$_dbHost = 'localhost';
					self::$_webRootUrl = '';
					self::$_sitemapRootUrl = '';
					self::$_webRootPath = '';
					self::$_eventMode = 'validation';
					if (isset($_SESSION["oClient"])) {
						$event = Context::getModel('event');
						$infos = $event::getById($_SESSION["oClient"], self::$_eventId);
						self::$_previewUrl = self::$_webRootUrl . strtolower($_SESSION['oClient']->nomStream) . $infos->video_root_dir; //Not PROD
						self::$_sitemapUrl = self::$_sitemapRootUrl . strtolower($_SESSION['oClient']->nomStream) . $infos->video_root_dir; //Not PROD		
					}		
				break;

				case 'staging':
					self::$_sharedDataRootPath = '';
					self::$_dbHost = 'localhost';
					self::$_webRootUrl = '';
					self::$_sitemapRootUrl = '';
					self::$_webRootPath = '';
					self::$_eventMode = 'dev';
					if (isset($_SESSION["oClient"])) {
						$event = Context::getModel('event');
						$infos = $event::getById($_SESSION["oClient"], self::$_eventId);
						self::$_previewUrl = self::$_webRootUrl . strtolower($_SESSION['oClient']->nomStream) . $infos->video_root_dir; //Not PROD										
						self::$_sitemapUrl = self::$_sitemapRootUrl . strtolower($_SESSION['oClient']->nomStream) . $infos->video_root_dir; //Not PROD										
					}		
				break;

				case 'prod':
					self::$_sharedDataRootPath = '';
					self::$_dbHost = 'localhost';
					self::$_webRootUrl = '';
					self::$_sitemapRootUrl = '';
					self::$_webRootPath = '';
					self::$_eventMode = 'production';
					if (isset($_SESSION["oClient"])) {
						$event = Context::getModel('event');
						$infos = $event::getById($_SESSION["oClient"], self::$_eventId);						
						self::$_previewUrl = "http://" . $infos->prod_domain . $infos->web_root_dir; //PROD
						self::$_sitemapUrl = self::$_sitemapRootUrl . strtolower($_SESSION['oClient']->nomStream) . $infos->video_root_dir; //Not PROD
					}
				break;
			}
			self::$_isInit = true;
		}
	}

	public static function getEventMode(){
		self::init();
		return self::$_eventMode;
	}
			
	public static function getSitemapUrl($eventId)
	{
		self::$_isInit = false;
		self::$_eventId = $eventId;
		self::init();
		return self::$_sitemapUrl;
	}
			
	public static function getPreviewUrl($eventId)
	{
		self::$_isInit = false;
		self::$_eventId = $eventId;
		self::init();
		return self::$_previewUrl;
	}

	public static function getSharedDataRootPath(){
		self::init();
		return self::$_sharedDataRootPath;
	}

	public static function getDbHost(){
		self::init();
		return self::$_dbHost;
	}

        public static function getWebRootPath(){
            self::init();
            return self::$_webRootPath;
        }

	public static function getWebRootUrl(){
		self::init();
		return self::$_webRootUrl;
	}

	public static function getClientDataPath($oClient){
		self::init();
		return Config::getSharedDataRootPath().$oClient->nomStream.'/';
	}

        public static function getClientSkinDataPath($clientName=null){
		self::init();
                if($clientName==null) return 'datas/default/';
		return 'datas/'.$clientName.'/';
	}

    public static function getClientPath($oClient){
		self::init();
		return Config::getWebRootPath().$oClient->nomStream.'/';
    }

	public static function getEventDataPath($oClient, $oEvent){
		self::init();
		list($y,$m,$d) = explode('-', $oEvent->date);
		$rep = $y.$m.$d.$oEvent->sous_ope;
		return Config::getClientDataPath($oClient).$rep.'/';
	}

	public static function getEventSharedDataUrl($oClient, $oEvent){
		self::init();
		list($y,$m,$d) = explode('-', $oEvent->date);
		$rep = $y.$m.$d.$oEvent->sous_ope;
		return Config::getWebRootUrl().$oClient->nomStream.'/'.$rep.'/datas/'.$rep.'/';
	}

    public static function getEventPath($oClient, $oEvent){
		self::init();
		list($y,$m,$d) = explode('-', $oEvent->date);
		$rep = $y.$m.$d.$oEvent->sous_ope;
		return Config::getClientPath($oClient).$rep.'/';
    }

    public static function getCurrentDatasVersion(){
            //echo __METHOD__;
            if ( is_null(self::$_datasVersion)){
                    $dom = new DOMDocument();
                    $dom->load(realpath(dirname(__FILE__).'/../../../config/config.xml'));
                    self::$_datasVersion = $dom->documentElement->getAttribute('datas_version');
            }
            return self::$_datasVersion;
    }
	
	public static function getCurrentCCVersion(){
            //echo __METHOD__;
            if ( is_null(self::$_ccVersion)){
                    $dom = new DOMDocument();
                    $dom->load(realpath(dirname(__FILE__).'/../../../config/config.xml'));
                    self::$_ccVersion = $dom->documentElement->getAttribute('cc_version');
            }
            return self::$_ccVersion;
    }
	
	public static function getCurrentCCLastMod(){
            //echo __METHOD__;
            if ( is_null(self::$_ccLastMod)){
                    $dom = new DOMDocument();
                    $dom->load(realpath(dirname(__FILE__).'/../../../config/config.xml'));
                    self::$_ccLastMod = $dom->documentElement->getAttribute('cc_last_mod');
            }
            return self::$_ccLastMod;
    }
	
	public static function getCurrentCCCopyright(){
            //echo __METHOD__;
            if ( is_null(self::$_ccCopyright)){
                    $dom = new DOMDocument();
                    $dom->load(realpath(dirname(__FILE__).'/../../../config/config.xml'));
                    self::$_ccCopyright = $dom->documentElement->getAttribute('cc_copyright');
            }
            return self::$_ccCopyright;
    }

    public static function getStage(){
        include 'switch_host.php';
        return $switch_host_profile;
    }
}
?>
