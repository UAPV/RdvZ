<?php
$path=ini_get('include_path');
$newpath="includes".PATH_SEPARATOR.$path;
ini_set('include_path',$newpath);



if (!file_exists('includes/config.inc.php')) header('Location:includes/install/install.php');
else
{


  require_once ('includes/config.inc.php');
  require_once ('lang/'.LOCALE.'.inc.php');
  require_once ('includes/lang.inc.php');
  require_once ('authentication.php');
  require_once ('includes/connection.php');
  require_once ('includes/connection_auth.php');
  require_once('includes/Date/Date.php');

  // Récupération des RDV
  $tpl_data['rdv'] = array();
  $sql = "SELECT title, mid, date_del, date_end,closed  
    FROM meeting
    WHERE uid='$uid' " ;
    $res = $BDD->query($sql);
    
    //echo $sql."<br>";
  while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC))
  {
    
    
    
    $date_del=new Date($row['date_del']);
    
    //Verification que le sondage n'est pas perime
   
    if ($date_del->isFuture())
    {
    	$rdv = array(
      	'title' => $row['title'],
      	'code' => $row['mid'],
    );
    
	    // On verifie si le sondage est ouvert/clos
	    $date_end=new Date($row['date_end']);
	    $date_del=new Date($row['date_del']);
	    
	    $rdv['date_end']=$date_end->format("DD/MM/YYYY");
	    $rdv['date_del']=$date_del->format("%D/%M/%Z");
	    
	    if ($date_end->getDay()<10) $day_end_aff="0".$date_end->getDay();
	    else  $day_end_aff=$date_end->getDay();
	    if ($date_end->getMonth()<10) $month_end_aff="0".$date_end->getMonth();
	    else $month_end_aff=$date_end->getMonth();
	    if ($date_del->getDay()<10) $day_del_aff="0".$date_del->getDay();
	    else  $day_del_aff=$date_del->getDay();
	    if ($date_del->getMonth()<10) $month_del_aff="0".$date_del->getMonth();
	    else $month_del_aff=$date_del->getMonth();
	    
	    $rdv['date_end']=$day_end_aff."/".$month_end_aff."/".$date_end->getYear();
	    $rdv['date_del']=$day_del_aff."/".$month_del_aff."/".$date_del->getYear();
	    
	    if ($date_end->isFuture()) //La date de fin n'est pas encore atteinte 
	    {
	    	echo $closed;
	    	if ($row['closed']==0) $rdv['close_state']='close_allowed';
	    	else $rdv['close_state']='reopen_allowed'   ;		
	     }
	     else //La date de fin est atteinte : on ferme, et l'utilisateur ne peut plus rouvrir
	     {
	     	
	     	require_once('close.php');
	     
	     	close_poll($row['mid'],$uid,$BDD);
	     	$rdv['close_state']='none_allowed';
	     		     	
	     }
	         
	     $tpl_data['rdv'][] = $rdv; 	   
	    }
    
    else //sondage perime : on le supprime
    {
    	
    	include('del.php');
    	del_poll($row['mid'],$uid,$BDD);
    	//echo $date_del->getDate()."<br>";	
    }
    
  }
  
  //$BDD->disconnect();

  // Définition du template de contenu
  $tpl_data['content_template_file'] = 'index.tpl.php';
  // Affichage de la page
  include('templates/'.TEMPLATE_NAME.'/page.tpl.php');
} 
?>
