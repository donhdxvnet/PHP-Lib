<?php

require_once dirname(__FILE__).'/../exceptions/UploadException.class.php';

abstract class FSUtils
{
	public static $files = array();
	public static $firstDir = "";

	public static function findMiddle()
	{
		$middle = filesize($file) / 2;
		$thefile = fopen($file, "r");
		$read = 0;
		while (($buffer=fgets($thefile)) !== false) {
			$read += strlen($buffer);
			if ($read>$middle) {
				return array($pos, $buffer);
			}
			$pos = ftell($thefile);
		}
	}

	public static function getExtension($filepath)
	{
		return pathinfo($filepath, PATHINFO_EXTENSION);
	}

	public static function recursiveListDir($dir)
	{
		if ($opendir = opendir($dir))
		{
			while ( $file = readdir($opendir) )
			{					
				if ( ! in_array($file, array(".", "..")) )
				{
					$filePath = $dir . "/" . $file;
					if ( is_file($filePath) ) {
						$shortFilePath = substr( $filePath, strlen(self::$firstDir) + 1, strlen($filePath) );
						self::$files[] = $shortFilePath;
					}
					if ( is_dir($filePath) ) {
						self::recursiveListDir($filePath);
					}
				}
			}
			closedir($opendir);
		}	 
	}

	public static function write( $lepath, $content, $perm=0664 ,$flags=0){

		if ( trim($lepath) == '' || trim($content) == '')
		throw new Exception('FSUtils::write() => Parametre vide');
			
		$lastslash = strrpos($lepath,'/');
		$path =  substr($lepath,0,$lastslash);
		$name =  substr($lepath,$lastslash+1);
		if ( ! is_file($path) || ! is_dir($path)){
			self::mkdir( $path );
		}

		if ( ! file_put_contents( $lepath,$content, $flags))
		throw new Exception('FSUtils::write() => Erreur lors de l\'ecriture du fichier : '.$lepath);
			
		@chmod($lepath, $perm);
	}

	public static  function read($filename){
		if ( ! file_exists($filename))
		throw new Exception('Le fichier '.$filename.' n\existe pas.');
		if ( ! is_readable($filename))
		throw new Exception('Pas de permission de lecture du fichier '. $filename);
		if ( ! ( $content = file_get_contents($filename)))
		throw new Exception('Erreur lors du chargement du contenu du fichier '. $filename);
		return $content;
	}

        public static function copy_rec($source, $target)
        {
            if (!is_dir($source) || !file_exists($source))
                throw new Exception( $source. ' not found');

            if(!is_link($source))
            {
                if(!is_dir($source) )
                {

                    copy($source, $target);
                    @chmod($target, 0664);
                    return;
                }
                else
                {
                    if(!is_dir($target))
                    {
                        mkdir($target);
                        @chmod($target, 0775);
                    }
                    $cur_dir = opendir($source);
                    while($elt = readdir($cur_dir))
                    {
                        if($elt!='.' && $elt!='..')
                        {
                            if(is_dir( $source.'/'.$elt ))
                            {
                                if(substr($elt, 0,1)!='.') self::copy_rec($source.'/'.$elt, $target.'/'.$elt);
                            }
                            else self::copy($source.'/'.$elt, $target.'/'.$elt);
                        }
                    }
                    closedir($cur_dir);
                }   
            }                
        }

        public static function copy($source, $target){
            if (! file_exists($source))
                throw new Exception( $source. ' not found');

            $td = substr($target, 0, strrpos($target,"/"));
            if (! file_exists($td))
                self::mkdir ($td);
            copy($source, $target);
            @chmod($target, 0664);
        }


	public static function mkdir($path, $perm=0775){
		if ( $path == '')
		throw new Exception('FSUtils::mkdir() => Parameter "$path" is empty');
          //    print_r($path); exit;
		$dirs = explode('/',$path);
		$cur_path = '/';
		foreach( $dirs as $dir){
			if ( $dir != ''){
				//echo $cur_path."\n";
				$old_path = $cur_path ;
				if ( $cur_path == '/')
				$cur_path = '/'.$dir.'/';
				else
				$cur_path .= $dir.'/';


				if ( ! file_exists($cur_path)){
					if ( ! is_writable($old_path))
					throw new Exception('FSUtils::mkdir() => Permission denied : '.$cur_path.' is not writable');

					//echo 'no exist!'."\n";
					if ( ! mkdir($cur_path, $perm))
					throw new Exception('FSUtils::mkdir() => mkdir '.$cur_path.' failled.');
					@chmod($cur_path, $perm);
			}
		}
	}
	}

	/**
	 * Function: isEmptyDir
	 * 		Verifie si un repertoire est vide
	 *
	 * Parameters:
	 * 		string - le repertoire a verifier
	 *
	 * Return:
	 * 		boolean - true si le repertoire est vide sinon false;
	 */
	public static function isEmptyDir($dir){
		return (($files = @scandir($dir)) && count($files) <= 2);
	}

	public static function rm_rev($filePath){
		if ( trim($filePath) == '')
		throw new Exception('FSUtils::rm_rev() => Parametre vide!!');
			
		if (! ($path = realpath(trim($filePath))))
		throw new Exception('FSUtils::rm_rev() => realpath erreur avec '.$filePath);
			
		if ( is_file($path)){
			if ( ! unlink($path))
			throw new Exception('FSUtils::rm_rev() => unlink '.$path.' failled.');
			$path = substr($path,0,strrpos($path, "/"));
		}
		if ( is_dir($path)){
			while(self::isEmptyDir($path)){
				if ( ! rmdir($path))
				throw new Exception('FSUtils::rm_rev() => rmdir '.$path.' failled.');
				$path = substr($path,0,strrpos($path, "/"));
			}
		}
	}

	public static function rm_folder($folderPath) {
		if ($folderPath[strlen($folderPath)-1] != '/') $folderPath .= '/';

		if (is_dir($folderPath)) {
			$sq = opendir($folderPath);
			while ($f = readdir($sq)) {
				if ($f != '.' && $f != '..')
				{
					$file = $folderPath.$f;
					if (is_dir($file)) self::rm_folder($file);
					else unlink($file);
				}
			}
			closedir($sq);
			rmdir($folderPath);
		}
		else unlink($folderPath);
	}


	public static function upload($file, $target, $extensions=null){
		$tmp = $file['tmp_name'];
		$name = $file['name'];
		$type = $file['type'];
		$size = $file['size'];
		$error = $file['error'];
		$extension = strtoupper(strrchr($name, '.'));

		$maxSize = str_replace('M','',ini_get("upload_max_filesize")) * 1048576;

		if ( $error !== UPLOAD_ERR_OK )
		throw new UploadException($error);

		if ($name == '')
		throw new Exception('filename is empty!');

		if( preg_match('#[\x00-\x1F\x7F-\x9F/\\\\]#', $name) )
		throw new Exception('Invalid Filename.');

		if ( $size < 1)
		throw new Exception('File size is null');
			
		if ( $extensions != null && !in_array($extension, $extensions))
		throw new Exception('File upload stopped by wrong extension');
			
		if ( $size > $maxSize)
		throw new Exception('File is too big');
			
		if( ! is_uploaded_file($tmp) )
		throw new Exception( 'Attaque possible : '.$tmp);

		FSUtils::mkdir(dirname($target));

		if( ! move_uploaded_file($tmp, $target))
		throw new Exception('Invalid File');
		@chmod($target,0664);
	}

	public static function formatFileName($name){
		if ( $name == '') throw new Exception('FSUtils::formatFileName() => Parameter "$name" is empty');
		$fichier = strtr(basename($name),
		          'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
		          'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
		return preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
	}

        public static function count_files($dir)
        {
            $num = 0;
            if(!is_dir($dir)) return $num;
            $dir_handle = opendir($dir);
              while($entry = readdir($dir_handle))
                    if(is_file($dir.'/'.$entry))
                             $num++;
            closedir($dir_handle);
            return $num;
        }

}
?>
