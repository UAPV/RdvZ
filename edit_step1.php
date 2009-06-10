<?php
  require_once('includes/config.inc.php');
  require_once LangueFile;
  require_once('includes/connection.php');
  require_once ('authentication.php');

  unset($_SESSION['dates']);
  unset($_SESSION["meeting_title"]);
  unset($_SESSION["meeting_description"]);

  //recuperer l'information generale
  if (isset($_GET["mid"]) AND strlen($_GET["mid"])==8 )
    {
      $mid = escape($_GET["mid"]);
      $sql = "SELECT title,description FROM meeting WHERE mid='$mid';";
      $res = $BDD->getRow($sql);
      if (!isset($res[0]))
          die(MeetingNotFound);
      $title = $res[0];
      $description = $res[1];
      $_SESSION["mid"] = $_GET["mid"];
    }
  else
    {
      die(MeetingNotFound);
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
  <form id="form1" name="form1" method="post" action="edit_step2.php">
    <h4><img src="images/stat_forum.gif" width="32" height="32" style="vertical-align: middle;" /><?php echo Informations; ?></h4>
    <hr />
    <p><br />
      <?php echo Title ?><br />
      <input name="meeting_title" type="text" class=text value="<?php echo $title ?>" size="50" />
    </p>
    <p><?php echo Description ?><br />
      <textarea name="meeting_description" cols="50" rows="6"><?php echo $description ?></textarea>
    </p>
    <p>
      <input type="hidden" name="mid" value="<?php echo $mid ?>" />
      <br />
<?php
  //recuperer les dates et les commentaires enregistrees
  $sql = "SELECT date,comment FROM meeting_date WHERE mid='$mid' ORDER BY date;";
  $res = $BDD->query($sql);
  $i = 0;
  while ($res->fetchInto($row))
  {
	$a = substr($row[0], 0, 4);// conversion       
	$m = substr($row[0], 5, 2);// de la date      
	$j = substr($row[0], 8, 2);// au format francais
	$date_fr = $j . '-' . $m . '-' . $a;

	$dates[] = $date_fr;
	echo '<input type="hidden" name="' . $i . '" value="' . $row[1] . '" />';
	$i++;
  }
  $_SESSION['dates'] = $dates;
?>
      <br />
    </p>
    <div class="buttons">
      <button type="submit"> <?php echo Next ?><img src="images/next.png" width="16" height="16" /></button>
      <button type="button" onClick="window.open('index.php','_self')" > <img src="images/cross.png" width="8" height="18" /><?php echo Cancel ?></button>
    </div>
  </form>
</div>
</body>
</html>
