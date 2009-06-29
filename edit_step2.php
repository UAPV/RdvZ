<?php

  $path=ini_get('include_path');
$newpath="includes".PATH_SEPARATOR.$path;
ini_set('include_path',$newpath);
  
  
  require_once('includes/config.inc.php');
  require_once LangueFile;
  require_once ('authentication.php');


  if (isset($_POST["mid"]) AND strlen($_POST["mid"])==8 && $_POST["mid"] == $_SESSION["mid"])
  {
       $mid = $_POST["mid"];
  }
  else die(invalidMeeting);

  //Premièrement on vérifie si des variables on déjà été envoyées pour le mois et l'année 
  //mois
  if (!isset($_GET["m"])) {
      $m = date("n");
  }
  else
  {
      	$m = $_GET["m"];
	//enlever le premier zero
	if (substr($m, 0, 1) == "0") $m = substr($m, 1, 1);
	if (strlen($m)>2 || ($m < 1 || $m > 12) ) die ('variable invalide') ;
  }
  //annee
  if (!isset($_GET["a"]))
  	$a = date("Y");
  else
  {
      	$a = $_GET["a"];
	if ( strlen($a) != 4 || !is_numeric($a)) die ('variable invalide') ;   
  }
  //recuperer l'information generale sur la reunion
  if (isset($_POST["meeting_title"])) {
  	$_SESSION["meeting_title"] = $_POST["meeting_title"];
  }
  
  if (isset($_POST["meeting_description"]) ) {
  	$_SESSION["meeting_description"] = $_POST["meeting_description"];
  }
  
  //recuperer les dates selectionnees 
  if (isset($_SESSION['dates'])) {
      //ancien dates
      $dates = $_SESSION['dates'];
  }
  
  //utilisateur demande d'ajouter une nouvelle date 
  if (isset($_GET["date"]) && preg_match("/^([0-3][0-9])-([0-1][0-9])-20[0-9]{2}$/",$_GET["date"],$matches) && $matches[1] >= 1 && $matches[1] <= 31 && $matches[2] >= 1 && $matches[2] <= 12) {
      $dates[] = $_GET["date"];
  }
  
  //utilisateur demande de supprimer une date
  if (isset($_GET["del_date"])) {
      $del_date = $_GET["del_date"];
      unset($dates[$del_date]);
      reset($dates);
  }
  
  //mis a jour
  $_SESSION['dates'] = $dates;
  
  $mnom = array("", January, February, March, April, May, June, July, August, September, October, Nevember, December);
  
  $dayone = date("w", mktime(1, 1, 1, $m, 1, $a));
  if ($dayone == 0)
      $dayone = 7;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo PageTitle ?></title>
<script type="text/javascript">
 function submitForm(url){
   var login=document.getElementById('form1'); 
    login.action=url;
    login.submit();
   return true;
}
</script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="meeting.css" />
</head>
<body>
<div id="container">
  <form id="form1" name="form1" method="post" action="edit_step2.php">
    <h4><img src="images/stat_calendar.gif" width="32" height="32" style="vertical-align: middle;" /><?php echo SelectionDate ?></h4>
    <hr />
    <p><?php echo HintSelectionDate ?></p>
    <table width="100%" border="0">
      <tr>
        <td width="310" valign="top"><div align="center"><font size=4>
	<?php
		$moisdeb = Date("n");
		$anneedeb = Date("Y");

		// si mois de janvier et annee precedente est sup ou egale a annee existente
		// si mois pr�cedents et dans l'ann�e et que le mois n'est pas pass�
		if ( ($m-1==0 && ($anneedeb <=$a-1 && $moisdeb <= 12)) || ($m-1 != 0 && $anneedeb <= $a && $moisdeb <= $m )  )
    		{
      			if ($m == 1)
        		{
          			$an = $a - 1;
          			$mois = 12;
        		}
      			else
        		{
				$an = $a;
          			$mois = $m - 1;
        		}
      			$post_url = "edit_step2.php?m=" . $mois . "&amp;a=" . $an;
      			//lien vers le mois precedent
      			echo '<a href="#"  onClick="javascript:submitForm(' . "'" . $post_url . "'" . ');return false;">&lt;&lt;</a> ';
      		}
      			echo $mnom[$m] . " " . $a;
		// si mois de decembre et annee suivante est sup ou egale a annee existente (presque sur)
		// si mois suivant et dans l'ann�e et que le mois n'est pas pass�
      		if ( ($m+1==13 && ($anneedeb <=$a+1 && $moisdeb <= 1)) || ($m+1 != 13 && $anneedeb <= $a && $moisdeb <= $m )  )
		{
			if ($m == 12)
        		{
          			$an = $a + 1;
          			$mois = 1;
        		}
      			else
        		{
          			$an = $a;
          			$mois = $m + 1;
        		}
      
      			$post_url = "edit_step2.php?m=" . $mois . "&amp;a=" . $an;
      			//lien vers le mois suivant
      			echo '&nbsp;<a href="#"  onClick="javascript:submitForm(' . "'" . $post_url . "'" . ');return false;">&gt;&gt;</a>';
    		}
	?>
            </font> <br />
            <br />
          </div>
          <table width=300 cellspacing=0 border="1">
            <tr  class="paire">
              <th><?php echo Mon ?></th>
              <th><?php echo Tue ?></th>
              <th><?php echo Wed ?></th>
              <th><?php echo Thu ?></th>
              <th><?php echo Fri ?></th>
              <th><?php echo Sat ?></th>
              <th><?php echo Sun ?></th>
	<?php
  		$jourdeb = Date("j");
		//afficher le calendrier
  		if ($m < 10 and strlen($m) < 2)
      		$m = '0' . $m;
  
  		for ($i = 1; $i <= 42; $i++)
    		{
      			if ($i % 7 == 1)
        		{
          			echo '</tr><tr>';
        		}
      			if (($i < (cal_days_in_month(CAL_GREGORIAN, $m, $a) + $dayone)) && ($i >= $dayone))
        		{
          			
          			$day = $i - $dayone + 1;
				if($m == $moisdeb && $a == $anneedeb && $day < $jourdeb){
					echo "<th bgcolor=silver>$day</th>";
				}
				else
				{
					echo '<th>';
					if ($day < 10)
					$day = '0' . $day;
		
					$post_url = "edit_step2.php?date=" . $day . "-" . $m . "-" . $a . "&amp;m=" . $m . "&amp;a=" . $a;
					echo '<a href="#"  class="linkdate" onClick="javascript:submitForm(' . "'" . $post_url . "'" . ');return false;">';
					echo $i - $dayone + 1;
					echo '</a></th>';
				}
        		}
      			else
        		{
          			echo '<th bgcolor=silver>&nbsp;</th>';
        		}
    		}
	?>
          </table></td>
        <td valign="top"><p><strong><img src="images/arrow.gif" width="18" height="18" /> <?php echo DatesSelected ?></strong></p>
        <?php
  		//afficher les dates selectionnees
  		if (count($dates) > 0)
    		{
      			echo '<table width="279" border="0">
            		<tr>
              		<td width="117">
				<div align="center">Date</div>
			</td>
              		<td width="152">
				<div align="center">' . Comment . '<a href="#" class="tip"><img src="images/help.png" width="16" height="16" border="0" style="vertical-align: middle;"/><span class="popbox">' . TipDate . '</span></a></div>
			</td>
            		</tr>
          		</table>';
      
      			foreach ($dates as $key => $value)
        		{
          			$post_url = "edit_step2.php?del_date=" . $key . "&amp;m=" . $m . "&amp;a=" . $a;
          			echo '<a href="#"  onClick="javascript:submitForm(' . "'" . $post_url . "'" . ');return false;">';
          			echo '<img src="images/del.gif" alt="Supprimer" width="16" height="16" border="0" style="vertical-align: middle;" /></a>' . $value . '<input type="text" class="text" name="' . $key . '" ';
		  
          			//afficher les commentaires
          			if (isset($_POST[$key]))
            			{
              				$commentaire = $_POST[$key];
              				echo "value='" . $commentaire . "'";
            			}
          			echo " /><br />\n";
        		}
    		}
	?>
        </td>
      </tr>
    </table>
    
	<input type="hidden" name="mid" value="<?php echo $mid; ?>" />
    	<br />    <p><img src="images/warning.png" width="16" height="16" style="vertical-align: middle;" />
      	<span style="font-size: 11px"><?php echo EditWarning; ?></span></p>
    <div class="buttons">
      <button type="button" onClick="javascript:submitForm('edit_save.php')"> <img src="images/save.png" width="16" height="16" /><?php echo Finish ?></button>
      <button type="button" onClick="window.open('index.php','_self')" > <img src="images/cross.png" width="8" height="18" /><?php echo Cancel ?></button>
    </div>
  </form>
</div>
</body>
</html>
