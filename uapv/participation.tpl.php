<script LANGUAGE="JavaScript">
<!-- Begin
var checkflag = new Array();
checkflag["check_ok"]="false";
checkflag["check_ok_if_needed"]="false";
checkflag["check_not_ok"]="false";

function check(field,type) {

if (type=="check_ok")
{
	
	if (checkflag["check_ok"]=="false")
	{
		for (i = 0; i < field.length; i++){
  			if (field[i].id==type) field[i].checked = true;
  			}
   		if (checkflag["check_ok_if_needed"]=="true") document.getElementById('button_check_ok_if_needed').innerHTML="<?php tr('check all');?>";	
   		if (checkflag["check_not_ok"]=="true") document.getElementById('button_check_not_ok').innerHTML="<?php tr('check all');?>";	
  	  	checkflag["check_ok"]="true";
  	  	checkflag["check_ok_if_needed"]="false";
  	  	checkflag["check_not_ok"]="false";
  	  	return "<?php tr('uncheck all');?>";
	}
	else
	{
		for (i = 0; i < field.length; i++){
  			if (field[i].id==type) field[i].checked = false;
  			}
  			checkflag["check_ok"]="false";	
  			
  			return "<?php tr('check all');?>";
	}	
}
else if (type=="check_ok_if_needed")
{

	if (checkflag["check_ok_if_needed"]=="false")
	{
		for (i = 0; i < field.length; i++){
  			
  			if (field[i].id==type) field[i].checked = true;
  			}
  		if (checkflag["check_ok"]=="true") document.getElementById('button_check_ok').innerHTML="<?php tr('check all');?>";	
  		if (checkflag["check_not_ok"]=="true") document.getElementById('button_check_not_ok').innerHTML="<?php tr('check all');?>";	
  	  	checkflag["check_ok_if_needed"]="true";	
  	  	checkflag["check_ok"]="false";
  	  	checkflag["check_not_ok"]="false";
  	  	
		return "<?php tr('uncheck all');?>";
	}else
	{
		for (i = 0; i < field.length; i++){
  			if (field[i].id==type) field[i].checked = false;
  			}
  			checkflag["check_ok_if_needed"]="false";
  			
  				
  			return "<?php tr('check all'); ?>";
	}	
}
else if (type=="check_not_ok")
{

	
	if (checkflag["check_not_ok"]=="false")
	{
		for (i = 0; i < field.length; i++){
  			
  			if (field[i].id==type) field[i].checked = true;
  			}
  		if (checkflag["check_ok"]=="true") document.getElementById('button_check_ok').innerHTML="<?php tr('check all');?>";	
  		if (checkflag["check_ok_if_needed"]=="true") document.getElementById('button_check_ok_if_needed').innerHTML="<?php tr('check all');?>";	
  	  	checkflag["check_ok_if_needed"]="false";	
  	  	checkflag["check_ok"]="false";
  	  	checkflag["check_not_ok"]="true";
  	  	
		return "<?php tr('uncheck all');?>";
	}else
	{
		for (i = 0; i < field.length; i++){
  			if (field[i].id==type) field[i].checked = false;
  			}
  			checkflag["check_not_ok"]="false";
  			
  				
  			return "<?php tr('check all'); ?>";
	}	
}


}

//  End -->
</script>

<script>
var oldcomment="<img src=\"images/comment.png\">";
var first=true;

function show_dialog(elt)
{
	document.getElementById("divcomm"+elt.id).style.display="block";
	elt.innerHTML="";
	if (document.getElementById("comm"+elt.id).value!="<img src=\"images/comment.png\">") oldcomment=document.getElementById("comm"+elt.id).value;
	document.getElementById("comm"+elt.id).value=oldcomment
	
}

function erase_dialog(elt)
{
document.getElementById("comm"+elt.name).value="";
}


function cancel_dialog(elt)
{
	document.getElementById("comm"+elt.name).value=oldcomment;
	hide_dialog(elt);
	if (oldcomment!="") document.getElementById(elt.name).innerHTML=oldcomment
	else document.getElementById(elt.name).innerHTML="<img src=\"images/comment.png\">";
	oldcomment="<img src=\"images/comment.png\">";
		
}

function hide_dialog(elt)
{
	
	document.getElementById("divcomm"+elt.name).style.display="none";
	document.getElementById(elt.name).innerHTML="<img src=\"images/comment.png\">";
}

function ok_dialog(elt)
{
	hide_dialog(elt);
    if (document.getElementById("comm"+elt.name).value!="")
	{
	document.getElementById(elt.name).innerHTML=document.getElementById("comm"+elt.name).value;}
	else document.getElementById(elt.name).innerHTML="<img src=\"images/comment.png\">";
	
}

function get_cell_array(row,classe)
{
cell_array=new Array();
//alert(classe+"---"+row.childNodes.length);
for (var c=0 ; c<row.childNodes.length ; c++)
{
	//alert("c="+c+" Classe : "+row.childNodes[c].className+"; nom : "+row.childNodes[c].name);
	if (row.childNodes[c].className==classe) cell_array.push(row.childNodes[c]);
}
return cell_array;

}


function get_user_comments(user)
{
//Verif si utilisateur en cours existe dans le tableau
var found=false;
i=0;

while ((!found)&&(i<js_users_votes_array.length))
{
	
	if(js_users_votes_array[i]['name']==user)
	{
		found=true;
		for(var j=0 ; j<js_users_votes_array[i]['comments'].length ; j++)
		{
			eltCell=document.getElementById('comm_cell'+j);
			eltCellTable=eltCell.getElementsByTagName('input');
			
				//alert(eltCell+"--"+eltCellTable[0]+"--"+js_users_votes_array[i]['comments'][j]);
				
				eltCellTable[0].value=js_users_votes_array[i]['comments'][j];
				nextelt=eltCell.nextSibling;
				while (nextelt.nodeType!=1)	{nextelt=nextelt.nextSibling;}
				//alert(nextelt);
				ok_dialog(nextelt);
		}
	}
	i++;
}
if (found==false)
	{
		
		for(var j=0 ; j<js_users_votes_array[0]['comments'].length ; j++)
		{
			eltCell=document.getElementById('comm_cell'+j);
			eltCellTable=eltCell.getElementsByTagName('input');
			
				//alert(eltCell+"--"+eltCellTable[0]+"--"+js_users_votes_array[i]['comments'][j]);
								
				nextelt=eltCell.nextSibling;
				while (nextelt.nodeType!=1)	{nextelt=nextelt.nextSibling;}
				erase_dialog(nextelt);
				hide_dialog(nextelt);
		}
		
			
			
			 
	}
	
}



function get_user_votes(user)
{
	row_ok_cells=get_cell_array(document.getElementById('row_ok'),"check_ok");
	row_nok_cells=get_cell_array(document.getElementById('row_nok'),"check_not_ok");
	
	if (document.getElementById('row_ok_if_needed')!=null)
	{row_ok_if_needed_cells=get_cell_array(document.getElementById('row_ok_if_needed'),"check_ok_if_needed");}
	
	
	c=0;
	i=0;
	var found=false;
	
	while ((!found)&&(i<js_users_votes_array.length))
	{
		
		if(js_users_votes_array[i]['name']==user)
		{
		found=true;
		
		for(var j=0 ; j<js_users_votes_array[i]['vote'].length ; j++)
		{
			
			switch (js_users_votes_array[i]['vote'][j])
			{
			case "0":
				row_nok_cells[c].childNodes[0].checked=true;
				c=c+1;
			break;
			case "1":
				row_ok_cells[c].childNodes[0].checked=true;
				c=c+1;				
			break;
			case "2":
				row_ok_if_needed_cells[c].childNodes[0].checked=true;
				c=c+1;
			break;
			}	
			
		}
		}
		i++;
		
	}

	if (found==false)
	{
		
		for (r=0 ; r<row_ok_cells.length ; r++)
		{
			
			
			row_ok_cells[r].childNodes[0].checked=false;
			row_nok_cells[r].childNodes[0].checked=false;
			if (typeof(row_ok_if_needed)!="undefined") row_ok_if_needed_cells[r].childNodes[0]=false; 
		}
	}
}



function require_auth()
{
user_name=document.getElementById('participantName').value;
while (user_name=="")
	{
	  user_name=prompt("Entrez votre nom","");
      if (user_name!=null) document.getElementById('participantName').value=user_name;
	}
get_user_comments(user_name);
get_user_votes(user_name);
}



		


</script>


<form action="participation.php?mid=<?php echo $tpl_data['mid']; ?>" name="form_poll" method="post">

<h2 class="step2"><?php tr('What are your preferences'); ?></h2>
<hr/>
<?php if ($display=='full')
     {
     	?>
     
      <!-- <div class="buttons"> -->
        <p align="right">
        <input type="submit" class="save" name="isSubmit" value="<?php tr('Participate'); ?>"/>
        </p>
      <!-- </div> -->
     
      <?php } ?>







<p class="pollintro">
<h2 class="polltitle">
        <?php echo htmlspecialchars($tpl_data['title']); ?><br />
</h2>
<?php  if (trim($tpl_data['description'])!='')
  {
?>
        <em><?php echo nl2br(htmlspecialchars($tpl_data['description'])); ?></em>
<?php
  }
  else {?>   <em><?php tr('No description');} ?></em>
<p><?php
      if ($display=='full')
      {
      	 if ($tpl_data['current_username']=='') tr('Poll tip', array($tpl_data['cas_login_url'])); else tr('Authentified poll tip'); ?></p><?php
      } 
      elseif ($display=='readonly') tr('Readonly poll tip');

     
if ($tpl_data['no_participant_name']){?> <p class="error"><?php tr('Please enter your name.'); }?></p>
      
      
<!--  <form action="participation.php?mid=<?php echo $tpl_data['mid']; ?>" name="form_poll" method="post"> -->


     
      <table class="poll-table" id="poll-table">
        <tr>
        <td></td>
          
			<?php

  		foreach ($tpl_data['possible_months'] as $possible_month) //Affichage mois
	  		{?>
	          <th class="month" colspan="<?php echo $possible_month['iterations']; ?>"><?php echo $possible_month['month']; ?></th>
		<?php }?>
        </tr>
        
        
        <tr id="row-dates">
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
    $current_user_comment_array=Array();
    if ($voter['authentified'])
    {  
    	$class = 'authentified';
    	if ($voter['name']==$tpl_data['current_username']) $current_user_comment_array=$voter['user_comment'];
    	
    	
    }
      
    
?>
        <tr class="vote">
          <th class="<?php echo $class; ?>"><?php echo htmlspecialchars($voter['name']);?> </th>
<?php
    
    foreach ($voter['votes'] as $key=>$vote) //Affichage votes existants 
    {
      
	  if ($voter['user_comment'][$key]!="") $user_comment=stripslashes($voter['user_comment'][$key]);
      switch($vote)
      {
      	case 0:
      	?><td class="not-ok"> <?php echo $user_comment; ?></td><?php
      	break;
      	
      	case 1:
      	?><td class="ok"><?php echo $user_comment; ?></td><?php
      	break;
      	
      	case 2:
      	?><td class="ok-if-needed"><?php echo $user_comment; ?></td><?php
      	break;
        }

    $user_comment="";    
        
        
        }
   
?>
        </tr>
        
       
        
<?php
  }
  
  
  
  
}
?>
  
 <!-- Affichage des cases pour vote -->
<tr class="voteboxes" id="row_ok">
<?php 
  if ($display=='full')
  { ?>
  	
  	<!-- Affichage nom votant -->
  	<?php
  	if ($tpl_data['aifna']=='Y') $field_height=4;
  	else $field_height=3;
  $auth=false;
  
  	if (empty($tpl_data['current_username'])){?> <td rowspan="<?php echo $field_height; ?>"><input maxlength="64" size="16" value=""  name="participantName" id="participantName" onKeyup="get_user_comments(this.value);get_user_votes(this.value);"/></td>  <?php }
		  else  {?><th rowspan="<?php echo $field_height; ?>"><?php echo $tpl_data['current_username']; $auth=true?> </th> <?php } ?>
			  
		<?php reset($tpl_data['possibilities']);?>
		 
		 
		 <!-- Cases vote "disponible" -->
		 <?php
	  	foreach ($tpl_data['possibilities'] as $possibility)
	  	{	
	  	?>   	
	  	<td class="check_ok"><input type="radio" id="check_ok" name="<?php echo htmlspecialchars($possibility['pollid']); ?>" value="1"/></td> 
	  	<?php } ?>
	  	<td class="checkall"><a href=# id="button_check_ok" onClick="this.innerHTML=check(document.forms['form_poll'],'check_ok');"> <?php tr('check all');?></a></td>
		</tr>
	 
	 
	  	
	  	
	  	
	  	<!--  Cases vote "disponible si besoin"  -->
	  	<?php 
	  	if ($tpl_data['aifna']=='Y')
	  	{?>
	  	<tr class="voteboxes" id="row_ok_if_needed">
	  	<?php
	  	foreach ($tpl_data['possibilities'] as $possibility)
	  	{	?> 
		  		<td class="check_ok_if_needed"><input type="radio" id="check_ok_if_needed" name="<?php echo htmlspecialchars($possibility['pollid']); ?>" value="2"/></td> 
	  <?php } ?>
			    <td class="checkall"><a href=# id="button_check_ok_if_needed" onClick="this.innerHTML=check(document.forms['form_poll'],'check_ok_if_needed');"><?php tr('check all');?></a></td>
			</tr>
			<?php 
	  	}
  		
  		?>
  		
  		<!--  Cases vote "Indisponible" -->
  		<tr class="voteboxes" id="row_nok">
	  	<?php
	  	foreach ($tpl_data['possibilities'] as $possibility)
	  	{	?> 
		  		<td class="check_not_ok"><input type="radio" id="check_not_ok" name="<?php echo htmlspecialchars($possibility['pollid']); ?>"  checked=true value="0"/></td> 
	  <?php } ?>
			    <td class="checkall"><a href=# id="button_check_not_ok" onClick="this.innerHTML=check(document.forms['form_poll'],'check_not_ok');"><?php tr('check all');?></a></td>
			</tr>
			<?php 
  		
  		
  		?>
  		
  		
  		<!-- Cases commentaire si autorisés -->
  		
  		<?php if ($tpl_data['availableDateComments']=='Y')
  		{
  			?>
  		
  		<tr id="row_comments">
  		
  		<?php  foreach ($tpl_data['possibilities'] as $key=>$possibility) 
  		{
  		if (count($current_user_comment_array)>0)  $current_user_comment=$current_user_comment_array[$key];
  		else  $current_user_comment="";
  		?>
  	
  		<td class="cell_comment">
  		
  		<div id="divcomm<?php echo $possibility['pollid'];?>" class="comment" name="<?php echo $possibility['pollid'];?>">
			
			<span id="comm_cell<?php echo $key; ?>">
			<input type="textarea" name="comment<?php echo $possibility['pollid'];?>" id="comm<?php echo $possibility['pollid'];?>" value="<?php echo $current_user_comment; ?>"/>
			</span>
			<img src="images/OK.png" width="16" height="16" title="OK" id="buttok"  class="ok" name="<?php echo $possibility['pollid'];?>" value="OK" onclick="ok_dialog(this)";>  
			 <!--  <button title="OK" id="buttok" name="<?php echo $possibility['pollid'];?>" value="OK" onclick="ok_dialog(this)";>-->
			  <!-- <button title="OK" id="buttok" name="<?php echo $possibility['pollid'];?>" value="OK"> -->
			<img src ="images/arrow_undo.png" width="16" height="16" title="Annuler" id="buttcancel" name="<?php echo $possibility['pollid'];?>" value="Annuler" onclick="cancel_dialog(this)";>
		    <img src="images/del.gif" width="16" height="16" title="Effacer" name="<?php echo $possibility['pollid'];?>" value="Eff" onclick="erase_dialog(this)";>
		    
		    
		</div>
		<span id="text<?php echo $possibility['pollid'];?>"></span>
		<span title="commentaire" id="<?php echo $possibility['pollid'];?>"  onclick="show_dialog(this);"><img src="images/comment.png"></span> 
		</td>
		
		
		<?php
  		}
  		?>
  		
			
		
		
		
  		
<?php  		}  		?>
	</tr>	  		
<?php } ?>
  		
  		
  	
        
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
      
     
    
     
      
      
      
      <!-- Legende votes -->
        <table class="legend_poll">
      <tr> 
      	<td class="ok"></td>
      	<td> <?php tr('Available'); ?> </td>
      </tr>
      <?php if ($tpl_data['aifna']=='Y')
	  	{ ?>
      <tr>
      	<td class="ok_if_needed">
      	<td> <?php tr('Available if needed') ?> </td>
      </tr> 
      <?php }  ?>
       <tr> 
      	<td class="not_ok"></td>
      	<td> <?php tr('Not Available'); ?> </td>
      </tr>
      <?php if ($tpl_data['availableDateComments']=='Y')
      {?>
      <tr>
      	<td> <img src="images/comment.png"> </td>
      	<td> <i> <?php tr('Click for comment'); ?></i> </td>
      </table>
      <?php } ?>
      
   
      
 <?php if (($tpl_data['current_username']=="")&&(!(isset($_POST['isSubmit'])))) 
{?>
<script type="text/javascript">
var js_users_votes_array=new Array();
		
<?php 			
// Remplissage tableau javascript à partir du tableau php
if (isset($tpl_data['votes']))
{
foreach ($tpl_data['votes'] as $key=>$vote)
			{
				echo "js_users_votes_array[".$key."]=new Array();"; 	
				echo "js_users_votes_array[".$key."]['name']=\"".$vote['name']."\";";
				echo "js_users_votes_array[".$key."]['comments']=new Array();";
				echo "js_users_votes_array[".$key."]['vote']=new Array();";
				foreach($vote['votes'] as $u_vote)
				{
					echo "js_users_votes_array[".$key."]['vote'].push(\"".$u_vote."\");";
				}
				
				foreach($vote['user_comment'] as $u_comment)
				{
					echo "js_users_votes_array[".$key."]['comments'].push(\"".$u_comment."\");";
				} 
				
							
			}
}?>


require_auth();
</script>
<?php } ?>



<!--  Commentaires globaux si autorisé -->
<?php
    	   if ($tpl_data['availableComments']=='Y'){
    	    			?>
<hr/>
<h2 class="step2"> Commentaires </h2>

<div class="global_comments_poll">
  <table>
	    	    <tr>
    	    		<td style="width:400px;vertical-align:top" >
    	    		
    	    			<?php foreach ($tpl_data['commentaires'] as $commentaire){
    	    				printf("<div style='border:1px solid #dddddd;padding:5px;width:300px'><p style='background-color: #dddddd;border: 1px solid #aaaaaa;margin:0px;padding:2px'>%s%s</p>%s</div>",$commentaire['uid'],$commentaire['participant_name'],nl2br($commentaire['comment']));
    	    			}
    	    		}
    	    		?>
    	    		</td>
    	    		<td>
    	    		<?php
    	    			if ($tpl_data['availableComments']=='Y'){
    	    		?>
    	    			<form style="background-color: #dddddd;border: 1px solid #aaaaaa;margin:0px;padding:2px" action="participation.php?mid=<?php echo $tpl_data['mid']; ?>" name="form_commentaire" method="post">
				     	    <legend><?php tr('Write a comment'); ?></legend><br />
				     	 	<br><label for=participantName"><?php tr('Your name'); ?></label> : <br />
				     	 	<input type="text" name="participantName" id="participantName" <?php if (!empty($tpl_data['current_username'])) printf("value=\"%s\" readonly=\"readonly\"", $tpl_data['current_username']);?>/><br />
							<label for="comments"><?php tr('Comment'); ?></label> : <br />     	 	
				     	 	<textarea name="comments" id="comments" rows="6" cols="30"></textarea><br />
				      		<input type="submit" name="isSubmit" class="save" value="<?php tr('Add'); ?>" />
				      	</form>
				    <?php
    	    			}
    	    		?>
	   	    		</td>
    	    	</tr>
      	</table>
      	</div>
      	
      
      </form>


