<script LANGUAGE="JavaScript">
<!-- Begin
var checkflag = "false";
function check(field) {
if (checkflag == "false") {
  for (i = 0; i < field.length; i++) {
  field[i].checked = true;}
  checkflag = "true";
  return "Tout décocher"; }
else {
  for (i = 0; i < field.length; i++) {
  field[i].checked = false; }
  checkflag = "false";
  return "Tout cocher"; }
}
//  End -->
</script>




<h2 class="step2"><?php tr('What are your preferences'); ?></h2>
<hr/>
      <p class="pollintro">
        <?php echo tr('Title:', array(), true).' '.htmlspecialchars($tpl_data['title']); ?><br />
<?php

  if (trim($tpl_data['description'])!='')
  {
?>
        <em><?php echo nl2br(htmlspecialchars($tpl_data['description'])); ?></em>
<?php
  }
  else
  {
?>
        <em><?php tr('No description'); ?></em>
<?php
  }
?>
</p>
<p><?php
      if ($display=='full')
      {
      	 if ($tpl_data['current_username']=='') tr('Poll tip', array($tpl_data['cas_login_url'])); else tr('Authentified poll tip'); ?></p><?php
      } 
      elseif ($display=='readonly') tr('Readonly poll tip');
      
  if ($tpl_data['no_participant_name'])
  {
?>
      <p class="error"><?php tr('Please enter your name.'); ?></p>
<?php
  }
?>
      <form action="participation.php?mid=<?php echo $tpl_data['mid']; ?>" method="post">
      <table class="poll-table">
        <tr>
          <td></td>
<?php

  foreach ($tpl_data['possible_months'] as $possible_month)
	  {
	?>
	          <th class="month" colspan="<?php echo $possible_month['iterations']; ?>"><?php echo $possible_month['month']; ?></th>
	<?php
	  }
?>
        </tr>
        <tr>
          <td></td>
<?php
  foreach ($tpl_data['possible_dates'] as $possible_date)
  {
?>
          <th class="day" colspan="<?php echo $possible_date['iterations']; ?>">
            <?php tr($possible_date['weekday']); ?><br/>
            <?php echo $possible_date['day']; ?>
          </th>
<?php
  }
?>
        </tr>
        <tr>
          <td></td>
<?php
  foreach ($tpl_data['possibilities'] as $possibility)
  {

?>
          <th class="possibility"><?php echo htmlspecialchars($possibility['comment']); ?></th>
<?php
  }
?>
        </tr>
<?php
  foreach ($tpl_data['votes'] as $voter)
  {
    
    $class = '';
    if ($voter['authentified'])
      $class = 'authentified';
?>
        <tr class="vote">
          <th class="<?php echo $class; ?>"><?php echo htmlspecialchars($voter['name']); ?></th>
<?php
    foreach ($voter['votes'] as $vote)
    {
      if ($vote)
      {
?>
          <td class="ok"><span class="label"><?php tr('OK'); ?></span></td>
<?
      }
      else
      {
?>
          <td class="not-ok"><span class="label"><?php tr('Not OK'); ?></span></td>
<?php
      }
    }
?>
        </tr>
<?php
  }
?>
        <tr class="voteboxes">
<?php

  
  if ($display=='full')
  {
  if (empty($tpl_data['current_username']))
  {
?>
          <td><input maxlength="64" size="16" value="<?php tr('Your name'); ?>" name="participantName"/></td>
          
<?php
  }
  
  else
  {?>
          <th><?php echo $tpl_data['current_username'];
  }
  } 
          
          
          ?>
     
        </th>
	
  
<?php
  
  reset($tpl_data['possibilities']);
  
  if ($display=='full')
	{
	  	foreach ($tpl_data['possibilities'] as $possibility)
	  {
		
	?>
	          <td><input type="checkbox" name="list" value="<?php echo htmlspecialchars($possibility['pollid']); ?>" value="1"/></td>
	<?php
	  }
	  ?><td><input type="checkbox" onClick="document.getElementById('global_legend').innerHTML=check(this.form.list)">
	  <br>
	  <span id="global_legend">Tout cocher</span>
	  </td><?php 
	  }
  
?> 
        </tr>
        <tr class="totals">
          <th><?php tr('Results:'); ?></th>
<?php
  reset($tpl_data['possibilities']);
  foreach ($tpl_data['possibilities'] as $possibility)
  {
?>
          <td><?php echo $possibility['total']; ?></td>
<?php
  }
  ?>
        </tr>
      </table>
      <div class="buttons">
        <input type="submit" name="isSubmit" class="save" value="<?php tr('Participate'); ?>"/>
      </div>
      </form>
