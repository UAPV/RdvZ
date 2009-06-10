
<!-- Initialisations options avancees-->



<!--Fin initialisations -->


<div id="basic">
      <form id="form1" name="form1" method="post" action="add_step1.php?mid=<?php echo $tpl_data['mid']; ?>">
        <hr/>
        <h2 class="step1"><?php tr('Informations');  ?></h2>
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
        <p>
          <?php tr('Title'); ?><br />
          <input name="meeting_title" type="text" class="text" size="50" value="<?php echo $tpl_data['meeting_title']; ?>"/>
        </p>
        <p><?php tr('Description'); ?><br />
          <textarea name="meeting_description" cols="50" rows="6"><?php echo $tpl_data['meeting_description']; ?></textarea>
          <br />
        </p>
   


<div id="button_advanced_options"></div>
<div class="buttons">
          <input type="submit" name="submit" class="next" id="next_button" value="<?php tr('Next'); ?>"/>
          <input type="submit" name="cancel" class="cancel" value="<?php tr('Cancel'); ?>"/>
  </div>    
 

</div>



	<!--Affichage options avancées-->
	<script type="text/javascript">
	
	function init() {
	module1 = new YAHOO.widget.Module("advanced", { visible: false });
	YAHOO.util.Event.addListener("hide1", "click", module1.hide, module1, true);
	YAHOO.util.Event.addListener("show1", "click", module1.show, module1, true);
	module1.render();
	}
	
	YAHOO.util.Event.addListener(window, "load", init);
	
	 var oButton = new YAHOO.widget.Button( {id:"button1", type:"checkbox",label:"Afficher les options",container:"button_advanced_options", checked:false});
	
	function obc()
	{
	
	if (oButton.get("checked"))
	{	
	oButton.set("label","Fermer les options");
	module1.show();
	}
	else
	{
	oButton.set("label","Afficher les options");
	module1.hide();
	}
	}
	oButton.addListener("checkedChange",obc);
	</script>
		
			<!--<div id="advanced" class="module">-->
			<div id="advanced">
			<p><br/>
			<!-- Activer/desactiver vote "Disponible en cas de besoin" -->
			<?php tr('Available if needed allowed');?><br/>
			<input type="radio" name="aifna" value="Y"><?php tr('Yes')?></input>
			<input type="radio" name="aifna" value="N" checked="true"><?php tr('No')?></input>
			</p>
			
			<p>
			<!--Activer/desactiver notification par e-mail -->
			<?php tr('Alert email');?><br/>
			<input type="radio" name="notif" value="Y"><?php tr('Yes')?></input>
			<input type="radio" name="notif" value="N" checked="true"><?php tr('No')?></input>
			</p>
			
			<!-- Ajout d'adresses e-mail à qui envoyer le sondage -->
			<p>
			
			<?php tr('Enter email');?><br/>
			</p>			
			<p>
			E-mail : <input type="text" id="field_mail" size="30" maxlength="300">
			<input type="button" id="submit_mail" value="Ajouter">
			<input type="hidden" id="maillist" name="maillist">
			</p>
			<p>
			<div id="emaillist">
			
			</div>
			</p>
			
<!-- Suppression par bouton droit -->
<div id="myContainer" class="contextmenu"></div>
	
			</table>

			</div>
		
	
	
	
	      
	</form> 
</div>
			
			
			
			<script type="text/javascript">
			YAHOO.util.Event.addListener(window, "load", function() {
				var Dom = YAHOO.util.Dom; 
			  var Dom = YAHOO.util.Dom; 
       	    var oSelectedTR;  
			//Creation du tableau contenant la liste
			var EmailTableColumnDefs = [{key:"email",label:"",editor:"textarea"}]; //Definition de la structure du tableau
			var initialData=[{email:""}]; //Données intiales
			var EmailTableInitialData=new YAHOO.util.DataSource(initialData); //Création de la source de données intiales
			EmailTableInitialData.responseType = YAHOO.util.DataSource.TYPE_JSON; 
			EmailTableInitialData.responseSchema = {fields: ["email"]};
			var EmailDataTable = new YAHOO.widget.DataTable("emaillist", EmailTableColumnDefs, EmailTableInitialData, {scrollable:true, height:"10em",width:"290px"});
			             
			
	        EmailDataTable.subscribe("cellClickEvent", EmailDataTable.onEventShowCellEditor); 
	        
			//Listener pour le bouton d'ajout
			 YAHOO.util.Event.addListener("submit_mail","click",function() {
			           emailToAdd={email:document.getElementById('field_mail').value};
			           EmailDataTable.addRow(emailToAdd);       
			        },this,true);
			        

		   //Suppression par bouton droit
		   onContextMenuClick = function(p_sType, p_aArgs, p_myDataTable) {
            var task = p_aArgs[1];
           
            if(task) {
            	
                // Extract which TR element triggered the context menu
                var elRow = this.contextEventTarget;
                elRow = p_myDataTable.getTrEl(elRow);
                if(elRow) {
                    switch(task.index) {
                        case 0:     // Delete row
                            var oRecord = p_myDataTable.getRecord(elRow);                          
                                p_myDataTable.deleteRow(elRow);
                                
                            
                    }
                }
            }
        };	        
        
        
        
	    var myContextMenu = new YAHOO.widget.ContextMenu("mycontextmenu",{trigger:EmailDataTable.getTbodyEl()});
	    myContextMenu.addItem("Effacer");
	    myContextMenu.render("myContainer");

        // Render the ContextMenu instance to the parent container of the DataTable       
        //myContextMenu.subscribe("beforeShow", onContextMenuBeforeShow);
        //myContextMenu.subscribe("hide", onContextMenuHide);       
        
        
        
        //myContextMenu.clickEvent.subscribe(Dom.addClass(EmailDataTable.getTbodyEl(),"selected")); 
        myContextMenu.clickEvent.subscribe(onContextMenuClick, EmailDataTable);
          
        //  EmailDataTable.subscribe("cellClickEvent", EmailDataTable.onEventHighlightCell); 
		//EmailDataTable.subscribe("cellMouseoverEvent", EmailDataTable.onEventHighlightCell); 
		//EmailDataTable.subscribe("cellMouseoutEvent", EmailDataTable.onEventUnhighlightCell);
		
		//Recup bouton "suivant" pour rajouter les mails
	   
	   function on_next_click()
	   {
	   	//Rajoute les adresses e-mail
	   	
	   	tab_emails=EmailDataTable.getRecordSet().getRecords(1);
	   	email_numbers=tab_emails.length;
	   
	   	for (i=1;i<=email_numbers;i++)
	   	{   		 		
	   		
	   		var email=EmailDataTable.getTrEl(i).childNodes[0].firstChild.innerHTML;
	   		
	   		
	   		//check e-mail !
	   		  		
	   		testm = false ; 
	   		for (var j=1 ; j<(email.length) ; j++) 
	   		{ if (email.charAt(j)=='@') { if (j<(email.length-4)) { for (var k=j ; k<(email.length-2) ; k++) { if (email.charAt(k)=='.') testm = true; } } } }
	   		
	   		if(testm==true)
	   		{
	   		 if (i==1) document.getElementById('maillist').value=document.getElementById('maillist').value+email;
	   		 else document.getElementById('maillist').value=document.getElementById('maillist').value+';'+email;
	   		} 	
	   	
	   	}
	   	
	   	//document.forms['form1'].submit();
	   		   
	   	}
	   	
	   var btn_next = new YAHOO.widget.Button({id:"next_button"});
	   btn_next.addListener("click", on_next_click);
	   
			 }			 	 
			 ,this,true);
			 
			</script>
			
			



