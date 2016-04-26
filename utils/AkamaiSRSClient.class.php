<?php
 
class AkamaiSRSClient {
	
	/**
	 * Url du service web akamai
	 *
	 * @var string
	 */
	private $_url = "https://control.akamai.com/nmrws/services/StreamingReportService";
	
	/**
	 * Le login de connexion d'akamai
	 *
	 * @var string
	 */
	private $_login;
	
	/**
	 * Le passe de connexion au WS d'akamai
	 *
	 * @var string
	 */
	private $_passe;
	
	/**
	 * Le service soap utilise pour appeler les methodes.
	 *
	 * @var SoapClient
	 */
	private $_service;
	
	/**
	 * Constructeur.
	 *
	 * @param string $login
	 * @param string $passe
	 * @exception SoapFault
	 */	
	public function __construct( $login, $passe){
		$this->_login = $login;
		$this->_passe = $passe;
		$this->_service = new SoapClient(null,array(	
				'location' => $this->_url, 
				'uri'=> $this->_url,
				'login' =>$login,
				'password'=>$passe));
	}
	
	/**
	 * Methode qui effectue une requete vers le WS d'akamai
	 *
	 * @param string $function_name : le nom de la methode a appeler
	 * @param array $params :  tableau de parametre
	 * @return string
	 */
	public function call($function_name,$params){
		return $this->_service->__soapCall($function_name,$params);
	}
}
?>
