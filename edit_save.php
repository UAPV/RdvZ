<?php
  $path=ini_get('include_path');
$newpath="includes".PATH_SEPARATOR.$path;
ini_set('include_path',$newpath);
  
  
  require_once ('includes/config.inc.php');
  require_once('authentication.php');
  require_once LangueFile;
  require_once('includes/connection.php');

  $meeting_title = escape($_SESSION['meeting_title']);
  $meeting_description = escape($_SESSION['meeting_description']);

  if (isset($_POST["mid"]) AND strlen($_POST["mid"])==8 && $_POST["mid"] == $_SESSION["mid"])
  {
      $mid = escape($_SESSION["mid"]);
  }
  else
      die(MeetingNotFound);

  //verification si la personne qui veut editer cette reunion = le proprietaire
  $sql = "SELECT uid FROM meeting WHERE mid='$mid'";
  
  //le proprietaire de cette reunion
  $uid2 = $BDD->getOne($sql);
  
  //si la personne qui lance la suppression != le proprietaire
  if ($uid != $uid2)
  {
      header("Location: index.php");
  }
	
  //supprimer les anciens donnees
  $sql = "DELETE FROM meeting_poll WHERE pollid IN ( SELECT pollid FROM meeting_date WHERE mid='$mid'  )";
  $BDD->query($sql);
  $sql = "DELETE FROM meeting_date WHERE mid='$mid'";
  $BDD->query($sql);
  $sql = "DELETE FROM meeting WHERE mid='$mid' ";
  $BDD->query($sql);
  
  //inserer les nouveaux informations
  $sql = "INSERT INTO meeting(mid,uid,title,description) VALUES('$mid','$uid','$meeting_title','$meeting_description');";
  $BDD->query($sql);
   
  if (count($_SESSION['dates']) > 0)
    {
      foreach ($_SESSION['dates'] as $key => $value)
        {
          $comment = escape($_POST[$key]);
           
          $a = substr($value, 6, 4);// conversion         
          $m = substr($value, 3, 2);// de la date         
          $j = substr($value, 0, 2);// au format anglais
          $date = $a . '-' . $m . '-' . $j; 
          
          $sql = "INSERT INTO meeting_date(mid,date,comment) VALUES('$mid','$date','$comment');";
          $BDD->query($sql);
        }
    }
   $BDD->disconnect();
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Prise de reunion</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="meeting.css" />
</head>
<body>
<div id="container">
  <h4><img src="images/OK.png" width="32" height="32" align="absmiddle" /> <?php echo Thanks ?></h4>
  <hr />
  <p><?php printf( InformationSaved, $mid) ?><br />
    	<?php 
		$adr= "http://".$_SERVER['SERVER_NAME'].ereg_replace("\/[^\/]*\.(html|php)(\?.*)?","",$_SERVER['REQUEST_URI'])."/participation.php?mid=$mid";
		echo '<a href="'.$adr.'" target="_blank" >'.$adr.'</a>';
	?>
  </p>
  <p><?php echo InformationSaved_Notice ?></p>
  <div class="buttons">
    <button type="button" onClick="window.open('index.php','_self')" > <img src="images/arrow.gif" width="18" height="18" /><?php echo MyMeetings ?></button>
  </div>
</div>
</body>
</html>
