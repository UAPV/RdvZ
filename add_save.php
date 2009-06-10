<?php 
/********************************************
Fonction: Sauvegarder une reunion dans la BD
Version: 1.0 
Date: Decembre 2007
Auteurs: Liang HONG, Min WANG
IUP GMI d'Avignon
http://www.iup.univ-avignon.fr
********************************************/

require_once ('includes/config.inc.php');
require_once LangueFile;
require_once ('authentication.php');
require_once ('includes/connection.php');

//recuperer les information generales
$meeting_title = $_SESSION['meeting_title'];
$meeting_description = $_SESSION['meeting_description'];

//generer un mid(Meeting ID) aleatoire
do {
    	$chars='0123456789abcdefghijklmnopqrstuvwxyz'; // characters to build the password from 
    	$mid=''; 
    	$len=8;
    	for($len;$len>=1;$len--) 
    	{
    	    $position=rand()%strlen($chars);
    	    $mid.=substr($chars,$position,1); 
   	}
	//verifier si le mid existe
	$sql = "SELECT mid FROM meeting WHERE mid='$mid';";
	$res= $BDD->query($sql);
	$mid_exist = $res->numRows();
}
while ($mid_exist != 0); //on fait ce boucle jusqu'on trouve un mid non redondant

//inserer les information generales dans la base de donnees
$sql = "INSERT INTO meeting(mid,uid,title,description) VALUES('$mid','$uid','$meeting_title','$meeting_description');";
$BDD->query($sql);

//inserer les options de votes dans la base de donnees
if (count($_SESSION['dates'])>0){
foreach ($_SESSION['dates'] as  $key => $value) {
	$comment = $_POST[$key];
	$a = substr($value, 6, 4);     // conversion 
	$m = substr($value, 3, 2);     // de la date 
	$j = substr($value, 0, 2);     // au format 
	$date = $a.'-'.$m.'-'.$j;             // anglais	
	$sql = "INSERT INTO meeting_date(mid,date,comment) VALUES('$mid','$date','$comment');";
	$BDD->query($sql);
}
$BDD->disconnect();

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo PageTitle ?></title>
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
<div class="footer">
<a href="<?php echo $footer_url;?>" target="blank"><?php echo $footer_msg; ?></a>
</div>
</body>
</html>
