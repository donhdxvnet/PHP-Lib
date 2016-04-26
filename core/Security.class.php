<?php
 
require_once dirname(__FILE__).'/../dao/UserModuleDAO.class.php'; 

abstract class Security {

    public static function checkAdminByPass(){
        self::checkIncludeByPass();
        self::forceUTF8Header();
		self::checkModuleAccess();
    }
    
    public static function checkClientByPass(){
        self::checkIncludeByPass();
        if(!isset($_SESSION['oClient'])) exit;
        self::forceUTF8Header();
		self::checkModuleAccess();
    }

    public static function checkEventByPass(){
        self::checkIncludeByPass();
        if(!isset($_SESSION['oClient'])) exit;
        if(!isset($_SESSION['oEvent'])) exit;
        self::forceUTF8Header();
		self::checkEventModuleAccess();
    }

    private static function checkIncludeByPass(){
        if(!defined('IN_FIRM_ADMIN')) exit;
    }
	
    private static function checkEventModuleAccess(){
		if(isset($_SESSION['noRight'])){
			unset($_SESSION['noRight']);
			return;
		}
		if(isset($_SESSION['oUser']) && !$_SESSION['oUser']->isAdmin && isset($_SESSION['oEvent']) && isset($_SESSION['oClient']) && isset($_SESSION['oUser']->id) && isset($_SESSION['oModule']->id)){
			$idModule = $_SESSION['oModule']->id;
			$idUser = $_SESSION['oUser']->id;
			$idOpe = $_SESSION['oEvent']->idOperation;
			$idClient = $_SESSION['oClient']->id;
			$mDAO = new ModuleDAO();
			if(!$mDAO->isEventModuleAccessibleForUser($idUser, $idModule, $idOpe, $idClient)){
				$_SESSION['noRight'] = '';
				include Context::getModulePath('core') . '/views/no_right.php';
				exit;
			}
		}
    }
	
    private static function checkModuleAccess(){
		if(isset($_SESSION['noRight'])){
			unset($_SESSION['noRight']);
			return;
		}
		if(isset($_SESSION['oUser']) && !$_SESSION['oUser']->isAdmin && isset($_SESSION['oUser']->id) && isset($_SESSION['oModule']->id)){
			$idModule = $_SESSION['oModule']->id;
			$idUser = $_SESSION['oUser']->id;
			$umDAO = new UserModuleDAO();
			if(!$umDAO->isModuleAccessibleForUser($idUser, $idModule)){
				$_SESSION['noRight'] = '';
				include Context::getModulePath('core') . '/views/no_right.php'; 
				exit;
			}
		}
    }

    private static function forceUTF8Header(){
        if(!headers_sent()) header('Content-type: text/html; charset=UTF-8');
    }
}
?>
