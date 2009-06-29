<?php 
require_once('../DB/DB.php');
require_once('../config.inc.php');



$BDD = DB::connect(DataBase);


if (DB::isError($BDD))
{
	
	if (DebugMode==2) $BDD->getDebugInfo();
	else if (DebugMode==1) $BDD->getMessage();
}

else
{
  
  $BDD->Query("SET NAMES 'UTF8'");
}

function quote($str)
{
  return $BDD->Quote($str);
	return "'".$this->escape($str)."'";
}
?>