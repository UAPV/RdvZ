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
    $opt = array('dsn' => $arguments['dsn'], 'username' => $arguments['username'], 'password' => $arguments['password']) ;
    $d2 = new sfPDODatabase($opt) ;
    $rdvz1 = $d2->getConnection() ;
    $this->logSection('rdvz', sprintf('Retrieving datas from RdvZ 1.x database "%s"', $opt['dsn'])) ;

    /**
      * This part is about fetching the datas from the old database
      * and formatting them before inserting into the new one.
      */
    
    // initialize the database connection for RdvZ 2.0 database
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $res = $rdvz1->query('select * from meeting')->fetchAll() ;
    foreach($res as $meet)
    {
      // If the user doesn't exists in the new database, we have to retrieve his information
      // from the ldap server.
      if(sfConfig::get('app_authentication_type') == 'ldap') 
      {
        $user = $this->getUserFromDatabase($meet['uid']) ;
        $user_id = $user->getId() ;
        $user->free() ;
        unset($user) ;
      }

      $meeting = new meeting() ;
      $meeting->setHash($meet['mid']) ;
      $meeting->setTitle($meet['title']) ;
      $meeting->setDescription($meet['description']) ;
      $meeting->setUid($user_id) ;
      $meeting->setClosed($meet['closed']) ;
      $meeting->setDateDel($meet['date_del']) ;
      $meeting->setDateEnd($meet['date_end']) ;
      $meeting->setNotif(($meet['notif'] == 'Y' ? 1 : 0)) ;
      $meeting->save(null,false) ;

      $meeting_id = $meeting->getId() ;
      $meeting->free() ;
      unset($meeting) ;

      $res2 = $rdvz1->query("select * from meeting_date where mid = '".$meet['mid']."'")->fetchAll() ;
      foreach($res2 as $date)
      {
        $meeting_date = new meeting_date() ;
        $meeting_date->setMid($meeting_id) ;
        $meeting_date->setDate($date['date']) ;
        $meeting_date->setComment($date['comment']) ;
        $meeting_date->save() ;

        $meeting_date_id = $meeting_date->getId() ;
        $meeting_date->free() ;
        unset($meeting_date) ;
        
        $res3 = $rdvz1->query("select * from meeting_poll where pollid = ".$date['pollid'])->fetchAll() ;
        foreach($res3 as $poll)
        {
          $meeting_poll = new meeting_poll() ;
          $meeting_poll->setDateId($meeting_date_id) ;
          $meeting_poll->setPoll($poll['poll']) ;

          if ($poll['user_comment'])
            $meeting_poll->setComment($poll['user_comment']) ;

          if ($poll['uid'] != '')
          {
            $poll_user = $this->getUserFromDatabase($poll['uid']) ;
            $meeting_poll->setUid($poll_user->getId()) ;
            $poll_user->free() ;
            unset($poll_user) ;
          }

          if ($poll['participant_name'] != '')
            $meeting_poll->setParticipantName($poll['participant_name']) ;

          $meeting_poll->save() ;
          $meeting_poll->free() ;
          unset($meeting_poll) ;
        }
      }
    }

    $this->logSection('rdvz', "The RdvZ 2.0 database is now filled with your former datas. You can now delete the old database.") ;
  }

  private function getUserFromDatabase($ldap_id)
  {
    $ldap = new uapvLdap() ;
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
