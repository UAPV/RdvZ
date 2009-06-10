<?php
  $path=ini_get('include_path');
$newpath="includes:".$path;
ini_set('include_path',$newpath);
  
  
  require_once ('authentication.php');
  //verification la variable mid
   if (isset($_GET['mid']) AND strlen($_GET['mid'])==8)
  {
	$mid = $_GET['mid'];
	require_once('includes/connection.php');

	//verifier si la personne qui lance l'export = le proprietaire
	$sql = "SELECT uid FROM meeting WHERE mid='".$BDD->escapeSimple($mid)."'";
	$res= $BDD->query($sql);
	//le proprietaire de cette reunion
	$uid2 = $BDD->getOne($sql);
	
	
	
		//si la personne qui demande l'export = le proprietaire
      	if ($uid == $uid2)
        {
		$array_date=array();
		$array_participant=array();
		$array_participation=array();
		
		//Recuperation des dates du sondage
		$sql="select * from meeting_date where mid='".$mid."'";
		//flag
		//echo $sql."<br>";
		
		$res_date = $BDD->query($sql);
		if (DB::isError($res_date)) die ($res_date->getMessage());
		else
		{
			while ($date_meeting = $res_date->fetchRow(DB_FETCHMODE_ASSOC)) 
			{
				
				$array_date[$date_meeting['date']]=$date_meeting['comment'];
								
				
				//Pour la date correspondante, recuperation des participants
				$sql = "select * from meeting_poll,meeting_date " .
						"where " .
						"meeting_poll.pollid=meeting_date.pollid and " .
						"meeting_date.mid='".$mid."' and".
						" meeting_date.date='".$date_meeting['date']."'" .
						" and meeting_poll.poll='1' ";
						
				//flag		
				//echo $sql."<br>";
				$res_participation=$BDD->query($sql);
				if (DB::isError($res_participation)) die ($res_participation->getMessage());
				else
				{
					while ($participation = $res_participation->fetchRow(DB_FETCHMODE_ASSOC))
					{
						$temp=array();
						if ($participation['uid']!='') $temp['participant']=$participation['uid'];
						else  $temp['participant']=$participation['participant_name'];
						$temp['date']=$participation['date'];						
						array_push($array_participation,$temp);
					}
				}			
			}
						
			//Construction du tableau complet de tous les participants pour la reunion
			foreach ($array_participation as $participation) $array_participant[]=$participation['participant'];
			$array_participant=array_unique($array_participant);
		    asort($array_participant);
		   
		 	//On construit les lignes pour le fichier csv
		 	$array_lines=array();				
			foreach($array_participant as $participant)
			{
				$line=array();
				$line['nom']=$participant;
				foreach ($array_date as $date=>$comment)
				{					
					$found=0;
					reset($array_participation);
					
					while (($participation=current($array_participation)) && ($found==0))
					{
						if (($participation['participant']==$participant)&&($participation['date']==$date)) 
						{
							$line[$date]='X';
							$found=1;
							}
						else 
						{
							$line[$date]='';
						}						
						next($array_participation);
					}
					reset($array_participation);									
					}
					reset($array_date);
					
				$array_lines[$participant]=$line;
				
			}	
		
		
		
		//Creation csv
		
			
		$tmpfname = tempnam($temp_dir, $mid."csv");
		$temp_file = fopen($tmpfname, "w");
		
		$date_csv=array_keys($array_date);
		array_unshift($date_csv,'');
		fputcsv($temp_file,$date_csv); 
	
		foreach($array_lines as $line)
		{
			
			fputcsv($temp_file,$line);
		}
		header("Content-type: application/force-download");
		header("Content-Disposition: attachment; filename=".$mid.".csv");
		readfile($tmpfname);	
		fclose($temp_file);	
		
		
		

//			
//			//Telechargement csv
//			header("Location: index.php");*/
        }
  }
  else
  {
	die ('mid invalide!');
  }
  } 
  
?>