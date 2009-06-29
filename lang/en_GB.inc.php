<?php
/********************************************
Fonction: English language messages file.
Date: 2008, Novenber
Auteurs: Liang HONG, Min WANG
Université d'Avignon
http://gpl.univ-avignon.fr
********************************************/

/*define('PageTitle', 'Meeting');
 */

  $translations = array(

  	'Install Welcome'=>"Welcome into RDVZ installation process.",
  	'Please choose your authentication way'=>"Please choose the way users will authenticate.",
  	 'Please check installation rights'=>"In order to achieve installation, please check the following points:" .
  	 		"<ul>" .
  	 		"<li> \"includes\" directory must be writable. </li>" .
  	 		"<li> Correct privileges must be granted to your database user (create, update, insert)</li>" .
  	 		"</ul>",

  	 'DB'=>"Database",
  	 'Before processing'=>"Before proceding please ensure that :  ",
  	 'Directory writable'=>"The \"includes\" directory is writable.",
  	 'Rights OK'=>"You have requested permissions on your database.",
  	 'If OK you can complete'=>"If all right you can now fill following fields :",
  	 'Application database parameters'=>"Application database parameters",
  	 'Database host'=>"Database host :",
  	 'Database name'=>"Database name :",
  	 'User name'=>"User name : ",
  	 'Password'=> "Password : ",
  	 'Create DB ?'=>"Create the database ?",
  	 'Yes'=>"Yes",
  	 'No'=>"No",
  	 'Create table'=>"Create table",
     'Update from 1.1'=>"Update from 1.1 ?",
  	 'CAS parameters'=>"CAS parameters",
  	 'CAS server name'=>"CAS server name :",
  	 'LDAP parameters'=>"LDAP parameters",
  	 'LDAP servers'=>"LDAP server(s) :",
  	 'If several LDAP servers'=>"(Separated by semicolon if several)<br>",
  	 'User for read only'=>"User for read only:",
  	 'Password for read only'=>"Password for read only: ",
  	 'User branch root'=>"User branch root :",
  	 'Mail parameters'=>"Mail parameters",
  	 'e-mail'=>"Administrator e-mail",
  	 'smtp server'=>"smtp server: ",
  	 'smtp port'=>" smtp port :", 
  	 'Miscellaneous parameters'=>"Miscellaneous parameters",
  	 'Site name'=>"Site name: ",
  	 
  	 'Users database parameters'=>"Users database parameters",
  	 'Users table'=>"Users table: ",
  	 'Login field'=>"Login field in the user table :",
  	 'Password field'=> "Password field in the user table :",
  	 'email field'=>"e-mail field in the user table :",
  	 'Password encryption'=>"Password encryption : ",
  	 'Clear'=>"None",
  	 'Congratulations'=>"Congratulations",
  	 'RDVZ installed'=>"RDVZ is now ready for using !",
  	 'Click on the following link to use RDVZ'=>"Click on the following link to use RDVZ : ",
  	 'Default user was created'=>"A default user has been created. You can use it to connect to RDVZ : ",
  	 'Login'=>"Login",  	


    'Code' => "Code",
    'Title' => "Title",
    'Action' => "Action",
    'Edit' => "Edit",
    'Delete' => "Delete",
    'Cancel' => "Cancel",
    'Next' => "Next",
    'Finish' => "Finish",
    'Thanks' => "Thanks",
    'Comment' => "Comment",
    'Participate' => "Participate",
    'Export'=>"Export CSV format",
    'Close' =>"Close votes",
    'Reopen'=>"Reopen votes",
    'Automatic closure'=>"Polls automatically closed on  ",
    'Automatic deletion'=> "Survey automatically deleted on  ",
    'You can reopen until'=>"Votes are closed. You may reopen them until  ",
    'Poll not available anymore'=>"This survey doesn't exist anymore.",
    'Enter email'=>"Enter destination email adresses list for this survey ",
    'Several adresses'=> "(separated by semicolon)",
    'Alert email'=>"Do you want to be informed by email whenever someone votes ?",

    'Mon' => "Mon.",
    'Tue' => "Tue.",
    'Wed' => "Wed.",
    'Thu' => "Thu.",
    'Fri' => "Fri.",
    'Sat' => "Sat.",
    'Sun' => "Sun.",

    'January' => "January",
    'February' => "February",
    'March' => "March",
    'April' => "April",
    'May' => "May",
    'June' => "June",
    'July' => "July",
    'August' => "August",
    'September' => "September",
    'October' => "October",
    'November' => "November",
    'December' => "December",

    'New Meeting' => "New Meeting",
    'My Meetings' => "My Meetings",
    'No Meeting' => "You do not have any meeting",
    'Do you really want to delete this meeting?' => "Do you really want to delete this meeting?",
    'Informations' => "General information (step 1/2)",
    'Description' => "Description (optional)",
    'Date selection' => "Dates selection (step 2/2)",
    'Date selection hint' => "Please hit a day to select it. Hit << and >> to select month.",
    'Dates selected' => "Selected dates:",
    'You must choose a title for the meeting.' => "You must choose a title for the meeting.",
    'No date selected.' => "No date selected.",
    'Please choose at least one date.' => "Please choose one date at least.",


    'RDV notification' => "A meeting is proposed by ",
    'Now you can choose'=> "You can indicate you availabilities by going to:",
    'Available until'=>"Vote open until: ",

    'Information saved' => "Your information has been saved already, the meeting code is <b>%s</b>. Now you may invite some people to make their choice. The simplest way is to send email to your potential meeting mates :",
    'Information saved notice' => "On this page, they will be able to make their choice. Please check carefully that you copy the whole adress.",
    'Meeting not found' => "This meeting does not exist! Please check carefully that you copy the whole adress.",
    'Poll not available anymore'=>"Poll not available anymore.",

    'What are your preferences' => "What are your availabilities?",
    'Title:' => "Title:",
    'Poll tip' => "Fill the above text box with your name and make a choice by clicking the check-boxes. Then use \"Participate\" button to validate your vote. Alternatively, if you have a CAS account, you can <a href=\"%s\">log in</a>.",
    'Authentified poll tip' => "Indicate your selection by clicking on the check-boxes. Then use \"Participate\" button to validate your vote.",
    'Readonly poll tip' => "This survey is now closed. Here is the results:",
	'OK' => "Agree",
    'Not OK' => "Not agree",
    'Results:' => "Results :",
    'No description' => "No description.",
    'Your name' => "Your name",
    'Please enter your name.' => "Please enter your name.",
    'Edit warning' => "Warning: if you modify this meeting, already sent votes will be deleted.",
    'Comment information' => "You may use these fields to indicate any information you like - as a time or a length time or a place: \"from 8:00AM until 9:00AM\" , \"Room PC4, 3:00PM Main Building.\"",
    'Available if needed allowed' => "Do you wish to allow \"Available if needed\" polls?",
    'Available'=> 'Available',
    'Available if needed' => 'Available if needed',
    'Yes'=>"Yes",
    'No'=>"No",
    'Notification message'=>"A poll has occured for your survey:",
    'Voted by'=>'Voted by:',
    'Not authenticated'=>" (not authenticated) ",
    'CAS authenticated'=>" (authenticated)",
    'Do not reply'=>"\n-----\n DO NOT REPLY TO THIS MESSAGE! \n It has been generated automatically, your reply wouldn't be distributed!",
    'check all'=>"Check all",
    'uncheck all'=>"Uncheck all",
    'Click for comment'=>"Click this icon to add a comment about the date",
     'Write a comment'=>"Write a comment about this poll",
    'Add'=>"Add",
    'Available comments'=>"Do you allow participants to add a comment ?",
    'Available date comments'=>"Do you allow participants to add a comment for each date ?"

  );
?>
