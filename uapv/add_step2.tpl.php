      <hr/>
      <h2 class="step2"><?php tr('Date selection'); ?></h2>
      <form name="form_step2" method="post" action="add_step2.php">
        <p><?php tr('Date selection hint'); ?></p>
        <hr />
        <div class="calendar">
          <?php include('calendar.tpl.php'); ?>
        </div>
        <div class="chosen-dates">
          <h3 class="dates-selected"><?php tr('Dates selected') ?></h3>
<?php
  if ($tpl_data['no_date_error'])
  {
?>
          <p class="error"><?php tr('Please choose at least one date.'); ?></p>
<?php
  }
  if (count($tpl_data['dates_selected'])==0)
  {
?>
          <p><em><?php tr('No date selected.'); ?></em></p>
<?php
  }
  elseif (count($tpl_data['dates_selected']) > 0)
  {
?>
          <table>
            <tr>
              <th><?php tr('Date'); ?></th>
              <th><?php tr('Comment');?></th>
            </tr>
<?php
    foreach ($tpl_data['dates_selected'] as $key => $value)
    {
      $del_url = "add_step2.php?del_date=".$key."&amp;m=".$tpl_data['selected_date']['month']."&amp;a=".$tpl_data['selected_date']['year'];
      $onclick= "document.form_step2.action='".$del_url."'; document.form_step2.submit(); return false;";
?>
            <tr>
              <td>
                <a href="<?php echo $del_url; ?>" onclick="<?php echo $onclick; ?>">
                  <img src="images/del.gif" alt="Supprimer" border="0" style="vertical-align: middle;" />
                </a>
                <?php echo $value['date']; ?>
              </td>
              <td>
                <input type="text" class="text" name="comment[<?php echo $key; ?>]" value="<?php echo $value['comment']; ?>"/>
              </td>
            </tr>
<?php
    }
?>
          </table>
          <p class="help"><?php tr('Comment information'); ?></p>
<?php
  }
?>
        </div>
        <div class="buttons">
          <input type="submit" name="savenow" class="next" value="<?php tr('Next'); ?>"/>
          <input type="submit" name="cancel" class="cancel" value="<?php tr('Cancel'); ?>"/>
        </div>
        <input type="hidden" name="mid" value="<?php echo $tpl_data['mid']; ?>"/>
      </form>
