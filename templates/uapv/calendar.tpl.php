<?php
  $post_url_back = "add_step2.php?m=".$tpl_data['one_month_before']['month']."&amp;a=".$tpl_data['one_month_before']['year'];
  $onclick_back = "document.form_step2.action='".$post_url_back."'; document.form_step2.submit(); return false;";
  $post_url_fwd = "add_step2.php?m=".$tpl_data['one_month_after']['month']."&amp;a=".$tpl_data['one_month_after']['year'];
  $onclick_fwd = "document.form_step2.action='".$post_url_fwd."'; document.form_step2.submit(); return false;";
?>
      <p>
        <a href="<?php echo $post_url_back; ?>" onclick="<?php echo $onclick_back; ?>">&lt;&lt;</a>
        <?php tr($tpl_data['selected_date']['month_lib']); ?> <?php echo $tpl_data['selected_date']['year']; ?>
        <a href="<?php echo $post_url_fwd; ?>" onclick="<?php echo $onclick_fwd; ?>">&gt;&gt;</a>
      </p>

      <table>
        <tr>
          <th><?php tr('Mon'); ?></th>
          <th><?php tr('Tue'); ?></th>
          <th><?php tr('Wed'); ?></th>
          <th><?php tr('Thu'); ?></th>
          <th><?php tr('Fri'); ?></th>
          <th><?php tr('Sat'); ?></th>
          <th><?php tr('Sun'); ?></th>
<?php
  //afficher le calendrier
  
  for ($i = 1; $i <= 42; $i++)
  {
    if ($i % 7 == 1)
    {
      echo '</tr><tr>';
    }
    if (($i < (cal_days_in_month(CAL_GREGORIAN, $tpl_data['selected_date']['month'], $tpl_data['selected_date']['year']) + $tpl_data['selected_date']['first_day']))
      && ($i >= $tpl_data['selected_date']['first_day']))
    {
      $day = $i - $tpl_data['selected_date']['first_day'] + 1;
      if ($tpl_data['selected_date']['month'] == $tpl_data['current_date']['month'] &&
        $tpl_data['selected_date']['year'] == $tpl_data['current_date']['year'] &&
        $day < $tpl_data['current_date']['day'] )
      {
        echo '<td class="unavailable">'.$day.'</td>';
      }
      else
      {
        if ($tpl_data['selected_date']['year'] < $tpl_data['current_date']['year'] ||
          ($tpl_data['selected_date']['year'] ==  $tpl_data['current_date']['year'] &&
            $tpl_data['selected_date']['month'] < $tpl_data['current_date']['month']))
        {
          echo '<td class="unavailable">'.$day.'</td>';
        }
        else
        {
          $post_url = "add_step2.php?date=" . str_pad($day, 2,'0', STR_PAD_LEFT) . "-" . str_pad($tpl_data['selected_date']['month'], 2,'0', STR_PAD_LEFT) . "-" . $tpl_data['selected_date']['year'] . "&amp;m=" . $tpl_data['selected_date']['month'] . "&amp;a=" . $tpl_data['selected_date']['year'];
          $onclick = "document.form_step2.action='".$post_url."'; document.form_step2.submit(); return false;";
?>
            <td>
              <a href="<?php echo $post_url; ?>" class="linkdate" onclick="<?php echo $onclick; ?>">
                <?php echo $i - $tpl_data['selected_date']['first_day'] + 1; ?>
              </a>
            </td>
<?php
        }
      }
    }
    else
    {
      echo '<td class="nonexistent">&nbsp;</td>';
    }
  }
?>
        </tr>
      </table>
