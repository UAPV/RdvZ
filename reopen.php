<?php
$path=ini_get('include_path');
$newpath="includes".PATH_SEPARATOR.$path;
ini_set('include_path',$newpath);

require_once ('authentication.php');
require_once('includes/connection.php');
require_once('includes/DB/DB.php');


function close_poll($mid,$uid,$BDD)
{
	require_once('includes/Date/Date.php');
	
	
	//verifier si la personne qui lance la suppression = le proprietaire
	$sql = "SELECT uid FROM meeting WHERE mid='".$mid."'";
	

	//le proprietaire de cette reunion
	$uid2=$BDD->getOne($sql);
	if (PEAR::isError($uid2)) die ($uid2->getMessage());
	

	//si la personne qui lance la cloture = le proprietaire
      	if ($uid == $uid2)
        {
        	$sql="update meeting set closed='0' where mid='".$mid."'";
        	$res=$BDD->query($sql);
        	if (PEAR::isError($res)) die ($res->getMessage());	
        }
        else
  {
	die ('mid invalide!');
  }		
	header("Location: index.php");
}


  
  if (isset($_GET['mid']) AND strlen($_GET['mid'])==8) 
  {
  	close_poll($_GET['mid'],$uid,$BDD);
  }
  


?>
