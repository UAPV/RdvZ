<?php 
require_once('DB/DB.php');
require_once('../config.inc.php');


$BDD_auth = DB::connect(DataBaseUsers);


if (DB::isError($BDD_auth))
{
	
	if (DebugMode==2) $BDD_auth->getDebugInfo();
	else if (DebugMode==1) $BDD_auth->getMessage();
}

else
{

  $BDD_auth->Query("SET NAMES 'UTF8'");
  
}
?>