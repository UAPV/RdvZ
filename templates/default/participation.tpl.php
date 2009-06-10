<script LANGUAGE="JavaScript">
<!-- Begin
var checkflag = new Array();
checkflag["check_ok"]="false";
checkflag["check_ok_if_needed"]="false";


function check(field,type) {

if (type=="check_ok")
{
	
	if (checkflag["check_ok"]=="false")
	{
		for (i = 0; i < field.length; i++){
  			if (field[i].id==type) field[i].checked = true;
  			}
  		if (checkflag["check_ok_if_needed"]=="true") document.getElementById('button_check_ok_if_needed').innerHTML="Tout cocher";	
  	  	checkflag["check_ok"]="true";
  	  	checkflag["check_ok_if_needed"]="false";
  	  	return "Tout décocher";
	}
	else
	{
		for (i = 0; i < field.length; i++){
  			if (field[i].id==type) field[i].checked = false;
  			}
  			checkflag["check_ok"]="false";	
  			
  			return "Tout cocher";
	}	
}
else if (type=="check_ok_if_needed")
{

	if (checkflag["check_ok_if_needed"]=="false")
	{
		for (i = 0; i < field.length; i++){
  			
  			if (field[i].id==type) field[i].checked = true;
  			}
  		if (checkflag["check_ok"]=="true") document.getElementById('button_check_ok').innerHTML="Tout cocher";	
  	  	checkflag["check_ok_if_needed"]="true";	
  	  	checkflag["check_ok"]="false";
  	  	
		return "Tout décocher";
	}else
	{
		for (i = 0; i < field.length; i++){
  			if (field[i].id==type) field[i].checked = false;
  			}
  			checkflag["check_ok_if_needed"]="false";
  			
  				
  			return "Tout cocher";
	}	
	
	
}



}
//  End -->
</script>


<h2 class="step2"><?php tr('What are your preferences'); ?></h2>
<hr/>
<p class="pollintro">
        <?php echo tr('Title:',1).' '.htmlspecialchars($tpl_data['title']); ?><br />
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
        <td></td>
          
			<?php

  		foreach ($tpl_data['possible_months'] as $possible_month) //Affichage mois
	  		{?>
	          <th class="month" colspan="<?php echo $possible_month['iterations']; ?>"><?php echo $possible_month['month']; ?></th>
		<?php }?>
        </tr>
        
        
        <tr>
        <td></td> 
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
   	  <td></td>
			<?php
			  foreach ($tpl_data['possibilities'] as $possibility) //Affichage commentaires
			  {	?>
			          <th class="possibility"><?php echo htmlspecialchars($possibility['comment']); ?></th>
			<?php }	?>
        </tr>
<?php
if (count($tpl_data['votes'])>0)
{
  
  
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
}
?>
  
 <!-- Affichage des cases pour vote -->
<tr class="voteboxes">
<?php 
  if ($display=='full')
  { ?>
  	
  	<?php if (empty($tpl_data['current_username'])){?> 
  		<td rowspan="2"><input maxlength="64" size="16" value="<?php tr('Your name'); ?>" name="participantName"/></td>  <?php }
		  else  {?><th rowspan="2"><?php echo $tpl_data['current_username'];?> </th> <?php } ?>
			  
		<?php reset($tpl_data['possibilities']);?>
		 
		 <?php
	  	foreach ($tpl_data['possibilities'] as $possibility)
	  	{	?>   	<td class="check_ok"><input type="radio" id="check_ok" name="<?php echo htmlspecialchars($possibility['pollid']); ?>" value="1"/></td> <?php } ?>
	  	<td class="checkall"><a href=# id="button_check_ok" onClick="this.innerHTML=check(document.forms['form_poll'],'check_ok');"> Tout cocher</a></td>
</tr>
	 
	 
	  	<tr class="voteboxes">
	  	
	  	<?php 
	  	
	  	if ($tpl_data['aifna']=='Y')
	  	{
	  	foreach ($tpl_data['possibilities'] as $possibility)
	  	{	?> 
	  		<td class="check_ok_if_needed"><input type="radio" id="check_ok_if_needed" name="<?php echo htmlspecialchars($possibility['pollid']); ?>" value="2"/></td> <?php } ?>
		    <td class="checkall"><a href=# id="button_check_ok_if_needed" onClick="this.innerHTML=check(document.forms['form_poll'],'check_ok_if_needed');">Tout cocher</a></td>
		</tr>
		
	  <?php 
  }
  }
?> 
        </tr>
        
        
        <tr class="totals">
          <th><?php tr('Results:'); ?></th>
<?php
  
  //Affichage du nombre de votes "disponible"
  reset($tpl_data['possibilities_ok']); 
  foreach ($tpl_data['possibilities_ok'] as $key=>$possibility)
  {
?>
          <td class="result_ok"><?php echo $possibility['total']; ?></td>
          
<?php
  }
  ?>
        </tr>
         <tr class="totals">
          <th></th>
<?php

//Affichage du nombre de votes "disponible si besoin"
  /* reset($tpl_data['possibilities_ok_if_needed']); 
  foreach ($tpl_data['possibilities_ok_if_needed'] as $key=>$possibility)
  {
?>
          <td class="result_ok_if_needed"><?php echo $possibility['total']; ?></td>
          
<?php
  }*/
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
     <?php if ($display=='full')
     {
     	?>
     
      <div class="buttons"><input type="submit" class="save" name="isSubmit" value="<?php tr('Participate'); ?>"/>
      </div>
      <?php } ?>
      </form>
