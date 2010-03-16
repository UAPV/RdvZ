<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
  <head>
    <title>
      <?php if (!include_slot('title')): ?>
        RdvZ 2.0
      <?php else : ?>
        <?php include_title() ?>
      <?php endif; ?>
    </title>
    <script type="text/javascript" src="/js/jquery-1.3.2.min.js"></script> 
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_stylesheets() ?>
  </head>
  <body>
    <?php echo link_to('<img src="/images/rdvz_logo3.png" id="logo" />','homepage') ?>
<!--    <div id="menu">
    </div> -->
    <div id="page">
    <?php if ($sf_user->hasFlash('notice')): ?>
    <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
    <?php endif; ?>

    <?php if ($sf_user->hasFlash('error')): ?>
      <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
    <?php endif; ?>

    <?php echo $sf_content ?>
    </div>
    <div id="user_infos">
      <?php if($sf_user->isAuthenticated()): ?>
        <?php $usr = Doctrine::getTable('user')->find($sf_user->getProfileVar(sfConfig::get('app_user_id'))) ; ?>
        <?php echo $usr->getMail() ?>
        <?php echo link_to('<img src="/images/shutdown.png" alt="Déconnexion" />', 'authentication/logout') ?>
      <?php endif ; ?>
    </div>
    <?php include_javascripts() ?>
  </body>
</html>
