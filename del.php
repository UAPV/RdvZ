<?php

$path=ini_get('include_path');
$newpath="includes:".$path;
ini_set('include_path',$newpath);


require_once ('authentication.php');
require_once('includes/connection.php');
require_once('includes/DB/DB.php');

function del_poll($mid,$uid,$BDD)
{
	
	
	//verifier si la personne qui lance la suppression = le proprietaire
	$sql = "SELECT uid FROM meeting WHERE mid='".$mid."'";
	

	//le proprietaire de cette reunion
	$uid2=$BDD->getOne($sql);
	if (PEAR::isError($uid2)) die ($uid2->getMessage());
	

	//si la personne qui lance la suppression = le proprietaire
      	if ($uid == $uid2)
        {
		$sql = "DELETE FROM meeting_poll WHERE pollid IN ( SELECT pollid FROM meeting_date WHERE mid='".$BDD->escapeSimple($mid)."' )";
        	$BDD->query($sql);
        	$sql = "DELETE FROM meeting_date WHERE mid='".$BDD->escapeSimple($mid)."'";
        	$BDD->query($sql);
        	$sql = "DELETE FROM meeting WHERE mid='".$BDD->escapeSimple($mid)."' ";
        	$BDD->query($sql);
        }
        else
  {
	die ('mid invalide!');
  }		
	//header("Location: index.php");
}


  
  if (isset($_GET['mid']) AND strlen($_GET['mid'])==8) 
  {
  	del_poll($_GET['mid'],$uid,$BDD);
  	header("Location: index.php");
  }
  
?>

