<?php

abstract class XSLT {
	
	/**
	 * Function: transform
	 * 		Effectue une transformation xslt  à partir des chemin des fichiers 
	 * 		xml et xsl et retourne le resultat en xml.
	 * 
	 * Parameters:
	 * 		$xml - le chemin du fichier xml.
	 * 		$xsl - le chemin du fichier xsl.
	 * 
	 * Exception:
	 * 		Exception, ErrorException
	 * 
	 * Return:
	 * 		DOMDocument - le resultat de la tranformation.
	 */
	public static function transform($xml, $xsl, $params = null){
		$data = '';
		if ( !file_exists($xml))
			throw new Exception('le fichier xml n\'existe pas : '.$xml);
		if ( !file_exists($xsl))
			throw new Exception('le fichier xsl n\'existe pas : '.$xsl);
		
		$xmlDom = new DOMDocument();
		if ( ! $xmlDom -> load($xml))
		 	throw new Exception('Erreur lors du chargement du fichier xml : '.$xml);
		$xslDom = new DOMDocument();
		if ( ! $xslDom -> load($xsl))
			throw new Exception('Erreur lors du chargement du fichier xsl : '.$xsl);
		
		$xslt = new XSLTProcessor();
		
		
		if ( $params != null){
				$xslt->setParameter('',$params);
		}
		$xslt -> importStylesheet($xslDom);
		if ( ($data = $xslt -> transformToDoc($xmlDom)) == false)
			throw new Exception('Erreur lors de la transformation xslt.');
		unset($xmlDom);
		unset($xslDom);
		unset($xslt);
		return $data;
	}

	
	
	
}
?>
