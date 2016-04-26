<?php

	class ConversionUtils
	{
		/*
		* Convertit Timecode en Secondes
		* @param {string} arg un Timecode sous la forme HH:MM:SS.D
		*/
		public static function TCtoSec($arg) {
			$sPatternTC = '/^\d{1,2}:\d{1,2}:\d{1,2}.\d$/';
			if(!preg_match($sPatternTC, $arg))
				return 0;
			
			$timecodeA = explode(':', $arg);
			$hh = $timecodeA[0];
			$mm = $timecodeA[1];

			$pointExists = strpos($timecodeA[2], ".");
			if ($pointExists !== false)
			{
				$temp = explode('.', $timecodeA[2]);
				$ss = $temp[0];
				$dd = $temp[1]; //Dixiemes de secondes
			}
			else
			{
				$ss = $timecodeA[2];
			}

			return $hh * 3600 + $mm * 60 + $ss + $dd * 0.1;
		}

		/*
		* Convertit Secondes en Timecodes HH:MM:SS.D
		* @param {string} arg une durée en Secondes
		*/
		public static function secToTC($arg)
		{
			$hh = floor($arg / 3600);
			$arg = $arg - ($hh * 3600);
			$mm = floor($arg / 60);
			$arg = $arg - ($mm * 60);
			$ss = floor($arg);		
			$arg = $arg - $ss;
			
			$h_str = ($hh <= 9)? '0' . $hh : $hh;
			$m_str = ($mm <= 9)? '0' . $mm : $mm;
			$s_str = ($ss <= 9)? '0' . $ss : $ss;

			return $h_str . ':' . $m_str . ':' . $s_str . '.' . $arg * 10;
		}
		
		public static function milliSecToTC($arg)
		{
			$arg = $arg / 1000; //Conversion Millisec en Sec
			return self::secToTC($arg);
		}	
	}
	
?>
