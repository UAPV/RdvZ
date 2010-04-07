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
    <script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script> 
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
    <?php if($sf_user->isAuthenticated() && $sf_user->hasCredential('member')): ?>
      <div id="user_infos">
        <?php $usr = Doctrine::getTable('user')->find($sf_user->getProfileVar(sfConfig::get('app_user_id'))) ; ?>
        <?php echo $usr->getMail() ?>
        <?php echo mail_to('romain.deveaud@univ-avignon.fr', '<img src="/images/71.png" alt="Bug" /> '.__('Signaler un bogue')) ?>
        <?php $languages = sfConfig::get('app_languages') ; ?>
        <?php foreach($languages as $lang => $country): ?>
          <?php echo link_to('<img src="/images/'.$lang.'_icon.png" alt="'.$country.'"/>','/lang/'.$lang) ?>
        <?php endforeach ; ?>
        <?php echo link_to('<img src="/images/shutdown.png" alt="'.__('Déconnexion').'" title="'.__('Déconnexion').'" />', 'out') ?>
      </div>
    <?php endif ; ?>
    <?php include_javascripts() ?>
  </body>
</html>
