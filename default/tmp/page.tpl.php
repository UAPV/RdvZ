<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title><?php echo SITE_NAME; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="templates/<? echo TEMPLATE_NAME; ?>/style.css" />
    <script type="text/javascript">
    </script>
  </head>
  <body>
     <div id="page">
     
      <h1><? echo SITE_NAME; ?></h1>
      
      <?php include($tpl_data['content_template_file']); ?>
      </div>
      <div class="footer">
      
      <p class="titre">
      	<a href="http://gpl.univ-avignon.fr/"> Un logiciel libre de l'Universit&eacute; d'Avignon et des Pays de Vaucluse </a>
      <p>     
      	 </div>
  </body>
</html>
