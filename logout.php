<?php
include_once('includes/config.inc.php');
session_start();
if (isset($_SESSION))
{
	$_SESSION=array();
	session_destroy();
}
if( !isset($_GET['type']) )
{
  header("Location: ".ereg_replace("\/[^\/]*\.(html|php)(\?.*)?","",$_SERVER['REQUEST_URI'])."/login.php");
	exit;
}

if( isset($_GET['type']) && ($_GET['type'] == 'cas') )
{
  // import phpCAS lib
  include_once('CAS/CAS.php');

  //phpCAS::setDebug('/home/jeff/CAS/phpcas.log');

  // initialize phpCAS
  phpCAS::client(CAS_VERSION_2_0,CAS_SERVER,443,CAS_FOLDER,false);

  // set the language to french
  phpCAS::setLang(PHPCAS_LANG_FRENCH);
  //phpCAS::forceAuthentication();
  if(phpCAS::checkAuthentication()) phpCAS::logout('?service=http://'.$_SERVER['SERVER_NAME'].ereg_replace("\/[^\/]*\.(html|php)(\?.*)?","",$_SERVER['REQUEST_URI']).'/login.php&type=cas');
  else
  {
	//header("Location: /login.php");
	exit;
  }
}
?>

