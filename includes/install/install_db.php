<?php session_start(); ?>
<html>
<head>
	<title>Rdvz 1.1</title>
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
	<li> <?php tr('Directory writable'); ?></li>
	<li> <?php tr('Rights OK'); ?></li>
</ul>
<?php tr('If OK you can complete'); ?>
</p> 
<form action="write_conf_db.php" method="POST" id="frm" onsubmit="return checkDataDB(this);">

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
<?php tr('No'); ?><input type="radio" name="create_struct" id="create_struct" value="N" checked="checked">
</fieldset>

<fieldset class="fieldset_params">
<legend><?php tr('Users database parameters'); ?> </legend>
<?php tr('Database host'); ?>  <input type="text" name="users_db_host" id="users_db_host"><br>
<?php tr('Database name'); ?><input type="text" name="users_db_name" id="users_db_name" ><br>
<?php tr('Password'); ?>  <input type="text" name="users_db_login" id="users_db_login"><br>
<?php tr('Password'); ?><input type="password" name="users_db_pwd" id="users_db_pwd" ><br>
<?php tr('Create DB ?'); ?>
<?php tr('Yes');?><input type="radio" name="create_db_users" value="O" onClick="checkCase(document.forms['frm'].elements['create_table_users']);">
<?php tr('No');?><input type="radio" name="create_db_users" value="N" checked="checked"><br>
<?php tr('Users table'); ?><input type="test" name="users_table" value="rdvz_users"><br>
<?php tr('Login field'); ?> <input type="text" name="users_field" value="login"><br>
<?php tr('Password field');?> <input type="text" name="pwd_field" value="pwd"><br>
<?php tr('email field'); ?>  <input type="text" name="email_field" value="email"><br>
<?php tr('Create table'); ?><br/>
<?php tr('Yes'); ?><input type="radio" name="create_table_users" id="create_table_users" value="O">
<?php tr('No'); ?><input type="radio" name="create_table_users" id="create_table_users" value="N" checked="checked"><br>
<?php tr('Password encryption'); ?> 
<select name="pwd_encr">
<option value="none"><?php tr('Clear'); ?></option>
<option value="md5">MD5</option>
</select>


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
