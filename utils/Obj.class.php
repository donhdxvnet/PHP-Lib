abstract class Obj
{
  public static function checkObject($obj, $objects)
  {
    $result = true;
    if (is_object($obj))
    {
      foreach ($objects as $object)
      {
        if ($obj instanceof $object) $result = true;
        else $result = false;
      }
    }
    return $result;
  }
}
