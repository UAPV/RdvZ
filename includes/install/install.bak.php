<?php
/**
 *@package RDVZ
 *@author Alexandre Cuniasse - UAPV
 *@version 1.0 
 *@license http://www.cecill.info/licences/Licence_CeCILL_V2-fr.txt
 */
 require_once('includes/config.inc.php');
 require_once ('lang/'.LOCALE.'.inc.php');
 require_once ('includes/lang.inc.php');
  ?>
 
 <html>
 
 <head>
 	<title>Rdvz 1.0</title>
 	<script type="text/javascript" src="includes/yui/build/yahoo/yahoo.js"></script>
	<script type="text/javascript" src="includes/yui/build/event/event.js"></script>
	<script type="text/javascript" src="includes/yui/build/dom/dom.js"></script>
	<script type="text/javascript" src="includes/yui/build/container/container_core.js"></script>
	
	<link rel="stylesheet" href="templates/uapv/style.css">

</head>
 
 
 <body>

<script type="text/javascript">
	
	function init() {
	db_params = new YAHOO.widget.Module("db_params", { visible: false });
	cas_params = new YAHOO.widget.Module("cas_params", { visible: false });
	YAHOO.util.Event.addListener("db_params_button", "check", dp_params.show, db_params, true);
	
	db_params.render();
	cas_params.render();
	}
	
	YAHOO.util.Event.addListener(window, "load", init);
	
	
	
	
	 //var SelectAuth = new YAHOO.widget.Button( {id:"button_db", type:"checkbox",label:"Base de données",container:"button_container", checked:false});
	var ButtonGroup = new YAHOO.widget.ButtonGroup("buttongroup")
	
	
	
	YAHOO.util.Event.addListener("db_params", "click", YAHOO.example.container.module1.show, YAHOO.example.container.module1, true);
YAHOO.util.Event.addListener("hide1", "click", YAHOO.example.container.module1.hide, YAHOO.example.container.module1, true);

YAHOO.util.Event.addListener("show2", "click", YAHOO.example.container.module2.show, YAHOO.example.container.module2, true);
YAHOO.util.Event.addListener("hide2", "click", YAHOO.example.container.module2.hide, YAHOO.example.container.module2, true);
	
	
	
	
	function obc()
	{
	
	if (oButton.get("checked"))
	{	
	oButton.set("label","Fermer les options");
	module1.show();
	}
	else
	{
	oButton.set("label","Afficher les options");
	module1.hide();
	}
	}
	oButton.addListener("checkedChange",obc);
	</script>


 
 
 <h1><?php tr('Install Welcome');?></h1>
 
 <?php tr ('Please choose your authentication way'); ?>
 
 
 <div id="buttongroup" class="yui-buttongroup">
    Base de donnees <input id="db_params_button" type="radio" name="radiofield1" value="BDD" checked><br>
    CAS <input id="cas_params_button" type="radio" name="radiofield1" value="CAS"><br>
 </div>
 
 <div id="db_params">
 db
 </div>
 
 <div id="cas_params"
 cas
 </div>
 

</body> 
 </html>

  
