<script LANGUAGE="JavaScript">
<!-- Begin
var checkflag = new Array();
checkflag["check_ok"]="false";
checkflag["check_ok_if_needed"]="false";


function check(field,type) {

if (type=)



if (checkflag[type] =="false") {

 for (i = 0; i < field.length; i++){
  if (field[i].id==type)
  field[i].checked = true;
  }
  
for (key in checkflag) checkflag[key]="false";
checkflag[type] = "true";

if (type=="check_ok") document.getElementById('button_check_ok_if_needed').value="Tout cocher";
else if (type=="check_ok_if_needed") document.getElementById('button_check_ok').value="Tout cocher";
  
  return "Tout décocher"; }
else {
    
    for (i = 0; i < field.length; i++) {
     if (field[i].id==type)
  field[i].checked = false; 
  
 
  }
for (key in checkflag) checkflag[key]="true";
  checkflag[type] = "false";
  

  
  return "Tout cocher"; }
}
//  End -->
</script>


<h2 class="step2"><?php tr('What are your preferences'); ?></h2>
<hr/>
<p class="pollintro">
        <?php echo tr('Title:', array(), true).' '.htmlspecialchars($tpl_data['title']); ?><br />
<?php  if (trim($tpl_data['description'])!='')
  {
?>
        <em><?php echo nl2br(htmlspecialchars($tpl_data['description'])); ?></em>
<?php
  }
  else ?>   <em><?php tr('No description'); ?></em>
</p>
<p><?php
      if ($display=='full')
      {
      	 if ($tpl_data['current_username']=='') tr('Poll tip', array($tpl_data['cas_login_url'])); else tr('Authentified poll tip'); ?></p><?php
      } 
      elseif ($display=='readonly') tr('Readonly poll tip');

     
if ($tpl_data['no_participant_name']){?> <p class="error"><?php tr('Please enter your name.'); }?></p>
      
      
<form action="participation.php?mid=<?php echo $tpl_data['mid']; ?>" name="form_poll" method="post">
     
      <table class="poll-table">
        <tr>
          
			<?php

  		foreach ($tpl_data['possible_months'] as $possible_month) //Affichage mois
	  		{?>
	          <th class="month" colspan="<?php echo $possible_month['iterations']; ?>"><?php echo $possible_month['month']; ?></th>
		<?php }?>
        </tr>
        
        <tr>
         
			<?php
			  foreach ($tpl_data['possible_dates'] as $possible_date) //Affichage dates
			  {	?>
			          <th class="day" colspan="<?php echo $possible_date['iterations']; ?>">
			            <?php tr($possible_date['weekday']); ?><br/>
			            <?php echo $possible_date['day']; ?>
			          </th>
			<?php  }?>
        </tr>
        
   	<tr>
   	  
			<?php
			  foreach ($tpl_data['possibilities'] as $possibility) //Affichage commentaires
			  {	?>
			          <th class="possibility"><?php echo htmlspecialchars($possibility['comment']); ?></th>
			<?php }	?>
        </tr>
<?php
  foreach ($tpl_data['votes'] as $voter)
  {
    
    $class = '';
    if ($voter['authentified'])
      $class = 'authentified';
?>
        <tr class="vote">
          <th class="<?php echo $class; ?>"><?php echo htmlspecialchars($voter['name']);?> </th>
<?php
    foreach ($voter['votes'] as $vote) //Affichage votes existants 
    {
      switch($vote)
      {
      	case 0:
      	?><td class="not-ok"><span class="label"><?php tr('Not OK'); ?></span></td><?php
      	break;
      	
      	case 1:
      	?><td class="ok"><span class="label"><?php tr('Not OK'); ?></span></td><?php
      	break;
      	
      	case 2:
      	?><td class="ok-if-needed"><span class="label"><?php tr('Not OK'); ?></span></td><?php
      	break;
      	
      }
      
    
    }
?>
        </tr>
<?php
  }
?>
  
 <!-- Affichage des cases pour vote -->
<tr id="checkboxes" class="voteboxes">
<?php 
  if ($display=='full')
  { ?>
  	
  	<?php if (empty($tpl_data['current_username'])){?> <td rowspan="2"><input maxlength="64" size="16" value="<?php tr('Your name'); ?>" name="participantName"/></td>  <?php }
		  else  {?><th rowspan="2"><?php echo $tpl_data['current_username'];?> </th> <?php } ?>
			  
		<?php reset($tpl_data['possibilities']);?>
		 
		 <?php
	  	foreach ($tpl_data['possibilities'] as $possibility)
	  	{	?>   	<td class="check_ok"><input type="radio" id="check_ok" name="<?php echo htmlspecialchars($possibility['pollid']); ?>" value="1"/></td> <?php } ?>
	  	<td class="checkall"><input type="button" id="button_check_ok" onClick="this.value=check(document.forms['form_poll'],'check_ok');" value="Tout cocher"></td>
</tr>
	 
	 
	  	<tr class="voteboxes">
	  	
	  	<?php foreach ($tpl_data['possibilities'] as $possibility)
	  	{	?> 
	  		<td class="check_ok_if_needed"><input type="radio" id="check_ok_if_needed" name="<?php echo htmlspecialchars($possibility['pollid']); ?>" value="2"/></td> <?php } ?>
		    <td class="checkall"><input type="button" id="button_check_ok_if_needed" onClick="this.value=check(document.forms['form_poll'],'check_ok_if_needed');" value="Tout cocher"></td>
		</tr>
		
	  <?php 
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
      
      <table class="legend_poll">
      <tr> 
      	<td class="ok"></td>
      	<td> <?php tr('Available'); ?> </td>
      </tr>
      <tr>
      	<td class="ok_if_needed">
      	<td> <?php tr('Available if needed') ?> </td>
      </tr>
      </table>
     
      <div class="buttons">
        <input type="submit" class="save" name="isSubmit" value="<?php tr('Participate'); ?>"/>
      </div>
      </form>
