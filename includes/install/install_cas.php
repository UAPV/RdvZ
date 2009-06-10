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

<form action="write_conf_cas.php" method="POST" onsubmit="return checkDataCAS(this);" id="frm">
<fieldset class="fieldset_params">
<legend>Param&egrave;tres de la base de donn&eacute;es de l'application</legend>
H&ocirc;te de la base de donn&eacute;es : <input type="text" name="db_host"><br>
Nom de la base : <input type="text" name="db_name"><br>
Nom d'utilisateur :  <input type="text" name="db_login"><br>
Mot de passe :  <input type="password" name="db_pwd"><br>
Cr&eacute;er la base ?
Oui<input type="radio" name="create_db" value="O" onClick="checkCase(document.forms['frm'].elements['create_struct']);">
Non<input type="radio" name="create_db" value="N" checked="checked"><br>
Cr&eacute;er les tables ?
Oui<input type="radio" name="create_struct" id="create_struct" value="O">
Non<input type="radio" name="create_struct" id="create_struct" value="N" checked="checked">
</fieldset>


<fieldset class="fieldset_params">
<legend>Param&egrave;tres du serveur CAS</legend>
Nom du serveur CAS : <input type="text" name="casservername"><br>
</fieldset>



<fieldset class="fieldset_params">
<legend>Param&egrave;tres des serveurs LDAP</legend>
Serveur(s) LDAP : <input type="text" name="ldap" size=60 ><br>
(S'il y a plusieurs serveurs, les s&eacute;parer par des points-virgules)<br>

Utilisateur pour lecture seule : <input type="text" name="dnread"></br>
Mot de passe pour lecture seule : :<input type="password" name="ldapread_pwd"><br>
Racine de la branche utilisateur : <input type="text" name="user_root">
</fieldset>

<fieldset class="fieldset_params">
<legend> Param&egrave;tres pour l'envoi de mail </legend>
Adresse e-mail de l'utilisateur : <input type="text" name="email_admin"><br>
Serveur smtp : <input type="text" name="smtp_server"><br>
Port du serveur smtp : <input type="text" name="smtp_port"><br>
</fieldset>

 <fieldset class="fieldset_params">
 <legend>Param&egrave;tres divers</legend>
 Nom du site : <input type="text" name="site_name"><br>
 </fieldset>
 <input type="submit" value="Suivant">


</form>


</body>

</html>