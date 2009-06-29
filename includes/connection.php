<?php 
$path=ini_get('include_path');
$newpath="includes".PATH_SEPARATOR.$path;
ini_set('include_path',$newpath);

require_once('DB/DB.php');
require_once('config.inc.php');
$BDD=DB::connect(DataBase);
if (DB::isError($BDD))
{
	
	if (DebugMode==2) $BDD->getDebugInfo();
	else if (DebugMode==1) $BDD->getMessage();
	$BDD->getDebugInfo();
	//echo "Tout pas bon<br>";
	
}

else
{
  //echo "toutbon ! <br>";
  $BDD->Query("SET NAMES 'UTF8'");
  
}

function quote($str)
{
  return $BDD->Quote($str);
	return "'".$this->escape($str)."'";
}
?>