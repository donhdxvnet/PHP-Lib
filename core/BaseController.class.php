<?php

require_once realpath(dirname(__FILE__).'/../core/Security.class.php');
require_once realpath(dirname(__FILE__).'/../core/Context.class.php');
require_once realpath(dirname(__FILE__).'/../core/BackendTexts.class.php');
require_once realpath(dirname(__FILE__).'/../utils/StringUtils.class.php');
require_once realpath(dirname(__FILE__).'/../utils/ArrayUtils.class.php');

class BaseController {

	protected static function getModulePath($name){
		return Context::getModulePath($name);
		}

	protected static function createAjaxResponse( $code , $msg){
		if(ob_get_contents())
			ob_end_clean();
		$a = new stdClass();
		$a->code = $code;
		$a->messages[] = $msg;
		header('Content-type: application/json');
		echo json_encode($a);
	}

	protected static function checkSession($update=false){
		if ( !isset($_SESSION['oUser'])){
			if ( $update){
				echo '<script>window.location.href = "index.php"</script>';
				exit;
			}else{
				self::createAjaxResponse(-1, '');
			}
		}
	}

	public static function log($mod, $msg, $id=0){
            $m = Context::getModel('core');
            $l = new Log();
            $l->idModule = $m::getIdModuleByName($mod);
            if($id>0)
                $l->idUser = $id;
            elseif ( isset($_SESSION['oUser']))
                $l->idUser = $_SESSION['oUser']->id;

            if ( isset($_SESSION['oClient']))
                    $l->idClient= $_SESSION['oClient']->id;
					
			if ( isset($_SESSION['oEvent']))
                    $l->idEvent= $_SESSION['oEvent']->idOperation;		
					
            $l->msg = $msg;
            $m::log($l);
	}

	protected static function checkRequestVar($name,$min=0, $max = null){
		if ( ! isset($_REQUEST[$name]) && $min > 0)
			throw new Exception('Property "'.$name.'" undefined!');

		if ( ! isset($_REQUEST[$name]) && $min == 0)
			return '';

		$val = $_REQUEST[$name];
		if ( ! is_array($val)){
			$val = trim($val);
			$size = strlen($val);
			if ( $size < $min  )
				throw new Exception(BackendTexts::get('alert_property').' "'.$name.'" '.BackendTexts::get('alert_short').', '.$min.' '.BackendTexts::get('alert_char_minimum'));

			if ( !is_null($max) && strlen($val) > $max)
				throw new Exception(BackendTexts::get('alert_property').' "'.$name.'" '.BackendTexts::get('alert_long').', '.$max.' '.BackendTexts::get('alert_char_maximum'));
		}

		return $val;
	}

	protected static function checkRequestVar2($name, $type, $min=0, $max = 50)
                {
		if ( ! isset($_REQUEST[$name]) && $min > 0)
			throw new Exception('Property "'.$name.'" undefined!');

		if ( ! isset($_REQUEST[$name]) && $min == 0){
			$_REQUEST[$name] = "";
		}
		$val = $_REQUEST[$name];
		if ( ! is_array($val)){
			$val = trim($val);
			$size = strlen($val);
			if ( $size < $min  )
                            {
                               try
                                 {
                                    $field = BackendTexts::get('field_'.$name);
                                 }
                                 catch (Exception $e)
                                 {
                                    throw new Exception(BackendTexts::get('alert_short_property',array($name,$min)));
                                 }
                                    throw new Exception(BackendTexts::get('alert_short_property',array($field,$min)));

                            }
			if ( !is_null($max) && strlen($val) > $max)
                            {
                               try
                                 {
                                    $field = BackendTexts::get('field_'.$name);
                                 }
                                 catch (Exception $e)
                                 {
                                    throw new Exception(BackendTexts::get('alert_property').' "'.$name.'" '.BackendTexts::get('alert_long').', '.$max.' '.BackendTexts::get('alert_char_maximum'));
                                 }
                                 throw new Exception($field.' '.BackendTexts::get('alert_long').', '.$max.' '.BackendTexts::get('alert_char_maximum'));


                            }
		}
		switch($type){
			case 'int':
				if (!is_numeric($val))
					throw new Exception($name .'='.$val.' is not a numeric : '.gettype($val) );
			break;

			case 'string':
				if ( !is_string($val))
					throw new Exception($name .' is not a string');
			break;

			case 'boolean':
				if( $val === "1"
					|| $val === 1
					|| $val === true
					|| strtolower($val) === 'true'
					|| strtolower($val) === 'on'
					|| strtolower($val) === 'yes'
					|| strtolower($val) === 'oui')
					$val = true;
				else
					$val = false;
			break;

			case 'email':
				if (! StringUtils::isValidEmail($val))
                                    {
                                        try
                                         {
                                            $field = BackendTexts::get('field_'.$name);
                                         }
                                         catch (Exception $e)
                                         {
                                            throw new Exception($name.' '.BackendTexts::get('baseController_isNotValidEmail'));
                                         }
                                            throw new Exception($field .' '.BackendTexts::get('baseController_isNotValidEmail'));
                                    }
			break;

                        case 'date':
                               if (!StringUtils::isValidDate($val))
                                    {
                                        try
                                         {
                                            $field = BackendTexts::get('field_'.$name);
                                         }
                                         catch (Exception $e)
                                         {
                                            throw new Exception($name.' '.BackendTexts::get('baseController_isNotValidDate'));
                                         }
                                            throw new Exception($field .' '.BackendTexts::get('baseController_isNotValidDate'));
                                    }
                        break;
                        
                        case 'timecode':
                               if (!StringUtils::isValidTimecode($val))
					throw new Exception($name .'= '.$val.' '.BackendTexts::get('baseController_isNotValidTimeCode'));
                        break;

			default:
				throw new Exception('type '.$ps[1].' unknown.');
		}
		return $val;
	}


	protected static function validateRequest($params){
		$r = array();
		foreach ($params as $ps) {
			list($name, $type, $min, $max) = $ps;

			if ( ! isset($_REQUEST[$name]) && $min > 0)
				throw new Exception('Property "'.$name.'" undefined!');

			if ( ! isset($_REQUEST[$name]) && $min == 0){
				$r[$name] = '';
				continue;
			}
			$val = $_REQUEST[$name];
			if ( ! is_array($val)){
				$val = trim($val);
				$size = strlen($val);
				if ( $size < $min  )
					throw new Exception('Property "'.$name.'" too short, '.$min.' char minimum!');

				if ( !is_null($max) && strlen($val) > $max)
					throw new Exception('Property "'.$name.'" too long, '.$max.' char maximum!');
			}
			switch($type){
				case 'int':
					if (!is_numeric($val))
						throw new Exception($name .'='.$val.' is not a numeric : '.gettype($val) );
				break;

				case 'string':
					if ( !is_string($val))
						throw new Exception($name .' is not a string');
				break;

				case 'checkbox':
					if ( $val != 'on' && $val != 'off')
						throw new Exception($name .' has an invalid checkbox value');
					$val = ($val =='on')? true: false;
				break;

				case 'email':
					if (! StringUtils::isValidEmail($val))
						throw new Exception($name .' is not a valid email');
				break;

				default:
					throw new Exception('type '.$ps[1].' unknown.');
			}
			$r[$name] = $val;
		}
		return $r;
	}
}
?>
