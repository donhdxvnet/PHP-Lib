<?php

class IPUtils {

	/**
	 * Récupere un nouvele objet ip2location a partir d'une IP.
	 * @param string
	 * @Exception InvalidParameterTypeException
	 * @Exception InvalidAdminConnectionException 
	 * @return Ip2Location
	 */
	public static function getLocationByIP($con, $ip)
	{
		$ip2l;		
		if ( !is_string($ip)){
			throw new Exception("Invalid Parameter Type");
		}
		$sql ="
			SELECT ipFROM, ipTO, countrySHORT, countryLONG, ipREGION, ipCITY 
			FROM ip2location
			WHERE ipFROM <= ? ORDER BY ipFROM DESC LIMIT 1
		";
		$stmt = $con->prepare($sql);
		
		$ipInt = self::Dot2LongIP($ip);
		$stmt->execute(array($ipInt));
		$rs = $stmt->fetchAll(PDO::FETCH_OBJ);
		if ( sizeof($rs) < 1)
			return null;
		$row = $rs[0];
		$ip2l = new Ip2Location($ip,$ipInt, $row->ipFROM, $row->ipTO, $row->countrySHORT,
											$row->countryLONG, $row->ipREGION, $row->ipCITY );
		return $ip2l;
	}
	
	/**
	 * Methode privé qui permet de transformer l'ip en numeric. Indispensable a l'utilistion de ip2location.
	 * @param String: l'ip a transformer.
	 * @return int : l'ip sous forme numeric( selon les critere d'ip2location)
	 */
	private static function Dot2LongIP ($IPaddr){
	    if ($IPaddr == "") {
	        return 0;
	    } else {
	        $ips = explode(".", $IPaddr);
	        return ($ips[3] + $ips[2] * 256 + $ips[1] * 256 * 256 + $ips[0] * 256 * 256 * 256);
	    }
	}

        /**
         * Fonction: ipToBin à partir d'une ip, retourne la représentation binaire en string.
         * @param <type> $ip String
         * @return <type> String : représentation en chaine de caractères de l'ip sous forme binaire
         */
        public static function ipToBin($ip){
               $str = '';
               if (is_numeric($ip)) {
               $str = sprintf("%u", floatval($ip));
                } else {
                $str = explode("/",$ip);
                $str = $str[0];
                $str = sprintf("%u", floatval(ip2long($str)));
                }
                $str = base_convert($str, 10, 2);

                if (($result = (32 - strlen($str))) > 0) {
                        return str_repeat("0", $result).$str;
                }
                return $str;
        }

        /**
         * check_auth_ip_address
         * validation de l'acces pour une adresse ip
         * @param <type> $authaddr tableau d'adresses ip
         * @param <type> $ip l'ip à vérifier
         * @return <type> Boolean
         */
        public static function check_auth_ip_address($authaddr,$ip){

                $ip_addr = self::ipToBin($ip); // (/24)

                foreach ($authaddr as $line) {
                        $network = explode("/", $line);
                        $net_addr = self::ipToBin($network[0]);
                        $cidr = isset($network[1])? $network[1] : 32;

                        if ($cidr == "") {
                                $cidr = 32;
                        }
                        
                        $t0 = substr($net_addr, 0, $cidr);
                        $t1 = substr($ip_addr, 0, $cidr);

                        if ($t0 === $t1) {
                                return true;
                        }
                }
                return false;
        }

}
?>
