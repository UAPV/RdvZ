<?php

  require_once ('includes/config.inc.php');
  require_once ('lang/'.LOCALE.'.inc.php');
  require_once ('includes/lang.inc.php');
  require_once ('authentication.php');
  require_once ('includes/connection.php');

  // Vider les valeurs de la session 
  // afin de commencer une nouvelle session
  unset($_SESSION["dates"]);
  unset($_SESSION["meeting_title"]);
  unset($_SESSION["meeting_description"]);

  $mid = '';
  if (isset($_GET['mid']))
    $mid = $_GET['mid'];

  $meeting_title = '';
  $meeting_description = '';

  // Récupération des données pour modification / Vérification du mid
  if ($mid != '')
  {
    $sql = "SELECT title, description, mid
      FROM meeting
      WHERE mid='".$BDD->escapeSimple($mid)."'
      AND uid='".$BDD->escapeSimple($uid)."'";
    $res = $BDD->query($sql);
    if ($row = $res->fetchRow(DB_FETCHMODE_ASSOC))
    {
      $meeting_title = $row['title'];
      $meeting_description = $row['description'];
    }
    else
    {
      header('location: index.php');
      die();
    }
  }
  $BDD->disconnect();
  
  
  // Traitement des données postées
  $tpl_data['show_title_error'] = false;
  if (isset($_POST['submit']))
  {
    if (isset($_POST["meeting_title"]))
      $meeting_title = $_POST["meeting_title"];
    if (isset($_POST["meeting_description"]))
      $meeting_description = $_POST["meeting_description"];
    if (isset($_POST["aifna"]))
	  $aifna=$_POST["aifna"];
	if (isset($_POST["notif"]))
	  $notif=$_POST["notif"];
	if (isset($_POST["maillist"]))
	{
	  $maillist=explode(';',$_POST["maillist"]);
	  $maillist=array_unique($maillist);
	  $_SESSION["maillist"]=$maillist;
	  
	} 
    if (trim($meeting_title)!='')
    {
      $_SESSION["meeting_title"] = $meeting_title;
      $_SESSION["meeting_description"] = $meeting_description;
      $_SESSION["aifna"]=$aifna;
      $_SESSION["notif"]=$notif;
      header('location: add_step2.php?mid='.$mid);
      die();
    }
    else
      $tpl_data['show_title_error'] = true;
  }

  $tpl_data['meeting_title'] = $meeting_title;
  $tpl_data['meeting_description'] = $meeting_description;
  $tpl_data['mid'] = $mid;

  // Définition du template de contenu
  $tpl_data['content_template_file'] = 'add_step1.tpl.php';
  
  // Affichage de la page
  include('templates/'.TEMPLATE_NAME.'/page.tpl.php');
?>
