      <hr/>
      <h2><?php tr('My Meetings'); ?></h2>
      <hr/>
      <p>
        <span class="add-link"><a href="add_step1.php" class="biglink"><?php tr('New Meeting'); ?></a></span>
      </p>
      <table class="meeting-table">
        <tbody>
          <tr>
            <th><?php tr('Code'); ?></th>
            <th><?php tr('Title'); ?></th>
            <th><?php tr('Action'); ?></th>
          </tr>
<?php
  if (count($tpl_data['rdv'])==0)
  {
?>
          <tr><td colspan="3"><em><?php tr('No Meeting'); ?></em></td></tr>
<?php
  }
  else
  {
  foreach($tpl_data['rdv'] as $rdv)
  {
$close_state=$rdv['close_state'];

?>
          <tr>
            <td class="column-code"><?php echo $rdv['code']; ?></td>
            <td class="column-title">
              <b><a href="participation.php?mid=<?php echo $rdv['code']; ?>"><?php echo htmlspecialchars($rdv['title']); ?></a></b><br/>
              <?php
              switch($close_state)
              {
              	case 'reopen_allowed':
              	tr('You can reopen until'); echo $rdv['date_end']."<br/>";
              	break;
              	
              	case 'close_allowed':
              	tr('Automatic closure'); echo $rdv['date_end']."<br/>";
              	break;
              	
              	case 'none_allowed':
              	tr('Poll not available anymore');
              	
              	break;
              }
              
              
              
              ?>
              
              
              
              <?php tr('Automatic deletion'); echo $rdv['date_del'];?><br/>
            </td>
            <td class="column-action">
              <span class="edit-link"><a href="add_step1.php?mid=<?php echo $rdv['code']; ?>"><?php tr('Edit') ?></a></span>
              <span class="export-link"><a href="export.php?mid=<?php echo $rdv['code']; ?> "><?php tr('Export') ?> </a></span>
              <span class="delete-link"><a href="del.php?mid=<?php echo $rdv['code']; ?>" onclick="return confirm('<?php echo addslashes(tr('Do you really want to delete this meeting?', array(), true)); ?>');"><?php tr('Delete'); ?></a></span>
              
              <?php 
            
              
             
              switch($close_state)
              {
              case 'reopen_allowed':?>
              <span class="reopen-link"><a href="reopen.php?mid=<?php echo $rdv['code']; ?> "><?php tr('Reopen') ?> </a></span>
              <?php 
              break;
              
              case 'close_allowed': ?>
              <span class="close-link"><a href="close.php?mid=<?php echo $rdv['code']; ?> "><?php tr('Close') ?> </a></span>
              <?php 
              break;
              }
              ?>           
                          
              
            </td>
          </tr>
<?php
  }
  }
?>
        </tbody>
      </table>
