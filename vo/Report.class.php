<?php

class Report {
	
	/**
	 * L'identifiant du rapport.
	 *
	 * @var int
	 */
	private $_id;
	
	/**
	 * l'id du createur du xml ou du pdf.
	 *
	 * @var int 
	 */
	private $_creator;
	 
	/**
	 * l'id de l'operation.
	 *
	 * @var int
	 */  
	private $_idOperation;
	
	/**
	 * type de l'operation (vod ou live).
	 *
	 * @var int
	 */  
	private $_type;
	
	/**
	 * date de creation du fichier xml ou pdf.
	 *
	 * @var int
	 */ 
	private $_date;

	/**
	 * Chemin du fichier xml ou pdf.
	 *
	 * @var string
	 */
	private $_path;

	/**
	 * Type de l'enregistrement ( xml ou pdf ou swf).
	 *
	 * @var string
	 */
	private $_type_file;
	
	/**
	 * Nombre total de visiteur unique.
	 *
	 * @var int
	 */
	private $_visitorCount;
	 
	/**
	 * Nombre total de hit.
	 *
	 * @var int
	 */
	private $_visitorHit;
	
	/**
	 * Nombre total de fichier telecharg�.
	 *
	 * @var int
	 */
	private $_downloadCount;

	/**
	 * Temps moyen par connexion en secondes.
	 *
	 * @var int
	 */
	private $_tmc;
	
	/**
	 * Pourcentage de visiteur recurent.
	 *
	 * @var float
	 */
	private $_recurent;
	
	/**
	 * Pourcentage de visiteur interne.
	 *
	 * @var float
	 */
	private $_interne;
	
	/**
	 * Pourcentage de visiteurs identifi�.
	 *
	 * @var int
	 */
	private $_inscrit;
	
	/**
	 * Bande passante consomm�.
	 *
	 * @var int
	 */
	private $_bp;
	
	/**
	 * Type de population
	 * 
	 * @var string;
	 * 
	 */
	private $_population;
	
	
	/**
	 * Affectation de l'identifiant.
	 *
	 * @param int $id
	 */
	public function setId( $id ){
		$this->_id = $id;
	}
	
	/**
	 * Affectation de l'id du createur du xml ou du pdf.
	 *
	 * @param int $id
	 */
	public function setCreator( $id ){
		$this->_creator = $id;
	}
	
	/**
	 * Affectation de l'id de l'operation.
	 *
	 * @param int $id
	 */
	public function setOperation( $id ){
		$this->_idOperation = $id;
	}
	
	/**
	 * Affectation du type de l'operation.( vod ou live)
	 *
	 * @param string $type
	 */
	public function setType( $type ){
		$this->_type = $type;
	}
	
	/**
	 * Affectation de la date de creation du xml ou du pdf.
	 *
	 * @param int $sec
	 */	
	public function setDate( $date ){
		$this->_date = $date;
	}
	
	/**
	 * Affectation du chemin du xml ou du pdf.
	 *
	 * @param string $path
	 */
	public function setPath( $path ){
		$this->_path = $path;
	}
	
	/**
	 * Affectation du type d'enregistrement (xml, pdf ou swf).
	 *
	 * @param string $type
	 */
	public function setTypeFile( $type){
		$this->_type_file = $type;
	}
	
	/**
	 * Affectation du nombre de visiteur uniques.
	 *
	 * @param int $count
	 */
	public function setVisitorCount( $count ){
		$this->_visitorCount = $count;
	}
	
	/**
	 * Affectation du nombre de hit.
	 *
	 * @param int $count
	 */
	public function setVisitorHit( $count ){
		$this->_visitorHit = $count;
	}
	
	/**
	 * Affectation du nombre de telechargement.
	 *
	 * @param int $count
	 */
	public function setDownloadCount( $count ){
		$this->_downloadCount = $count;
	}
	
	/**
	 * Affectation du temps moyen de connexion.
	 *
	 * @param int $sec
	 */
	public function setTmc( $sec){
		$this->_tmc = $sec;
	}
	
	/**
	 * Affectation du nombre de visiteur recurent.
	 *
	 * @param float $percent
	 */
	public function setRecurentPercent( $percent ){
		$this->_recurent = $percent;
	}
	
	/**
	 * Affectation du pourcentage de visiteur recurent.
	 *
	 * @param float $percent
	 */
	public function setInternePercent( $percent ){
		$this->_interne = $percent;
	}
	
	/**
	 * Affectation du pourcentage de visiteur inscrit.
	 *
	 * @param float $percent
	 */
	public function setInscritPercent( $percent ){
		$this->_inscrit = $percent;
	}
	
	/**
	 * Affectation de la bande passante consomme.
	 *
	 * @param int $mo
	 */
	public function setBP( $mo ){
		$this->_bp = $mo;
	}
	
	
	public function setPopulation($s){
		$this->_population = $s;
	}
	
	/**
	 * Recuperation de l'id du rapport.
	 *
	 * @return int
	 */
	public function getId(){
		return $this->_id;
	}
	
	/**
	 * Recuperation de l'id du createur du xml ou du pdf.
	 *
	 * @return int
	 */
	public function getCreator(){
		return $this->_creator;
	}
	
	/**
	 * Recuperation de l'id de l'operation.
	 *
	 * @return int
	 */
	public function getOperation(){
		return $this->_idOperation;
	}
	
	/**
	 * Recuperation du type de l'operation ( live ou vod).
	 *
	 * @return string
	 */	
	public function getType(){
		return $this->_type;
	}
	
	/**
	 * Recuperation de la date de creation du xml ou du pdf.
	 *
	 * @return int
	 */
	public function getDate(){
		return $this->_date;
	}
	
	/**
	 * Recuperation du chemin du xml ou du pdf.
	 *
	 * @return int
	 */
	public function getPath(){
		return $this->_path;
	}
	
	/**
	 * R�cuperation du type de l'enregistrement.
	 *
	 * @return string
	 */
	public function getTypeFile(){
		return $this->_type_file;
	}
	
	/**
	 * Recuperation du nombre de visiteur unique.
	 *
	 * @return int
	 */
	public function getVisitorCount(){
		return $this->_visitorCount;
	}
	
	/**
	 * Recuperation du nombre de hit total.
	 *
	 * @return int
	 */
	public function getVisitorHit(){
		return $this->_visitorHit;
	}
	
	/**
	 * Recuperation du nombre de fichier telecharg�.
	 *
	 * @return int
	 */
	public function getDownloadCount(){
		return $this->_downloadCount;
	}
	
	/**
	 * Recuperation du temps moyen de connexion en secondes.
	 *
	 * @return int
	 */
	public function getTmc(){
		return $this->_tmc;
	}
	
	/**
	 * Recuperation du pourcentage de visiteur recurent.
	 *
	 * @return float
	 */	
	public function getRecurentPercent(){
		return $this->_recurent;
	}
	
	/**
	 * Recuperation du pourcentage de visiteur interne.
	 *
	 * @return float
	 */
	public function getInternePercent(){
		return $this->_interne;
	}
	
	/**
	 * Recuperation du pourcentage de visiteur inscrit.
	 *
	 * @return float
	 */
	public function getInscritPercent(){
		return $this->_inscrit;
	}
	
	/**
	 * Recuperation de la bande passante consomme.
	 *
	 * @return int
	 */
	public function getBP(){
		return $this->_bp;
	}
	
	/**
	 * gettter population
	 * @return string
	 */
	public function getPopulation(){
		return $this->_population;
	}
}
?>
