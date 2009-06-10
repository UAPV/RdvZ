      <form id="form1" name="form1" method="post" action="add_step1.php?mid=<?php echo $tpl_data['mid']; ?>">
        <hr/>
        <h2 class="step1"><?php tr('Informations'); ?></h2>
        <hr/>
<?php
  if ($tpl_data['mid']!='')
  {
?>
        <p class="warning"><?php tr('Edit warning'); ?></p>
<?php
  }
  if ($tpl_data['show_title_error'])
  {
?>
        <p class="error"><?php tr('You must choose a title for the meeting.'); ?></p>
<?php
  }
?>
        <p><br />
          <?php tr('Title'); ?><br />
          <input name="meeting_title" type="text" class="text" size="50" value="<?php echo $tpl_data['meeting_title']; ?>"/>
        </p>
        <p><?php tr('Description'); ?><br />
          <textarea name="meeting_description" cols="50" rows="6"><?php echo $tpl_data['meeting_description']; ?></textarea>
          <br />
        </p>
        <div class="buttons">
          <input type="submit" name="submit" class="next" value="<?php tr('Next'); ?>"/>
          <input type="submit" name="cancel" class="cancel" value="<?php tr('Cancel'); ?>"/>
        </div>
      </form>
