<?php
 
abstract class DateUtils {
	
	/**
	 * Format un timestamp en date et heure à la française.
	 *
	 * @param timestamp 
	 * @return la date et l'heure formaté.
	 */
	public static function formatTimestamp($timestamp, $langue = 'fr'){
		if ( $langue == 'fr')
			return date("d/m/Y H:i:s",round($timestamp/1000));
		else
		 	return date("Y/m/d H:i:s",round($timestamp/1000));
	}
			
	/**
	 * 		formate un champ de type datetime en timestamp.
	 * 		@static
	 * 		@return double;
	 */
	public static function dateTime2Timestamp($dateTime){
		if ( trim($dateTime) == '')
			throw new Exception('DateUtils::dateTime2Timestamp(), paramtre vide');
		if ( ! self::validateDateTimeFormat($dateTime))
			throw new Exception('Bad Date and time Format : '. $dateTime);
			
		$aDateTime = explode(" ",$dateTime);
		$date = $aDateTime[0];
		$time = $aDateTime[1];
		$aDate = explode("-",$date);
		$year = $aDate[0];
		$month = $aDate[1];
		$day = $aDate[2];
		$aTime = explode(":",$time);
		$hour = $aTime[0];
		$minute = $aTime[1];
		$second = $aTime[2];
		return mktime( $hour, $minute, $second, $month, $day, $year)*1000;
	}
	
	public static function validateDateTimeFormat($dateTime){
		return preg_match("/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/",$dateTime);
	}
	
	
	public static function validateDateFormat($date){
		return preg_match("/\d{4}-\d{2}-\d{2}/",$date);
	}
	
	
	/**
	 * 		Formate un champ de type date en timestamp.
	 * 		@static
	 * 		@return double;
	 */
	public static function date2Timestamp($date){
		if ( ! self::validateDateFormat($date))
			throw new Exception('Bad Date Format : '. $date);
		$aDate = explode("-",$date);
		$year = $aDate[0];
		$month = $aDate[1];
		$day = $aDate[2];
		return mktime( null, null, null,$month, $day, $year)*1000;
	}

	/**
	 * 		Convertit un timestamp en datetime.
	 * 		Le timestamp doit comporter 13 chiffres.
	 * 		@static
	 * 		@return String
	 */
	public static function timestamp2DateTime($timestamp){
		$len =  strlen(trim($timestamp));
		return date("Y-m-d H:i:s",substr(trim($timestamp),0,$len -3));
		
	}

	/**
	 * 		Convertit un timestamp en date.
	 * 		Le timestamp doit comporter 13 chiffres.
	 * 		@static
	 * 		@return String
	 */
	public static function timestamp2Date($timestamp){
		$len =  strlen(trim($timestamp));
		return date("Y-m-d",substr(trim($timestamp),0,$len-3));
	}
	
	/**
	 * Retourne la difference entre deux datetime type mysql en miliseconde.
	 * 
	 */
	public static function deltaWhithMysqlDateTime($dt1, $dt2){
		$ts1 = self::dateTime2Timestamp($dt1);
		$ts2 = self::dateTime2Timestamp($dt2);
		return $ts1 - $ts2;
	}

	public static function time2sec($str){
		if ( $str == "") return 0;
		list($hour,$minute,$sec)=explode(":",$str);
		return ($hour*3600)+($minute*60)+$sec;
	}

	public static function sec2time($secondes, $format=':'){
		$min = floor($secondes/60);
		$sec  = $secondes %60;
		$hour = floor ($min/60);
		$minute = $min % 60;
		if ( $format == ':')
			return self::format00($hour).':'.self::format00($minute).':'.self::format00($sec);
		else
			return self::format00($hour).'h '.self::format00($minute).'m '.self::format00($sec).'s';
	}

	private static function format00($val){
		return (strlen($val) < 2)? '0'.$val : $val;
	}
}
?>
