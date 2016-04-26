<?php
 
class LogDebug {
	
	/**
	 * Path vers le fichier de log.
	 *
	 * @var string
	 */
	private $_fileName;
	
	/**
	 * Ressource du fichier de log.
	 *
	 * @var <resource>
	 */
	private $_des;
	
	/**
	 * Constructeur.
	 *
	 * @param string $fileName
	 */
	public function __construct($fileName){
		$this->_fileName = $fileName;
		$this->_des = FALSE;
	}
	
	/**
	 * Ecrit un message dans le fichier de log.
	 *
	 * @param string $message
	 */
	public function write($message)
	{
		if ($this->_des == FALSE) $this->open();
		fwrite($this->_des, $message . "\n");
	}
	
	/**
	 * Methode qui permet de visualiser la veleur d'une variable.
	 * 
	 * @param string $message 
	 * @param Object $var :
	 */
	public function showValue($message,$var){
		ob_start();
		var_export($var);
		$str=ob_get_contents();
		ob_end_clean();
		$this->write('variable '.$message.':\n'.$str);
	}
	
	/**
	 * Ouvre le fichier de log en mode append.
	 *
	 */
	private function open(){
		$this->_des = fopen($this->_fileName, 'a+');
	}
	
	/**
	 * Ferme le fichier de log.
	 *
	 */
	private function close(){
		if ( $this->_des != FALSE){
			fclose($this->_des);
			$this->_des = FALSE;
		}
	}
	
	/**
	 * Destructeur. 
	 * Ferme la connexion au fichier de log.
	 *
	 */
	public function __destruct(){
		$this->close();
	}
}
?>
