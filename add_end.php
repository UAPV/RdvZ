<?php
/********************************************
Fonction: Page d'accueil, affichage mes rdv
Version: 1.0 
Date: Decembre 2007
Auteurs: Liang HONG, Min WANG
IUP GMI d'Avignon
http://www.iup.univ-avignon.fr
********************************************/
  require_once ('includes/config.inc.php');
  require_once ('lang/'.LOCALE.'.inc.php');
  require_once ('includes/lang.inc.php');
  require_once ('authentication.php');
  require_once ('includes/Date/Date.php');
  require_once('includes/connection.php');
  require_once ('includes/Mail/Mail.php');
  require_once ('includes/ldap_lecture.php');
 
  
  if (isset($_GET['mid']))
    $mid = $_GET['mid'];
  if (!isset($mid))
  {
    header('location: index.php');
    die();
  }
  
    //Calcul date suppression
  $date_del=new Date();
  $date_span=new Date_Span(Array(nb_del_date,0,0,0));
  $date_del->addSpan($date_span);
  $Y=$date_del->getYear();
  $m=$date_del->getMonth();
  $d=$date_del->getDay();
  
  //Mise au format MySQL
  $date_del_sql=$Y;
  if($m<10) $date_del_sql.="-0".$m;
  else $date_del_sql.="-".$m;
  if($d<10) $date_del_sql.="-0".$d;
  else $date_del_sql.="-".$d;
    
   
  //Calcul date cloture
  $date_end=new Date();
  $date_span=new Date_Span(Array(nb_end_date,0,0,0));
  
  $date_end->addSpan($date_span);
  $Y=$date_end->getYear();
  $m=$date_end->getMonth();
  $d=$date_end->getDay();
 
  //Mise au format MySQL
  $date_end_sql=$Y;
  if($m<10) $date_end_sql.="-0".$m;
  else $date_end_sql.="-".$m;
  if($d<10) $date_end_sql.="-0".$d;
  else $date_end_sql.="-".$d;
   
  
  if($d<10) $date_end_aff="0".$d;
  else $date_end_aff=$d;
  if($m<10) $date_end_aff.="/0".$m;
  else $date_end_aff.="/".$m;
   $date_end_aff.="/".$Y;
   
  //Insertion dans la base
  $sql="update meeting set date_del='".$date_del_sql."',date_end='".$date_end_sql."' where mid='".$mid."'";
  $res = $BDD->query($sql); 
  if (DB::isError($res)) die ($res_date->getMessage());      
  $BDD->disconnect();
  
  
  //Envoi de mail aux participants choisis
  if (isset($_SESSION["maillist"]))
  {
  	if (LOGIN_TYPE=="cas" || LOGIN_TYPE=="ldap")
  	{  		
  		$owner_mail=$_SESSION['mail'];
  		$owner_name=$_SESSION['cn'];  	 		
  	}
  	elseif(LOGIN_TYPE=="db")
  	{
  	//Recupere e-mail depuis la base
  	require_once ('includes/connection_auth.php');
  	$sql="select ".FIELD_MAIL." from ".TABLE_USERS." where ". FIELD_USERS." ='".$uid."'";
   	$res_mail=$BDD_auth->query($sql);
   	
  	if (DB::isError($res_mail)) die ($res_mail->getMessage());
  	else $array_mail=$res_mail->fetchRow(DB_FETCHMODE_ASSOC);
  	$owner_mail=$array_mail['mail'];
  	$owner_name=$uid;
  	}
  	
  	
	$headers["From"]    = admin_mail;
	$headers["Reply-to"]=admin_mail;
	
	$headers["Subject"] = '[RDVZ] Proposition de rendez-vous';
	$headers["Content-Type"]='text/plain; charset="UTF-8"';
	$headers["MIME-Version"]='1.0';

	$msg=tr('RDV notification',1).$owner_name."(".$owner_mail.")\n";
	$msg.=$_SESSION["meeting_title"];
	if (isset($_SESSION["meeting_description"])&&($_SESSION["meeting_description"]!='')) $msg.="(".$_SESSION["meeting_description"].")";
	$msg.="\n";
	$msg.=tr('Now you can choose',1);
	$msg.= "http://".$_SERVER['SERVER_NAME'].ereg_replace("\/[^\/]*\.(html|php)(\?.*)?","",$_SERVER['REQUEST_URI'])."/participation.php?mid=$mid";
	$msg.="\n";
	$msg.=tr('Available until',1).$date_end_aff;	
	$msg.=tr('Do not reply',1);
	$params["host"] = smtp_host;
	$params["port"] = smtp_port;

	foreach ($_SESSION["maillist"] as $mail)
  	{
  	
  	$recipients = $mail;
	$headers["To"] = $mail;
	// Create the mail object using the Mail::factory method
	$mail_object =& Mail::factory("smtp", $params);
	$send=$mail_object->send($recipients, $headers, $msg);
	if (PEAR::isError($send)) {print($send->getMessage());}
  	} 
  }
	
  	
  // Définition du template de contenu
    $tpl_data['content_template_file'] = 'add_end.tpl.php';
  // Affichage de la page
  include('templates/'.TEMPLATE_NAME.'/page.tpl.php');
?>
