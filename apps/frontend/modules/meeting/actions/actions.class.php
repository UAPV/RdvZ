<?php

/**
 * meeting actions.
 * This is main controller of RdvZ.
 *
 * @package    rdvz
 * @subpackage meeting
 * @author     Romain Deveaud <romain.deveaud@univ-avignon.fr>
 */
class meetingActions extends sfActions
{
  /**
    * Homepage of the app.
    */
  public function executeIndex(sfWebRequest $request)
  {
    $this->getUser()->setAttribute('participant_name', null) ;
    $this->getUser()->setAttribute('mail', array()) ;
    $this->getUser()->setAttribute('new', false) ; // to remove?

    $this->cronTasks() ;

    // Getting the meetings created by the logged in user.
    $this->meeting_list = Doctrine::getTable('meeting')->getMeetingsFromUser($this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;

    $this->followed_meeting_list = Doctrine::getTable('meeting')->getMeetingsFollowedByUser($this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
  }

  /**
    * Show the meeting's vote summary + handle the user comment entry.
    */
  public function executeShow(sfWebRequest $request)
  {
    // If the hash does not exists, redirect the user.
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    $comm = $request->getParameter('comm');

    // If a user just entered a comment, save it !
    if($comm)
      $this->setComment($request->getParameter('poll_id'),$comm) ;

    // Get all the variables used to display the recap table.
    $vars = $this->meeting->processShow($this->getUser()->getCulture()) ;

    $this->dates    = $vars['dates'] ;
    $this->comments = $vars['comments'] ;
    $this->bests    = $vars['bests'] ;
    $this->md       = $vars['md'] ;
    $this->months   = $vars['months'] ;
    $this->votes    = $vars['votes'] ;
    $this->counts   = $vars['counts'] ;
  }

  /**
    * This action is not secured, it allows extern users to watch and 
    * vote for a meeting.
    */
  public function executeShowua(sfWebRequest $request)
  {
    // The 'invite' credential allows the user only to watch meetings
    // from the 'showua' action.
    $this->getUser()->setAuthenticated(true) ;
    $this->getUser()->addCredential('invite') ;
    $this->getUser()->setCulture('fr') ;

    $this->executeShow($request) ;
  }

  public function executeOldmeet(sfWebRequest $request)
  {
    $request->setParameter('h',$request->getParameter('mid')) ;
    $this->executeShow($request) ;
  }

  /**
    * The action called by the big search field on the top of the
    * homepage.
    */
  public function executeSearch(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    if(!$this->meeting)
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
      $this->getUser()->setFlash('error', __('Aucun sondage ne correspond à ce code.')) ;
      $this->redirect('homepage') ;
    }
    else
      $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
  }

  /**
    * Vote editing, only available for authentificated users.
    */
  public function executeEditvote(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);
    
    $this->getUser()->setAttribute('edit',true) ;

    if($this->getUser()->hasCredential('invite')) $this->redirect('meeting/showua?h='.$this->meeting->getHash()) ;
    elseif($this->getUser()->hasCredential('member')) $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
    else $this->forward404() ;
  }

  /**
    * Vote updating after editing.
    */
  public function executeValidvote(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    $votes = $request->getPostParameters() ;

    $meeting_dates = Doctrine::getTable('meeting_date')->retrieveByMid($this->meeting->getId()) ;
    
    foreach($meeting_dates as $date)
    {
      // For each date of the meeting, get the votes of the current user
      // and update their values.
      if($this->getUser()->hasCredential('member'))
        $poll = Doctrine::getTable('meeting_poll')->retrieveByUserAndDateId($date->getId(),$this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
      elseif($this->getUser()->hasCredential('invite'))
        $poll = Doctrine::getTable('meeting_poll')->retrieveByUserNameAndDateId($date->getId(),$this->getUser()->getAttribute('participant_name')) ;

      if(in_array($poll->getId(), array_keys($votes)))
        $poll->setPoll(1) ;
      else
        $poll->setPoll(0) ;

      $poll->save() ;
    }

    $this->getUser()->setAttribute('edit',null) ;

    if($this->getUser()->hasCredential('invite')) $this->redirect('meeting/showua?h='.$this->meeting->getHash()) ;
    elseif($this->getUser()->hasCredential('member')) $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
    else $this->forward404() ;
  }

  /**
    * Normal vote of a user.
    */
  public function executeVote(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    $votes = $request->getPostParameters() ;

    if($this->getUser()->hasCredential('invite') && $votes['name'] == "")
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
      $this->getUser()->setFlash('error', __('Vous devez entrer votre nom pour voter.')) ;
      $this->redirect('meeting/showua?h='.$this->meeting->getHash()) ;
    }

    $this->meeting_dates = Doctrine::getTable('meeting_date')->retrieveByMid($this->meeting->getId()) ;
    
    foreach($this->meeting_dates as $date)
    {
      $poll = new meeting_poll() ;

      if($this->getUser()->hasCredential('member'))
      {
        $poll->setUid($this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
        $poll->setDateId($date->getId()) ;
      }
      else
      {
        $name = $votes['name'] ;
        $poll = Doctrine::getTable('meeting_poll')->retrieveByUserNameAndDateId($date->getId(), $name) ;
        $poll->setParticipantName($name) ;
        $this->getUser()->setAttribute('participant_name',$name) ;
      }

      if(in_array($date->getId(), array_keys($votes)))
        $poll->setPoll(1) ;
      else
        $poll->setPoll(0) ;

      $poll->save() ;
    }

    // If the creator wants to know when there is a new vote, send him a 
    // mail right now !
    if ($this->meeting->getNotif() && $this->getUser()->getProfileVar(sfConfig::get('app_user_id')) != $this->meeting->getUid())
    {
      if($this->getUser()->hasCredential('member'))
      {
        $user = Doctrine::getTable('user')->find($this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
        $u_name = $user->getSurname().' '.$user->getName() ;
      }
      else
        $u_name = $this->getUser()->getAttribute('participant_name') ;

      $this->sendNotifMail($this->meeting,$u_name) ;
    }

    // Different redirection depending on the credentials of the user.
    if($this->getUser()->hasCredential('invite')) $this->redirect('meeting/showua?h='.$this->meeting->getHash()) ;
    elseif($this->getUser()->hasCredential('member')) 
    {
      // Auto bookmarking for authenticated users.
      $follow = new is_following() ;
      $follow->setMid($this->meeting->getId()) ;
      $follow->setUid($this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
      $follow->save() ;

      $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
    }
    else $this->forward404() ;
  }

  /**
    * Only available for the creator ; closes/opens the votes for the meeting.
    */
  public function executeVoteclose(sfWebRequest $request)
  {
    $meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($meeting);

    // If the meeting is opened, close it ; if it is closed, open it.
    $closed = $meeting->getClosed() ? 0 : 1 ;
    $meeting->setClosed($closed) ;
    $meeting->save() ;

    $this->redirect('homepage') ;
  }

  /**
    * Reset the votes of a user. It removes a line from the recap table.
    */
  public function executeRazvote(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    // Get the user.
    $this->user = Doctrine::getTable('user')->find($request->getParameter('u')) ;
    
    // If we can't find him, it's an extern user, so get his votes
    // by his name.
    if(empty($this->user))
      $t = Doctrine::getTable('meeting_poll')->getByParticipantName($request->getParameter('u'),$this->meeting->getId()) ;
    else
      $t = Doctrine::getTable('meeting_poll')->getByUid($this->user->getId(),$this->meeting->getId()) ;

    foreach($t as $poll)
      $poll->delete() ;

    $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
  }

  /**
    * Export the meeting results as a CSV file.
    */
  public function executeCsv(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h')) ;
    $this->forward404Unless($this->meeting);

    $this->counts = $this->meeting->createCsv() ;
    
    // Prepare the response type and the file name.
    $this->getResponse()->setContentType('text/comma-separated-values'); 
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename='.$this->meeting->getHash().'.csv');

  }

  public function executeFollow(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h')) ;
    $this->forward404Unless($this->meeting);

    $user = Doctrine::getTable('user')->find($this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
    
    if(!$user)
    {
      $this->getContext()->getConfiguration()->loadHelpers(array('I18N'));
      $this->getUser()->setFlash('error', __('Vous ne semblez pas être authentifié. Veuillez vous authentifier pour suivre ce rendez-vous.')) ;
      $this->redirect($request->getReferer()) ;
    }

    if ($follow = Doctrine::getTable('is_following')->findOneByMidAndUid($this->meeting->getId(), $user->getId()))
      $follow->delete() ;
    else
    {
      $follow = new is_following() ;
      $follow->setMid($this->meeting->getId()) ;
      $follow->setUid($user->getId()) ;
      $follow->save() ;
    }

    //$this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
    if(!$request->isXmlHttpRequest())
      $this->redirect($request->getReferer()) ;

    return sfView::NONE ;
  }

  /**
    * Action triggered when a user clicks on 'Créer un nouveau rendez-vous'.
    */
  public function executeNew(sfWebRequest $request)
  {
    $this->getUser()->setAttribute('mail', array(0 => '')) ;
    $this->getUser()->setAttribute('date', array()) ;
    $this->getUser()->setAttribute('comment', array()) ;
    $this->getUser()->setAttribute('new', true) ; // to remove?

    $this->form = new meetingForm(array(), array('current_user' => $this->getUser())) ;
  }

  /**
    * New meeting form submitted.
    */
  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));

    $data = $request->getParameter('meeting');

    $dates = $this->fetchDates($data) ;
    $this->getUser()->setAttribute('mail',$this->fetchMails($data)) ;
    $this->getUser()->setAttribute('date',$dates) ;
    $this->getUser()->setAttribute('comment', $this->fetchComments($data)) ;
      
    $this->form = new meetingForm(array(), array('current_user' => $this->getUser())) ;

    // If the user didn't select any date...
    if(empty($dates))
    {
      sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
      $this->getUser()->setFlash('error', __('Vous devez sélectionner au moins une date pour créer un sondage.')) ;
    }
    else
      $this->processForm($data, $this->form);

    $this->setTemplate('new');
  }

  /**
    * Action triggered when the user wants to edit a meeting.
    */
  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($meeting = Doctrine::getTable('meeting')->find($request->getParameter('id')), sprintf('Object meeting does not exist (%s).', $request->getParameter('id')));

    $dates    = array() ;
    $comments = array() ;

    $d = Doctrine::getTable('meeting_date')->retrieveByMid($meeting->getId()) ;
    
    // Retrieving all the data associated to the meeting...
    foreach($d as $date)
    {
      $tmp = date_create($date->getDate()) ;
      $dates[$date->getId()] = date_format($tmp, 'd-m-Y') ;
      $comments[$date->getId()] = $date->getComment() ;
    }

    $this->getUser()->setAttribute('date',$dates) ;
    $this->getUser()->setAttribute('comment',$comments) ;

    $this->getUser()->setAttribute('date_prime',$dates) ;
    $this->getUser()->setAttribute('comment_prime',$comments) ;

    // ... and creating the form.
    $this->form = new meetingForm($meeting, array('current_user' => $this->getUser())) ;
  }

  /**
    * Submitting the meeting edition form.
    */
  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($meeting = Doctrine::getTable('meeting')->find($request->getParameter('id')), sprintf('Object meeting does not exist (%s).', $request->getParameter('id')));

    $data = $request->getParameter('meeting'); // retrieving meeting datas
    $data['id'] = $request->getParameter('id') ; // little hack to avoid a huge bug
                                                 // which I don't know how to fix

    $this->form = new meetingForm($meeting, array('current_user' => $this->getUser())) ;
    $this->form->bind($data) ;

    $before_dates    = $this->getUser()->getAttribute('date_prime') ;
    $before_comments = $this->getUser()->getAttribute('comment_prime') ;
    $after_dates     = $this->fetchDates($data) ;
    $after_comments  = $this->fetchComments($data) ;

    $this->getUser()->setAttribute('date',$after_dates) ;
    $this->getUser()->setAttribute('comment', $after_comments) ;

    if ($this->form->isValid())
    {
      // Comparing the submitted values with the 'old' ones.
      $dates_to_add = array_diff_assoc($after_dates, $before_dates) ;
      $dates_to_remove = array_diff_assoc($before_dates, $after_dates) ;
      $comments_to_add = array_diff_assoc($after_comments, $before_comments) ;

      // Inserting, updating or deleting dates and comments.
      $meeting->processDatesAndCommentsForUpdate($dates_to_add,$dates_to_remove,$comments_to_add) ;

      $this->form->save() ;

      $this->getUser()->setAttribute('mail', array()) ;
      $this->getUser()->setAttribute('date', array()) ;
      $this->getUser()->setAttribute('new', false) ; // to remove?

      $this->redirect('homepage') ; 
    }

    $this->setTemplate('edit');
  }

  /**
    * Delete a meeting.
    */
  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($meeting = Doctrine::getTable('meeting')->find($request->getParameter('id')), sprintf('Object meeting does not exist (%s).', $request->getParameter('id')));
    $meeting->delete();

    $this->redirect('meeting/index');
  }

  /**
    * Ajax called action which renders the mail input widget.
    */
  public function executeRenderMailInput(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('text/html; charset=utf-8');
    $current_id = $request->getParameter('current_id');

    $form = new meetingForm(array(), array('current_user' => $this->getUser())) ;

    $widget = $form->createMailInput() ;
    $html = $widget->render("meeting[input_mail_$current_id]") ;

    return $this->renderText($html) ;
  }

  /**
    * Ajax called action which renders the date input widget.
    */
  public function executeRenderDateInput(sfWebRequest $request)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    $this->getResponse()->setContentType('text/html; charset=utf-8');
    $current_id = $request->getParameter('current_id');
    $date = $request->getParameter('value') ;

    $form = new meetingForm(array(), array('current_user' => $this->getUser())) ;

    $widget_date = $form->createDateInput() ;
    $html = $widget_date->render("meeting[input_date_$current_id]",$date) ;

    return $this->renderText($html);
  }

  /**
    * Fetch the dates from the request information.
    */
  private function fetchDates($data, $strict = false)
  {
    $dates = array() ;  

    foreach ($data as $widget => $value)
    {
      // If the widget name is like 'input_date_*'...
      if(preg_match('/input_date_*/',$widget)) 
      {
        $parts = explode('_',$widget) ;
        $date = date_create($value) ;

        // Formatting the date for a better display...
        if ($strict && $date) $dates[$parts[2]] = date_format($date, 'd-m-Y') ;
        elseif (!$strict) $dates[$parts[2]] = $value ;
      }
    }

    return $dates ;
  }

  /**
    * Fetch the comments from the request information.
    */
  private function fetchComments($data)
  {
    $comments = array() ;

    foreach ($data as $widget => $value)
    {
      // If the widget name is like 'input_comment_*'...
      if(preg_match('/input_comment_*/',$widget))
      {
        $parts = explode('_',$widget) ;
        $comments[$parts[2]] = $value ;
      }
    }

    return $comments ;
  }

  /**
    * Fetch the mails from the request information.
    */
  private function fetchMails($data, $strict = false)
  {
    $mails = array() ;
 
    foreach ($data as $widget => $value)
    {
      // If the widget name is like 'input_mail_*'...
      if(preg_match('/input_mail_*/',$widget)) 
      {
        // Checking the mail syntax...
        if ($strict && preg_match('/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/', $value))
          $mails[] = $value ;
        elseif (!$strict)
          $mails[] = $value ;
      }
    }

    return $mails ;
  }

  /**
    * Meeting creation form processing...
    */
  protected function processForm($data, sfForm $form)
  {
    $form->bind($data) ;

    if ($form->isValid())
    {
      $meeting = $form->save();

      // Retrieving meeting creator's id.
      // This line was originally in the model, but it triggered bugs
      // with the fixtures.
      // Note : NEVER USE sfContext IN MODELS. Yes, it's tough...
      $meeting->setUid(sfContext::getInstance()->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
      $meeting->save() ;

      // Fetching the dynamic information...
      $mails =    $this->fetchMails($data,true) ;
      $dates =    $this->fetchDates($data) ; 
      $comments = $this->fetchComments($data) ;

      // ... saving...
      $meeting->saveDates($dates,$comments) ;

      $this->getUser()->setAttribute('mail', array()) ;
      $this->getUser()->setAttribute('date', array()) ;
      $this->getUser()->setAttribute('new', false) ; // to remove?
      
      // ... and sending mails.
      $this->sendMails($mails,$meeting) ;

      $this->redirect('homepage') ;
    }
  }

  /**
    * The function which sends mails when a meeting is created.
    */
  protected function sendMails($mails,$meeting)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    $subject = "[RdvZ] ".__('Proposition de rendez-vous') ;

    $user = Doctrine::getTable('user')->find($meeting->getUid()) ;
    try {     
      foreach($mails as $mail)
      {
        $mailBody = $this->getPartial('meeting/notif_new_meeting', array('user' => $user, 'meeting' => $meeting )) ;
        $this->getMailer()->composeAndSend($user->getMail()/*'rdvz-admin@univ-avignon.fr'*/, $mail, $subject, $mailBody);
      }
    }
    catch(Exception $e)
    {
      echo $e->getMessage();
    }
  }

  /**
    * The function which sends mails when the owner of the meeting
    * wants to be notified if a change occurs in the votes.
    */
  protected function sendNotifMail($meeting,$uname)
  {
    sfContext::getInstance()->getConfiguration()->loadHelpers(array('I18N'));
    $subject = "[RdvZ]".__('Nouveau vote pour votre rendez-vous') ;

    $user = Doctrine::getTable('user')->find($meeting->getUid()) ;
    try {
      $mailBody = $this->getPartial('meeting/notif_new_vote', array('meeting' => $meeting, 'uname' => $uname)) ;
      $this->getMailer()->composeAndSend(sfConfig::get('app_mail_sender', 'rdvz-admin@univ-avignon.fr'), $user->getMail(), $subject, $mailBody);
    }
    catch(Exception $e)
    {
      echo $e->getMessage() ;
    }
  }

  /**
    * Says if the given mail is present in the LDAP.
    */
  private function isMailFromLdap($mail)
  {
    $ldap = new uapvLdap() ;
    $tmp = $ldap->search("mail=$mail") ;
    return empty($tmp) ? false : true ;
  }

  /**
    * Sets the comment of a vote.
    */
  private function setComment($poll_id, $comment)
  {
    $poll = Doctrine::getTable('meeting_poll')->find($poll_id) ;
    $poll->setComment($comment) ;
    $poll->save() ;
  }

  /**
    * Closes the meetings that hadn't been closed and deletes the expired ones.
    */
  private function cronTasks()
  {
    $cron_meetings = Doctrine::getTable('meeting')->getExpiredMeetingsNotClosed() ;
    foreach($cron_meetings as $m)
    {
      $m->setClosed(1) ;
      $m->save() ;
    }

    $cron_meetings = Doctrine::getTable('meeting')->getMeetingsToDelete() ;
    foreach($cron_meetings as $m)
      $m->delete() ;
  }
}
