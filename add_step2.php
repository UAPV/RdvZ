<?php
/********************************************
Fonction: Ajouter une reunion 
          - la page "Selectionne de Dates"
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
  require_once ('includes/connection.php');
  
  
  // Annulation
  if (isset($_POST['cancel']))
  {
    header('location: index.php');
    die();
  }

  // Modification ?
  $mid = '';
  if (isset($_POST['mid']))
    $mid = $_POST['mid'];
  if (isset($_GET['mid']))
    $mid = $_GET['mid'];

  if ($mid != '')
  {
    // On verifie que le mid existe et que l'utilisateur en est l'auteur
    $sql = "SELECT uid
      FROM meeting
      WHERE mid='".$BDD->escapeSimple($mid)."'
      AND uid='".$BDD->escapeSimple($uid)."'";
    $res = $BDD->query($sql);
    if (!($res->fetchRow(DB_FETCHMODE_ASSOC)))
    {
      header('location: index.php');
      die();
    }
  }

  //Premi�rement on v�rifie si des variables on d�j��t� envoy�es pour le mois et l'ann�e 

  //mois
  if (!isset($_GET["m"])) {
    $m = date("n");
  }
  else
  {
    $m = $_GET["m"];
	  //enlever le premier zero
	  if (substr($m, 0, 1) == "0")
      $m = substr($m, 1, 1);
	  if (strlen($m)>2 || ($m < 1 || $m > 12) )
      die ('variable invalide') ;
  }

  //annee
  if (!isset($_GET["a"]))
  	$a = date("Y");
  else
  {
   	$a = $_GET["a"];
    if ( strlen($a) != 4 || !is_numeric($a))
      die ('variable invalide') ;   
  }

  $dates = array();

  //recuperer les dates selectionnees 
  if (isset($_SESSION['dates'])) {
    $dates = $_SESSION['dates'];
  }
  elseif ($mid != '')
  {
    // Modification : On recupere les dates qu'on a en base
    $sql = "SELECT date, comment
      FROM meeting_date
      WHERE mid='".$BDD->escapeSimple($mid)."'
      ORDER BY date";
    $res = $BDD->query($sql);
    
    
    
    
    while ($row = $res->fetchRow(DB_FETCHMODE_ASSOC))
    {
      if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $row['date'], $matches))
      {
        $date_saved['date'] = $matches[3]."-".$matches[2]."-".$matches[1];
        $date_saved['comment'] = $row['comment'];
        $dates[] = $date_saved;
      }
    }
  }

  //utilisateur demande d'ajouter une nouvelle date 
  if (isset($_GET["date"]))
  {
    if (preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $_GET["date"], $matches))
    {
      if (checkdate($matches[2], $matches[1], $matches[3]))
      {
        $dates[] = array('date' => $_GET["date"]);
      }
    }
  }

  //utilisateur demande de supprimer une date
  if (isset($_GET["del_date"]))
  {
    $del_date = $_GET["del_date"];
    unset($dates[$del_date]);
    reset($dates);
  }

  // On a les dates, on initialise les commentaires
  foreach ($dates as $key => $value)
  {
    if (!isset($dates[$key]['comment']))
      $dates[$key]['comment'] = '';
    if (isset($_POST['comment']))
      if (isset($_POST['comment'][$key]))
      {
        $dates[$key]['comment'] = $_POST['comment'][$key];
      }
  }

  //mis a jour
  $_SESSION['dates'] = $dates;

  $tpl_data['no_date_error'] = false;
  // Sauvegarde
  if (isset($_POST['savenow']))
  {
    if (count($_SESSION['dates'])==0)
    {
      $tpl_data['no_date_error'] = true;
    }
    else
    {
      if ($mid == '')
      {
        //generer un mid(Meeting ID) aleatoire
        do
        {
          $chars='0123456789abcdefghijkmnopqrstuvwxyz'; // characters to build the password from 
          $mid='';
          $len=8;
          for($len;$len>=1;$len--)
          {
            $position=rand()%strlen($chars);
            $mid.=substr($chars,$position,1);
          }
    
          //verifier si le mid existe
          $sql = "SELECT mid FROM meeting WHERE mid=".$BDD->quote($mid);
          $res= $BDD->query($sql);
          $mid_exist = $res->numRows();
        } while ($mid_exist != 0); //on fait ce boucle jusqu'on trouve un mid non redondant
      }
      else
      {
        // modification : on supprime les enregistrements du mid actuel
        $sql = "DELETE FROM meeting_poll
          WHERE pollid IN (
            SELECT pollid
            FROM meeting_date
            WHERE mid='".$BDD->escapeSimple($mid)."'
          )";
        $BDD->query($sql);
        $sql = "DELETE FROM meeting_date
          WHERE mid='".$BDD->escapeSimple($mid)."'";
        $BDD->query($sql);
        $sql = "DELETE FROM meeting
          WHERE mid='".$BDD->escapeSimple($mid)."'";
        $BDD->query($sql);
      }

      //inserer les information generales dans la base de donnees
      
      $sql = "INSERT INTO meeting
        (mid, uid, title, description,aifna,notif)
        VALUES(
          ".$BDD->quote($mid).",
          ".$BDD->quote($uid).",
          ".$BDD->quote($_SESSION['meeting_title']).",
          ".$BDD->quote($_SESSION['meeting_description']).",
          ".$BDD->quote($_SESSION['aifna']).",
          ".$BDD->quote($_SESSION['notif'])."
        )";
        $BDD->query($sql);

      //inserer les options de votes dans la base de donnees
      if (count($_SESSION['dates'])>0)
      {
        foreach ($_SESSION['dates'] as  $key => $value)
        {
          if (preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $value['date'], $matches))
          {
            $sql = "INSERT INTO meeting_date      
              (mid, date, comment)
              VALUES(
                ".$BDD->quote($mid).",
                ".$BDD->quote($matches[3]."-".$matches[2]."-".$matches[1]).",
                ".$BDD->quote($value['comment'])."
               
              )";
            
            $BDD->query($sql);
          }
        }
      }
    
      $BDD->disconnect();
      header('location: add_end.php?mid='.$mid);
      die();
    }
  }

  $mnom = array("", 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
  
  $dayone = date("w", mktime(1, 1, 1, $m, 1, $a));
  if ($dayone == 0)
    $dayone = 7;

  $tpl_data['mid'] = $mid;

  $tpl_data['dates_selected'] = $dates;

  $tpl_data['selected_date']['month_lib'] = $mnom[$m];
  $tpl_data['selected_date']['month'] = $m;
  $tpl_data['selected_date']['year'] = $a;
  $tpl_data['selected_date']['first_day'] = $dayone;

  $tpl_data['current_date']['day'] = Date("j");
  $tpl_data['current_date']['month'] = Date("n");
  $tpl_data['current_date']['year'] = Date("Y");

  $offset_date = strtotime('-1 month', mktime(0, 0, 0, $m, 1, $a));
  $tpl_data['one_month_before']['month'] = Date("n", $offset_date);
  $tpl_data['one_month_before']['year'] = Date("Y", $offset_date);

  $offset_date = strtotime('1 month', mktime(0, 0, 0, $m, 1, $a));
  $tpl_data['one_month_after']['month'] = Date("n", $offset_date);
  $tpl_data['one_month_after']['year'] = Date("Y", $offset_date);

  // D�finition du template de contenu
  $tpl_data['content_template_file'] = 'add_step2.tpl.php';
  // Affichage de la page
  include('templates/'.TEMPLATE_NAME.'/page.tpl.php');
?>
