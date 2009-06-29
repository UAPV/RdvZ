<?php
/********************************************
Fonction: Participation d'une reunion 
Version: 1.0 
Date: Decembre 2007
Auteurs: Liang HONG, Min WANG
IUP GMI d'Avignon
http://www.iup.univ-avignon.fr
********************************************/

$path=ini_get('include_path');
$newpath="includes".PATH_SEPARATOR.$path;
ini_set('include_path',$newpath);

  // TODO : verifier champ "nom" pour anonyme

  require_once ('includes/config.inc.php');
  
  require_once ('lang/'.LOCALE.'.inc.php');
  require_once ('includes/lang.inc.php');
  require_once ('includes/connection.php');
  
  require_once ('includes/ldap_lecture.php');
  require_once ('includes/Date/Date.php');
  
  require_once ('includes/Mail/Mail.php');
  require_once ('CAS/CAS.php');


  if (isset($_GET['mid']))
  {
    $mid = $_GET['mid'];
   	//Verification de la validité du sondage
   	$sql="select date_del, uid,date_end, closed, aifna,notif,title, description from meeting where mid='".$mid."'";
   	
   	$res_validity=$BDD->query($sql);
   	  	if (DB::isError($res_validity)) die ($res_validity->getMessage());
  	else $array_validity=$res_validity->fetchRow(DB_FETCHMODE_ASSOC);
  	$date_del=new Date($array_validity['date_del']);
  	$date_end=new Date($array_validity['date_end']);
  	/*echo "<pre>";
  	print_r($array_validity);
  	echo "</pre>";*/
  	
  	$closed=$array_validity['closed'];
  	$owner_uid=$array_validity['uid'];
  	$notif=$array_validity['notif'];
  	$tpl_data['aifna']=$array_validity['aifna'];
  	$poll_title=$array_validity['title']."(".$array_validity['description'].")";
 
  	//type d'affichage suivant la validite
  	if (($date_del->isPast())) $display='none'; //Le sondage n'existe plus 
  	elseif(($date_end->isPast())||($closed==1)) $display='readonly'; //La consultation est possible, mais pas le vote
  	else $display='full'; //Le sondage est ouvert	
  }
  else
  {
    header('location: index.php');
    die();
  }
  // TODO : verifier l'existence du mid en base

  $participantName = '';
  $uid = '';
 
  if (isset($_SESSION['uid'])) $uid= $_SESSION['uid']; //Utilisateur authentifie CAS ou BDD
  
  elseif (isset($_POST["participantName"])) $participant_name = $_POST["participantName"];
  elseif (LOGIN_TYPE=='cas') //On teste si l'utilisateur possede un ticket
  {
  	phpCAS::client(CAS_VERSION_2_0,CAS_SERVER,CAS_PORT,CAS_FOLDER);
    phpCAS::setLang(PHPCAS_LANG_FRENCH);
    if (phpCAS::checkAuthentication()) {$uid = phpCAS::getUser(); }//Utilisateur authentifie
    }
  else $participant_name = $_POST["participantName"]; //Utilisateur non authentifie
    
 
  $tpl_data['no_participant_name'] = false;
  
 //commentaires globaux 
  if (isset($_POST['comments']) && (isset($uid) || trim($participant_name)!='')){
	
	$sql = "INSERT INTO meeting_comment
          (id,uid, mid,participant_name, comment)
          VALUES(
          	NULL,
            '".$BDD->escapeSimple($uid)."',
            '".$BDD->escapeSimple($mid)."',            
            '".$BDD->escapeSimple($participant_name)."',
            '".$BDD->escapeSimple($_POST['comments'])."'
          )";	
	$res = $BDD->query($sql);
}
  
  
  
  //utilisateur appuie le bouton Submit
  if (isset($_POST["isSubmit"]))
  {
    if (!isset($uid) && (trim($participant_name)=='')) 
    {
       $tpl_data['no_participant_name'] = true;
    }
   
      // si la personne qui a deja vote veut voter encore une fois
      // (par exemple il a change son idee),
      // on supprime ses votes precedents pour enregistrer ses nouveaux votes.
      $sql = "DELETE FROM meeting_poll
        WHERE uid='".$BDD->escapeSimple($uid)."'
        AND participant_name='".$BDD->escapeSimple($participant_name)."'
        AND pollid IN (
          SELECT pollid
          FROM meeting_date
          WHERE mid='".$BDD->escapeSimple($mid)."'
        )";
        
      $res = $BDD->query($sql);

      //inserer les nouveaux votes
      $sql = "SELECT pollid
        FROM meeting_date
        WHERE mid='".$BDD->escapeSimple($mid)."'";
       
      $res = $BDD->query($sql);
      while ($pollid = $res->fetchRow(DB_FETCHMODE_ASSOC))
      {
        if (isset($_POST[$pollid['pollid']])) $is_poll=$_POST[$pollid['pollid']];
        if (isset($_POST["comment".$pollid['pollid']])) 
        {
        $comm=$_POST["comment".$pollid['pollid']];
        
        }  	
        
        $sql = "INSERT INTO meeting_poll
          (uid, participant_name, pollid, poll,user_comment) 
          VALUES(
            '".$BDD->escapeSimple($uid)."',
            '".$BDD->escapeSimple($participant_name)."',
            '".$BDD->escapeSimple($pollid['pollid'])."',
            '".$BDD->escapeSimple($is_poll)."',
            '".$BDD->escapeSimple($comm)."'
          )";
       
        
        $res2 = $BDD->query($sql);
        $is_poll='';
       
        
      }
      $res->free();
     
  }
  
  
  	
  

  // recuperer les information generales
  $sql = "SELECT title, description,comments,datecomments
    FROM meeting
    WHERE mid='".$BDD->escapeSimple($mid)."'";
  
  
  $res = $BDD->query($sql);
  if ($meeting = $res->fetchRow(DB_FETCHMODE_ASSOC))
  {
    $tpl_data['title'] = stripslashes($meeting['title']);
    $tpl_data['description'] = stripslashes($meeting['description']);
    $tpl_data['availableComments'] = $meeting['comments'];
    $tpl_data['availableDateComments'] = $meeting['datecomments'];
  }
  else
  {
    tr('Meeting not found');
    die();
  }

  // R�cup�ration des mois et du nombre de possibilit�s par mois
  $possible_months = array();
  $sql = "SELECT DISTINCT(DATE_FORMAT(date,'%m/%Y')) AS month,
    COUNT(DATE_FORMAT(date,'%m/%Y')) AS iterations
    FROM meeting_date
    WHERE mid='".$BDD->escapeSimple($mid)."'
    GROUP BY DATE_FORMAT(date,'%m/%Y')
    ORDER BY date";
  $res = $BDD->query($sql);
  while ($possible_month = $res->fetchRow(DB_FETCHMODE_ASSOC))
  {
    $possible_months[] = $possible_month;
  }
  $tpl_data['possible_months'] = $possible_months;

  // R�cup�ration des dates et du nombre de possibilit�s par date
  $possible_dates = array();
  $sql = "SELECT DISTINCT(DATE_FORMAT(date, '%d')) AS day,
    DATE_FORMAT(date, '%w') AS weekday,pollid,
    COUNT(comment) AS iterations
    FROM meeting_date
    WHERE mid='".$BDD->escapeSimple($mid)."'
    GROUP BY date
    ORDER BY date";
  $res = $BDD->query($sql);
  $weekdays = array("0" => Sun, "1" => Mon, "2" => Tue, "3" => Wed, "4" => Thu, "5" => Fri, "6" => Sat);
  while ($possible_date = $res->fetchRow(DB_FETCHMODE_ASSOC))
  {
    $possible_date['weekday'] = $weekdays[$possible_date['weekday']];
    $possible_dates[] = $possible_date;
  }
  $tpl_data['possible_dates'] = $possible_dates;
 
  
  $possibilities=array();
  $sql = "SELECT meeting_date.date, meeting_date.comment, meeting_date.pollid, COUNT(meeting_poll.poll) AS total
    FROM meeting_date
    LEFT JOIN meeting_poll ON (meeting_date.pollid=meeting_poll.pollid)
    WHERE meeting_date.mid='".$BDD->escapeSimple($mid)."'
    	
    GROUP BY meeting_date.pollid
    ORDER BY meeting_date.date, meeting_date.pollid";
  $res = $BDD->query($sql);
  while ($possibility = $res->fetchRow(DB_FETCHMODE_ASSOC))
  {
    $possibility['comment']=stripslashes($possibility['comment']);
    $possibilities[] = $possibility;
  }
  $tpl_data['possibilities'] = $possibilities;
 
  
  //Recuperation des votes "disponibles"
  $possibilities_ok = array();   
  $sql = "SELECT meeting_date.date, meeting_date.comment, meeting_date.pollid, COUNT(meeting_poll.poll) AS total
    FROM meeting_date
    LEFT JOIN meeting_poll ON (meeting_date.pollid=meeting_poll.pollid AND meeting_poll.poll=1)
    WHERE meeting_date.mid='".$BDD->escapeSimple($mid)."'
    	
    GROUP BY meeting_date.pollid
    ORDER BY meeting_date.date, meeting_date.pollid";
  $res = $BDD->query($sql);
  while ($possibility = $res->fetchRow(DB_FETCHMODE_ASSOC))
  {
    $possibilities_ok[] = $possibility;
  }
  $tpl_data['possibilities_ok'] = $possibilities_ok;
 
  
  
  
  // R�cup�ration des votes "disponibles si besoin"
  $possibilities_ok_if_needed = array();
  $sql = "SELECT meeting_date.date, meeting_date.comment, meeting_date.pollid, COUNT(meeting_poll.poll) AS total
    FROM meeting_date
    LEFT JOIN meeting_poll ON (meeting_date.pollid=meeting_poll.pollid AND meeting_poll.poll=2)
    WHERE meeting_date.mid='".$BDD->escapeSimple($mid)."'" .
    		"AND meeting_poll.poll='2' 
    GROUP BY meeting_date.pollid
    ORDER BY meeting_date.date, meeting_date.pollid";

  $res = $BDD->query($sql);
  while ($possibility = $res->fetchRow(DB_FETCHMODE_ASSOC))
  {
    $possibilities_ok_if_needed[] = $possibility;
  }
 
  $tpl_data['possibilities_ok_if_needed'] = $possibilities_ok_if_needed;
    
 
  // R�cup�ration des participants
  $parsed_votes = array();
  $sql = "SELECT uid, participant_name
    FROM meeting_poll, meeting_date
    WHERE meeting_date.mid='".$BDD->escapeSimple($mid)."'
    AND meeting_date.pollid=meeting_poll.pollid
    GROUP BY uid, participant_name";
    
    
  $res = $BDD->query($sql);
  while ($voter = $res->fetchRow(DB_FETCHMODE_ASSOC))
  {
    $voter_data = array();
    if (empty($voter['uid']))
    {
      $voter_data['name'] = $voter['participant_name'];
      $voter_data['authentified'] = false;
    }
    else
    {
      $info_utilisateur = get_infos($voter['uid'], array('givenname','sn'));
      $name = $info_utilisateur['givenname'].' '.$info_utilisateur['sn'];
      $voter_data['name'] = $name;
      $voter_data['authentified'] = true;
    }	



    // R�cup�ration des votes des participants disponibles
    
    $voter_data['votes'] = array();
    $sql = "SELECT poll as vote
      FROM meeting_poll, meeting_date
      WHERE meeting_date.pollid=meeting_poll.pollid
      AND meeting_poll.uid='".$BDD->escapeSimple($voter['uid'])."'
      AND meeting_poll.participant_name='".$BDD->escapeSimple($voter['participant_name'])."'
      AND meeting_date.mid='".$BDD->escapeSimple($mid)."'
      ORDER BY meeting_date.date, meeting_date.pollid";
   
     
    $res2 = $BDD->query($sql);
    while ($vote = $res2->fetchRow(DB_FETCHMODE_ASSOC))
    {
      $voter_data['votes'][] = $vote['vote'];
    
    }
   
  
  
  //Recuperation des commentaires laisssés par les votants, si autorise
  
  if ($tpl_data['availableDateComments']=='Y')
  {
  
    $sql = "SELECT user_comment FROM meeting_poll, meeting_date
      WHERE meeting_date.pollid=meeting_poll.pollid
      AND meeting_poll.uid='".$BDD->escapeSimple($voter['uid'])."'
      AND meeting_poll.participant_name='".$BDD->escapeSimple($voter['participant_name'])."'
      AND meeting_date.mid='".$BDD->escapeSimple($mid)."'
      ORDER BY meeting_date.date, meeting_date.pollid";
  
      
    
    $res2 = $BDD->query($sql);
    
    while ($comm = $res2->fetchRow(DB_FETCHMODE_ASSOC))
    {
      $voter_data['user_comment'][] = $comm['user_comment'];
    
    }
  }
    $parsed_votes[] = $voter_data;
  
  $tpl_data['votes'] = $parsed_votes;
  
  
  }
  
 // Recuperation commentaires globaux, si autorisés
if ($tpl_data['availableComments']=='Y') {
    $sql = "SELECT uid, participant_name, comment 
		FROM meeting_comment 
		WHERE mid='".$BDD->escapeSimple($mid)."'";
    
    
	$res = $BDD->query($sql);
	$tpl_data['commentaires']=array();
	while ($lstCommentaires = $res->fetchRow(DB_FETCHMODE_ASSOC))
	{
		$comment_user_data = '';
		$comment_user_name = '';
		if (!empty($lstCommentaires['uid']))
	    {
	    	// si c'est un utilisateur authentifie, on r�cup�re son nom
	    	$comment_user_data = get_infos($lstCommentaires['uid'], array('givenname','sn'));
	    	$comment_user_name = $comment_user_data['givenname'].' '.$comment_user_data['sn'];
	    }
		
		$tpl_data['commentaires'][] = array("uid"=>$comment_user_name,"participant_name"=>$lstCommentaires['participant_name'],"comment"=>$lstCommentaires['comment']);
	}
}	





  $tpl_data['current_username'] = '';

  if (!empty($uid))
  {
   
    // si c'est un utilisateur authentifie, on r�cup�re son nom
    $user_data = get_infos($uid, array('givenname','sn'));   
    
    $tpl_data['current_username'] = $user_data['givenname'].' '.$user_data['sn'];
  }
  
  elseif(LOGIN_TYPE=='cas')
  {
    //$tpl_data['cas_login_url'] = phpCAS::getServerLoginURL();
  }

  $tpl_data['mid'] = $mid;

  // D�finition du template de contenu
  
  if (($display=='full')||($display=='readonly')) $tpl_data['content_template_file'] = 'participation.tpl.php';
  if ($display=='none') $tpl_data['content_template_file'] = 'poll_not_available.tpl.php';
  
  /*echo "<pre>";
  print_r($tpl_data);
  echo "</pre>";*/
  
  
  //Envoi mail si notif activées et parametres renseignes
  
  if (($notif=="Y")
  &&(isset($_POST['isSubmit']))
  &&(smtp_host!='')
  &&(smtp_port!=''))
  {

  	if (LOGIN_TYPE=="cas" || LOGIN_TYPE=="ldap")
  	{
  		//Recupere e-mail depuis ldap
  		$owner_data = get_infos($owner_uid, array('mail'));
  		$mail=$owner_data['mail'];
  		
  		
  		
  	}
  	elseif(LOGIN_TYPE=="db")
  	{
  		//Recupere e-mail depuis la base
  		require_once ('includes/connection_auth.php');
  		$sql="select ".FIELD_MAIL." from ".TABLE_USERS." where ". FIELD_USERS." ='".$owner_uid."'";
  		
   	$res_mail=$BDD_auth->query($sql);
  	if (DB::isError($res_mail)) die ($res_mail->getMessage());
  	else $array_mail=$res_mail->fetchRow(DB_FETCHMODE_ASSOC);
  	$mail=$array_mail[FIELD_MAIL];
  	  		
  		
  	}
  
  	$recipients = $mail;
	$headers["From"]    = admin_mail;
	$headers["Reply-to"]= admin_mail;
	$headers["To"]      = $mail;
	$headers["Subject"] = '[RDVZ] Vote pour votre rendez-vous '.$nom_fichier;
	$headers["Content-Type"]='text/plain; charset="UTF-8"';
	$headers["MIME-Version"]='1.0';
	
	
	
	$msg=tr('Notification message',TRUE)."\n";
	$msg.=$poll_title."\n";
	$msg.=tr('Voted by',1);
	if ($participant_name!='') $msg.=" ".$participant_name.tr('Not authenticated',TRUE);
	if ($uid!='') $msg.=" ".$tpl_data['current_username'].tr('CAS authenticated',TRUE);
	
	$msg.=tr('Do not reply',1);
	
	$params["host"] = smtp_host;
	$params["port"] = smtp_port;
	
	
	
	
	// Create the mail object using the Mail::factory method
	$mail_object =& Mail::factory("smtp", $params);
	$send=$mail_object->send($mail, $headers, $msg);
	
	if (PEAR::isError($send)) {echo "ERR" ;print($send->getMessage());}
	
	}
  // Affichage de la page
 
  include('templates/'.TEMPLATE_NAME.'/page.tpl.php');
   
?>
