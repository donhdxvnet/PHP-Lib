<?php

class Ip2Location {
	
	private $_ip;
	private $_ipInt;
	private $_ipFrom;
	private $_ipTo;
	private $_countryShort;
	private $_country;
	private $_region;
	private $_city;
	
	/**
	 * Construit une nouvelle instance d'Ip2Location.
	 * 
	 * @Exception InvalidParameterTypeException  
	 * @param ip l'ip de la forme w.x.y.z
	 * @param ipInt l'ip au format ip2location
	 * @param ipFrom debut de zone d'ip
	 * @param ipTo fin de zone d'ip
	 * @param countryShort l'abreviation du pays
	 * @param country le pays
	 * @param region la region
	 * @param city la ville
	 */
	public function __construct($ip, $ipInt, $ipFrom, $ipTo, $countryShort, $country, $region="", $city="")
	{	
		if ( 	! is_string($ip) && 
				! is_string($ipInt) && 
				! is_string($ipFrom) &&
				! is_string($ipTo) &&
				! is_string($countryShort) &&
				! is_string($country) &&
				! is_string($region) &&
				! is_string($city)){
			throw new InvalidParameterTypeException();
		}
		$this->_ip = $ip;
		$this->_ipInt = $ipInt;
		$this->_ipFrom = $ipFrom;
		$this->_ipTo = $ipTo;
		$this->_countryShort = $countryShort;
		$this->_country = $country;
		$this->_region = $region;
		$this->_city = $city;
	}
	
	/**
	 * Représentation en chaine de caractère de l'instance.
	 */
	public function __toString(){
		return $this->_ip.' '.$this->_ipInt.' '.$this->_ipFrom.' '.$this->_ipTo.' '.
				$this->_countryShort.' '.$this->_country.' '.$this->_region.' '.$this->_city;
	}
	
	public function getIP(){ return $this->_ip;}
	public function getIPInt(){ return $this->_ipInt;}
	public function getIPFrom(){ return $this->_ipFrom;}
	public function getIPTo(){ return $this->_ipTo;}
	public function getCountryShort(){ return $this->_countryShort;}
	public function getCountry(){ return $this->_country;}
	public function getRegion(){ return $this->_region;}
	public function getCity(){ return $this->_city;}
}
?>
