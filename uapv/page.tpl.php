<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo SITE_NAME; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="templates/uapv/fat.js"></script>
	<script type="text/javascript" src="templates/uapv/nifty.js"></script>
	<script type="text/javascript">
	window.onload=function(){
	if(!NiftyCheck())
	    return;
	Rounded("div#titre","#FFFFFF","#1E52A1");
	Fat.fade_all();
	}
</script>
<script type="text/javascript" src="includes/yui/build/yahoo/yahoo.js"></script>
<script type="text/javascript" src="includes/yui/build/event/event.js"></script>
<script type="text/javascript" src="includes/yui/build/dom/dom.js"></script>
<script type="text/javascript" src="includes/yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="includes/yui/build/element/element-beta-min.js"></script>
<script type="text/javascript" src="includes/yui/build/button/button-min.js"></script>
<script type="text/javascript" src="includes/yui/build/container/container_core.js"></script> 
<script type="text/javascript" src="includes/yui/build/datasource/datasource-beta-min.js"></script> 
<script type="text/javascript" src="includes/yui/build/connection/connection.js"></script> 
<script type="text/javascript" src="includes/yui/build/datatable/datatable-beta-min.js"></script>
<script type="text/javascript" src="includes/yui/build/menu/menu-min.js"></script>



 <link rel="stylesheet" type="text/css" href="templates/<? echo TEMPLATE_NAME; ?>/style.css" />
 
  </head>
  <html>
  <body class="yui-skin-sam">
  <div id="titre">
<h1 class="titre"> >>> <?php echo SITE_NAME; ?></h1>
</div>
  
  
  
  
     <div id="page">
     
      
        <?php include($tpl_data['content_template_file']); ?>
      </div>
      <div class="footer">
        	<a href="http://gpl.univ-avignon.fr/"> Un logiciel libre de l'Universit&eacute; d'Avignon et des Pays de Vaucluse </a>
           
      	 </div>
  </body>
</html>
