<?php
/**
 *@package RDVZ
 *@author Alexandre Cuniasse - UAPV
 *@version 1.0 
 *@license http://www.cecill.info/licences/Licence_CeCILL_V2-fr.txt
 */

  ?>
 
 <html>
 
 <head>
 	<title>Rdvz 1.2</title>
 	<link rel="stylesheet" href="../../templates/uapv/style.css">

</head>
 
 
 <body>
<h1> RDVZ 1.2 </h1>

 <form action="install_choose_auth.php" id="f"method="POST">
 
  Langue d'installation : fran&ccedil;ais <input type="radio" id="lang" name="lang" value="fr_FR" checked="checked" onclick="f.submit();"><br>
  Installation language : english <input type="radio" id="lang" name="lang" value="en_GB" onclick="f.submit() ;"><br>


<input type="submit" value=">>>">    
  </form>
 

</body> 
 </html>