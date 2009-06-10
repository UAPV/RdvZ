<?php
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
 	<title>Rdvz 1.1</title>
 	<link rel="stylesheet" href="../../templates/uapv/style.css">

</head>
 
 
 <body>


 <h1><?php tr('Install Welcome');?></h1>
 <p><?php tr('Please check installation rights');?>
 <p><?php tr ('Please choose your authentication way'); ?></p>
 <form action="install2.php" method="POST">
      Base de donnees <input type="radio" id="db_params_button" name="auth_type" value="db"><br>
      CAS <input type="radio" id="cas_params_button" name="auth_type" value="cas" checked="checked"><br>

<input type="submit" value="Suivant">    
  </form>
 

</body> 
 </html>