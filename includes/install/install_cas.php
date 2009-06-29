<?php session_start(); ?>
<html>
<head>
	<title>Rdvz 1.2</title>
	<link rel="stylesheet" href="../../templates/uapv/style.css">
	<script type="text/javascript" src="libinstall.js"></script>
</head>

<body>

<?php 
$path=ini_get('include_path');
$newpath="..:".$path;
ini_set('include_path',$newpath);
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


<p>
<?php tr('Before processing'); ?>

<ul>
	<li> <?php tr('Directory writable'); ?> </li>
	<li> <?php tr('Rights OK'); ?></li>
</ul>
<?php tr('If OK you can complete'); ?>


<form action="write_conf_cas.php" method="POST" onsubmit="return checkDataCAS(this);" id="frm">
<fieldset class="fieldset_params">
<legend><?php tr('Application database parameters'); ?></legend>
<?php tr('Database host'); ?>  <input type="text" name="db_host"><br>
<?php tr('Database name'); ?><input type="text" name="db_name"><br>
<?php tr('User name'); ?>  <input type="text" name="db_login"><br>
<?php tr('Password'); ?>  <input type="password" name="db_pwd"><br>
<?php tr('Create DB ?'); ?><br/>
<?php tr('Yes');?> <input type="radio" name="create_db" value="O" onClick="checkCase(document.forms['frm'].elements['create_struct']);">
<?php tr('No');?><input type="radio" name="create_db" value="N" checked="checked"><br>
<?php tr('Create table'); ?><br/>
<?php tr('Yes'); ?><input type="radio" name="create_struct" id="create_struct" value="O">
<?php tr('No'); ?><input type="radio" name="create_struct" id="create_struct" value="N" checked="checked"><br/>
<?php tr('Update from 1.1'); ?><br/>
<?php tr('Yes'); ?><input type="radio" name="update" id="update" value="O">
<?php tr('No'); ?><input type="radio" name="update" id="update" value="N" checked="checked"><br/>
</fieldset>


<fieldset class="fieldset_params">
<legend><?php tr('CAS parameters'); ?></legend>
<?php tr('CAS server name'); ?> <input type="text" name="casservername"><br>
<?php tr('CAS folder'); ?>  <input type="text" name="cas_folder"><br>
<?php tr('CAS port'); ?>  <input type="text" name="cas_port"><br>
</fieldset>



<fieldset class="fieldset_params">
<legend><?php tr('LDAP parameters'); ?></legend>
<?php tr('LDAP servers'); ?>  <input type="text" name="ldap" size=60 ><br>
<?php tr('If several LDAP servers'); ?>
<?php tr('User for read only'); ?> <input type="text" name="dnread"></br>
<?php tr('Password for read only'); ?> :<input type="password" name="ldapread_pwd"><br>
<?php tr('User branch root'); ?>  <input type="text" name="user_root">

</fieldset>

<fieldset class="fieldset_params">
<legend> <?php tr('Mail parameters'); ?></legend>
<?php tr('e-mail'); ?>  <input type="text" name="email_admin"><br>
<?php tr('smtp server'); ?> <input type="text" name="smtp_server"><br>
<?php tr('smtp port'); ?> <input type="text" name="smtp_port"><br>
</fieldset>

 <fieldset class="fieldset_params">
 <legend><?php tr('Miscellaneous parameters'); ?> </legend>
 <?php tr('Site name'); ?> <input type="text" name="site_name"><br>
 </fieldset>
 <input type="submit" value="<?php tr('Next'); ?>">


</form>


</body>

</html>