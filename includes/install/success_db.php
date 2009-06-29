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
 	<title>Rdvz 1.1</title>
 	<link rel="stylesheet" href="../../templates/uapv/style.css">

</head>

<body>
<h1><?php tr('Congratulations'); ?></h1>
<p><?php tr('RDVZ installed'); ?></p>

<p><?php tr('Default user was created'); ?> 
<ul>
<li><b><?php tr('Login');?> : rdvz</li>
<li><?php tr('Password'); ?> : rdvz_pwd</li></b>
</p>
</ul>

<?php tr('Click on the following link to use RDVZ'); ?>  
<a href="../../index.php">RDVZ</a> 

   

</body>

</html>
