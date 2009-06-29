<?php
session_start();
/**
 *@package RDVZ
 *@author Alexandre Cuniasse - UAPV
 *@version 1.0 
 *@license http://www.cecill.info/licences/Licence_CeCILL_V2-fr.txt
 */


if (isset($_POST['lang']))
{
require_once ('../../lang/'.$_POST['lang'].'.inc.php');
$_SESSION['lang']=$_POST['lang'];
}
else
{require_once ('../../lang/en_GB.inc.php');
	$_SESSION['lang']='en_GB';
}
require_once ('../lang.inc.php');
  ?>
 
 <html>
 
 <head>
 	<title>Rdvz 1.1</title>
 	<link rel="stylesheet" href="../../templates/uapv/style.css">

</head>
 
 
 <body>


 <h1><?php tr('Install Welcome');?></h1>
 <p><?php tr('Please check installation rights');?>
 <p><?php tr ('Please choose your authentication way'); ?></p>
 <form action="install2.php" method="POST">
      <?php tr('DB'); ?> <input type="radio" id="db_params_button" name="auth_type" value="db" onclick="this.form.submit();"><br>
      CAS <input type="radio" id="cas_params_button" name="auth_type" value="cas" checked="checked" onclick="this.form.submit();"><br>
<input type="submit" value=">>>">
  </form>
 

</body> 
 </html>