<?php
session_start();
if (isset($_SESSION['lang']))
{
require_once ('../../lang/'.$_SESSION['lang'].'.inc.php');
}
else
{require_once ('../../lang/en_GB.inc.php');
	$_SESSION['lang']='en_GB';
}
require_once ('../lang.inc.php');
?>
<html>
 
 <head>
 	<title>Rdvz 1.2</title>
 	<link rel="stylesheet" href="../../templates/uapv/style.css">

</head>

<body>
<h1><?php tr('Congratulations'); ?></h1>
<p><?php tr('RDVZ installed'); ?></p>

<?php tr('Click on the following link to use RDVZ'); ?>  :  
<a href="../../index.php">RDVZ</a> 
   

</body>

</html>
