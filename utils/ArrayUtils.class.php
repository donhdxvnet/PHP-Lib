<?php

abstract class ArrayUtils {

	public static /*Array*/ function array_unique_obj($array, $unique_obj_attr){
		$aClean = Array();
		$aExlusions = Array();
		foreach($array as $k=>$mu){
			$bPush = true;
			foreach($array as $k2=>$mu2){
				if($k == $k2 || in_array($mu2->$unique_obj_attr, $aExlusions)) continue;
				if($mu->$unique_obj_attr == $mu2->$unique_obj_attr){
					$bPush = false;
					array_push($aExlusions, $mu->$unique_obj_attr);
				}
			}
			if($bPush) array_push($aClean, $mu);
		}
		return $aClean;
	}
	
}
?>
