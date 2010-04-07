<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('meeting/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>> 
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table id="new_meet">
    <tfoot>
      <tr>
        <td><center><strong><?php echo __('Dates à retenir') ?> </strong><br />(<em><?php echo __('cliquez sur les dates voulues') ?></em>) <strong>:</strong></center></td>
        <td><div id="datee"></div></td>
      </tr>
      <tr>
        <td colspan="1">
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to(__('Effacer'), 'meeting/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => __('Voulez-vous vraiment supprimer ce rendez-vous?'))) ?>
          <?php endif; ?>
          
          <input type="submit" class="awesome blue large" value="<?php echo $sf_user->getAttribute('new') ? __('Créer') : __('Modifier') ?> <?php echo __('le rendez-vous') ?>" />
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form['_csrf_token']->render() ?>
      <?php //foreach($form as $widget): ?>
        <?php //if ($widget->getName() == '_csrf_token') continue ; ?>
<!--        <tr><th><?php // echo $widget->renderLabel() ?></th><td><?php // echo $widget->renderError() ?> <?php // echo $widget->render() ?> 
        <?php // if ($widget->getName() == 'input_date_1'): ?>
          Commentaire <span class="mini_help">(optionnel)</span> : <input type="text" name="meeting[input_date_1_comment]" id="meeting_input_date_1_comment" />
        <?php // endif ; ?>
        </td></tr> -->
      <?php // endforeach; ?>
      <?php echo $form ?>
    </tbody>
  </table>
<!--  <span id="mail_help">Entrez ici les adresses mail des personnes que vous voulez avertir, un mail automatique leur sera envoyé avec un lien vers votre sondage.</span> -->
<!--  <div id="dates_container"></div> -->
</form>
<script type="text/javascript">
  $('.dynamic_mail').live('blur',function()
  {
//    var loader = " <img src='/images/ajax-loader.gif' alt='loader' />" ;
    var not_mail = " <span class='mail_error'><img src='/images/invalid.png' alt='invalid' class='mail_icon' /> <?php echo __('Ce mail ne semble pas valide') ?>.</span>" ;
    var known_mail = " <span class='mail_ok'><img src='/images/valid.png' alt='valid' class='mail_icon' /></span>" ;
//    var unknown_mail = " <span class='mail_unk'><img src='/images/unk.png' alt='unknown' class='mail_icon' /> Ce mail est valide mais n'est pas répertorié dans la base de données.</span>" ;
    var mail_regex = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
    if (mail_regex.test(this.value) == false)
    {
      // c'pas un vrai mail !
      if ($(this).nextAll('span').length == 1)
        $(this).nextAll('span').remove() ;

      $(this).parent().append(not_mail) ;
    }
    else 
    {
      if ($(this).nextAll('span').length == 1)
        $(this).nextAll('span').remove() ;

      $(this).parent().append(known_mail) ; 
    }
  });
</script>
