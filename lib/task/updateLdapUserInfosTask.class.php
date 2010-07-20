<?php

class updateLdapUserInfosTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    $this->addOptions(array(
      new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','frontend'),
      new sfCommandOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'dev'),
      new sfCommandOption('connection', null, sfCommandOption::PARAMETER_REQUIRED, 'The connection name', 'doctrine'),
      // add your own options here
    ));

    $this->namespace        = 'rdvz';
    $this->name             = 'update-ldap-user-infos';
    $this->briefDescription = 'In case of changing LDAP attributes mapping, run this case to update the user table.';
    $this->detailedDescription = <<<EOF
The [updateLdapUserInfos|INFO] task retrieves current LDAP users in the database, and updates their information considering the LDAP attributes mapping (see `apps/frontend/config/app.yml`).
Call it with:

  [php symfony rdvz:update-ldap-user-infos|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // initialize the database connection
    $databaseManager = new sfDatabaseManager($this->configuration);
    $connection = $databaseManager->getDatabase($options['connection'])->getConnection();

    $this->logSection('rdvz', "Retrieving users...") ;

    $ldap = new uapvLdap() ;
    $users = Doctrine::getTable('user')->retrieveLdapUsers() ;

    $this->logSection('rdvz', "Updating information...") ;

    foreach($users as $user)
    {
      $us = $ldap->searchOne(sfConfig::get('app_profile_var_translation_uid')."=".$user->getLdapId()) ;

      $user->setName($us[sfConfig::get('app_profile_var_translation_name')]) ;
      $user->setSurname($us[sfConfig::get('app_profile_var_translation_surname')]) ;
      $user->setMail($us[sfConfig::get('app_profile_var_translation_mail')]) ;
      $user->save();
    }

    $this->logSection('rdvz', "Done.") ;
  }
}
