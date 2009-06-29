<?php
session_start();
/**
 *@package RDVZ
 *@author Alexandre Cuniasse - UAPV
 *@version 1.0 
 *@license http://www.cecill.info/licences/Licence_CeCILL_V2-fr.txt
 */

require_once ('../../lang/fr_FR.inc.php');
 require_once ('../lang.inc.php');
  ?>
 
 <html>
 
 <head>
 	<title>Rdvz 1.03</title>
 	<link rel="stylesheet" href="../../templates/uapv/style.css">

</head>
 
 
 <body>


 <h1>Erreur !</h1>
 
 <?php echo $_SESSION['msg']; ?>

 

</body> 
 </html><?php
/*
 * Created on 29 août 08
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>
