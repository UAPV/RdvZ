<?php
/*
 * Created on 22 juil. 08
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 
 if (isset($_POST['auth_type']) && $_POST['auth_type']=='cas') include('install_cas.php');
 elseif (isset($_POST['auth_type']) && $_POST['auth_type']=='db') include('install_db.php');
 else echo "Erreur inconnue";
 
?>
