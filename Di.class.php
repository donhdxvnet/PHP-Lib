//Dependance Injection
abstract class Di
{
	private static $db_instance = null;

	public static function getArticle()
	{
		return new Article(self::getDatabase());
	}

	public static function getDatabase()
	{
		if (self::$db_instance == null){
			self::$db_instance = new MySQLDatabase("blog");			
		}
		return self::$db_instance;		
	}
}
