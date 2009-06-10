
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
?>
<p>
Avant de proc&eacute;der &agrave l'installation, v&eacute;rifiez bien que : 
<ul>
	<li> Le r&eacute;pertoire "includes" est accessible en &eacute;criture. </li>
	<li> Vous poss&eacute;dez les droits n&eacute;cessaires sur vos bases de donn&eacute;es.</li>
</ul>
Si c'est bien le cas, vous pouvez maintenant remplir les champs suivants :
</p> 
<form action="write_conf_db.php" method="POST" id="frm" onsubmit="return checkDataDB(this);">

<fieldset class="fieldset_params">
<legend>Param&egrave;tres de la base de donn&eacute;es de l'application</legend>
H&ocirc;te de la base de donn&eacute;es : <input type="text" name="db_host" onchange="chgVal(this.value,'users_db_host');"><br>
Nom de la base : <input type="text" name="db_name" onchange="chgVal(this.value,'users_db_name')" ><br>
Nom d'utilisateur :  <input type="text" name="db_login" onchange="chgVal(this.value,'users_db_login')"  ><br>
Mot de passe :  <input type="password" name="db_pwd" onchange="chgVal(this.value,'users_db_pwd')" ><br>
Cr&eacute;er la base ?
Oui<input type="radio" name="create_db" value="O" onClick="checkCase(document.forms['frm'].elements['create_struct']);">
Non<input type="radio" name="create_db" value="N" checked="checked"><br>
Cr&eacute;er les tables ?
Oui<input type="radio" name="create_struct" id="create_struct" value="O">
Non<input type="radio" name="create_struct" id="create_struct" value="N" checked="checked">
</fieldset>

<fieldset class="fieldset_params">
<legend> Param&egrave;tres de la base de donn&eacute;es contenant les utilisateurs</legend>
H&ocirc;te de la base de don&eacute;es : <input type="text" name="users_db_host" id="users_db_host"><br>
Nom de la base : <input type="text" name="users_db_name" id="users_db_name" ><br>
Nom d'utilisateur :  <input type="text" name="users_db_login" id="users_db_login"><br>
Mot de passe :  <input type="password" name="users_db_pwd" id="users_db_pwd" ><br>
Cr&eacute;er la base ?
Oui<input type="radio" name="create_db_users" value="O" onClick="checkCase(document.forms['frm'].elements['create_table_users']);">
Non<input type="radio" name="create_db_users" value="N" checked="checked"><br>
Table contenant les utilisateurs : <input type="test" name="users_table" value="rdvz_users"><br>
Nom du champ contenant les logins des utilisateurs :  <input type="text" name="users_field" value="login"><br>
Nom du champ contenant les mots de passe des utilisateurs : <input type="text" name="pwd_field" value="pwd"><br>
Nom du champ contenant les e-mails des utilisateurs : <input type="text" name="email_field" value="email"><br>
Cr&eacute;er les tables ?
Oui<input type="radio" name="create_table_users" id="create_table_users" value="O">
Non<input type="radio" name="create_table_users" id="create_table_users" value="N" checked="checked"><br>

</fieldset>

<fieldset class="fieldset_params">
<legend> Param&egrave;tres pour l'envoi de mail </legend>
Adresse e-mail de l'administrateur : <input type="text" name="email_admin"><br>
Serveur smtp : <input type="text" name="smtp_server"><br>
Port du serveur smtp : <input type="text" name="smtp_port" value="25"><br>
</fieldset>

 <fieldset class="fieldset_params">
 <legend>Param&egrave;tres divers</legend>
 Nom du site : <input type="text" name="site_name" value="RDVZ"><br>
 </fieldset>
 <input type="submit" value="Suivant">
 
</form>


</body>


</html>
