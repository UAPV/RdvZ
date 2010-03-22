<?php

class retrieveOldDatasTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    $this->addArguments(array(
      new sfCommandArgument('dsn', sfCommandArgument::REQUIRED, 'The database dsn'),
      new sfCommandArgument('username', sfCommandArgument::OPTIONAL, 'The database username', 'root'),
      new sfCommandArgument('password', sfCommandArgument::OPTIONAL, 'The database password'),
     ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'rdvz';
    $this->name             = 'retrieve-old-datas';
    $this->briefDescription = 'Retrieves RdvZ 1.x datas and inserts it into RdvZ 2.0 database.';
    $this->detailedDescription = <<<EOF
The [retrieveOldDatas|INFO] task allows to get back meeting datas from the RdvZ 1.x versions and use it with the RdvZ 2.0 versions.
Call it with:

  [php symfony configure:database mysql:host=localhost;dbname=example root mYsEcret|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection for RdvZ 1.x database
    //$opt = array_merge(isset($config[$options['env']][$options['name']]['param']) ? $config[$options['env']][$options['name']]['param'] : array(), array('dsn' => $arguments['dsn'], 'username' => $arguments['username'], 'password' => $arguments['password'])) ;
    $opt = array('dsn' => $arguments['dsn'], 'username' => $arguments['username'], 'password' => $arguments['password']) ;
    $d2 = new sfPDODatabase($opt) ;
    $rdvz1 = $d2->getConnection() ;

    /**
      * This part is about fetching the datas from the old database
      * and formatting them before inserting into the new one.
      */
    $ldap = new uapvLdap() ;
    

    // initialize the database connection for RdvZ 2.0 database
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $sql = 'select * from meeting' ;
    foreach($rdvz1->query($sql) as $meet)
    {
      // If the user doesn't exists in the new database, we have to retrieve his information
      // from the ldap server.
      if(sfConfig::get('app_authentication_type') == 'ldap') 
        $user = $this->getUserFromDatabase($meet['uid']) ;

//        $connection->exec("insert into user (ldap_id,name,surname,mail) values ('".$meet['uid']."','".$us[sfConfig::get('app_profile_var_translation_name')]."','".$us[sfConfig::get('app_profile_var_translation_surname')]."','".$us[sfConfig::get('app_profile_var_translation_mail')]."')") ;

//      $user_id = $connection->query("select id from user where ldap_id = '".$meet['uid']."'")->fetchColumn() ;

//      $connection->exec("insert into meeting (hash,title,description,uid,closed,date_del,date_end,notif) values ('".$meet['mid']."','".$meet['title']."','".$meet['description']."',$user_id,".$meet['closed'].",'".$meet['date_del']."','".$meet['date_end']."',".($meet['notif'] == 'Y' ? 1 : 0).")") ;
      $meeting = new meeting() ;
      $meeting->setHash($meet['mid']) ;
      $meeting->setTitle($meet['title']) ;
      $meeting->setDescription($meet['description']) ;
      $meeting->setUid($user->getId()) ;
      $meeting->setClosed($meet['closed']) ;
      $meeting->setDateDel($meet['date_del']) ;
      $meeting->setDateEnd($meet['date_end']) ;
      $meeting->setNotif(($meet['notif'] == 'Y' ? 1 : 0)) ;
      $meeting->save() ;

//      $meeting_id = $connection->query("select id from meeting where hash = '".$meet['mid']."')")->fetchColumn() ;

      $sql2 = "select * from meeting_date where mid = '".$meet['mid']."'" ;
      foreach($rdvz1->query($sql2) as $date)
      {
        //$connection->exec("insert into meeting_date (mid,date,comment) values ($meeting_id,'".$date['date']."','".$date['comment']."')") ;
        $meeting_date = new meeting_date() ;
        $meeting_date->setMid($meeting->getId()) ;
        $meeting_date->setDate($date['date']) ;
        $meeting_date->setComment($date['comment']) ;
        $meeting_date->save() ;
        
        $sql3 = "select * from meeting_poll where pollid = ".$date['pollid'] ;
        foreach($rdvz1->query($sql3) as $poll)
        {
          $meeting_poll = new meeting_poll() ;
          $meeting_poll->setDateId($meeting_date->getId()) ;
          $meeting_poll->setPoll($poll['poll']) ;
          $meeting_poll->setComment($poll['user_comment']) ;

          if ($poll['uid'] != '')
          {
            $poll_user = $this->getUserFromDatabase($poll['uid']) ;
            $meeting_poll->setUid($poll_user->getId()) ;
          }

          if ($poll['participant_name'] != '')
            $meeting_poll->setParticipantName($poll['participant_name']) ;

          $meeting_poll->save() ;
        }
      }
    }

    print "The RdvZ 2.0 database is now filled with your former datas. You can now delete the old database.\n" ;
  }

  private function getUserFromDatabase($ldap_id)
  {
    $user = Doctrine::getTable('user')->retrieveByLdapId($ldap_id);

    if ($user === null)
    {
      $us = $ldap->searchOne(sfConfig::get('app_profile_var_translation_uid')."=$ldap_id") ;

      $user = new user() ;
      $user->setLdapId($ldap_id) ;
      $user->setName($us[sfConfig::get('app_profile_var_translation_name')]) ;
      $user->setSurname($us[sfConfig::get('app_profile_var_translation_surname')]) ;
      $user->setMail($us[sfConfig::get('app_profile_var_translation_mail')]) ;
      $user->save() ;
    }

    return $user ;
  }
}
