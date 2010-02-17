<?php

/**
 * meeting actions.
 *
 * @package    rdvz
 * @subpackage meeting
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12474 2008-10-31 10:41:27Z fabien $
 */
class meetingActions extends sfActions
{
  public function executeIndex(sfWebRequest $request)
  {
/*
    $mwnt = Doctrine::getTable('meeting')->getWithNoTitle() ;
    foreach($mwnt as $m)
      $m->delete() ;
*/
    /*
    if($this->getUser()->hasCredential('invite')) 
    {
      $this->getUser()->setAuthenticated(false) ;
      $this->getUser()->removeCredential('invite') ;
    }
    */

    $this->getUser()->setAttribute('mail', array()) ;
    $this->getUser()->setAttribute('new', false) ;
    $this->meeting_list = Doctrine::getTable('meeting')->getMeetingsFromUser($this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
  }

  public function executeShow(sfWebRequest $request)
  {
    $this->processShow($request) ;
  }

  public function executeShowua(sfWebRequest $request)
  {
    $this->getUser()->setAuthenticated(true) ;
    $this->getUser()->addCredential('invite') ;
    $this->processShow($request) ;
    $this->setTemplate('show') ;
  }

  public function executeSearch(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    if(!$this->meeting)
    {
      $this->getUser()->setFlash('error', 'Aucun rendez-vous ne correspond à ce code.') ;
      $this->redirect('homepage') ;
    }
    else
      $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
  }

  public function executeEditvote(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);
    
    $this->getUser()->setAttribute('edit',true) ;

    $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
  }

  public function executeValidvote(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    $votes = $request->getPostParameters() ;

    $meeting_dates = Doctrine::getTable('meeting_date')->retrieveByMid($this->meeting->getId()) ;
    
    foreach($meeting_dates as $date)
    {
      $poll = Doctrine::getTable('meeting_poll')->retrieveByUserAndDateId($date->getId(),$this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;

      if(in_array($poll->getId(), array_keys($votes)))
        $poll->setPoll(1) ;
      else
        $poll->setPoll(0) ;

      $poll->save() ;
    }

    $this->getUser()->setAttribute('edit',null) ;

    $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
  }

  public function executeVote(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    $votes = $request->getPostParameters() ;

    $this->meeting_dates = Doctrine::getTable('meeting_date')->retrieveByMid($this->meeting->getId()) ;
    
    foreach($this->meeting_dates as $date)
    {
      $poll = new meeting_poll() ;
      if($this->getUser()->hasCredential('member'))
        $poll->setUid($this->getUser()->getProfileVar(sfConfig::get('app_user_id'))) ;
      else
        $poll->setParticipantName($votes['name']) ;

      $poll->setDateId($date->getId()) ;

      if(in_array($date->getId(), array_keys($votes)))
        $poll->setPoll(1) ;
      else
        $poll->setPoll(0) ;

      $poll->save() ;
    }

    if ($this->meeting->getNotif())
      $this->sendNotifMail($this->meeting) ;

    if($this->getUser()->hasCredential('invite')) $this->redirect('meeting/showua?h='.$this->meeting->getHash()) ;
    elseif($this->getUser()->hasCredential('member')) $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
    else $this->forward404() ;
  }

  public function executeVoteclose(sfWebRequest $request)
  {
    $meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));

    $closed = $meeting->getClosed() ? 0 : 1 ;
    $meeting->setClosed($closed) ;
    $meeting->save() ;

    $this->redirect('homepage') ;
  }

  public function executeRazvote(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    $this->user = Doctrine::getTable('user')->find($request->getParameter('u')) ;
    
    if(empty($this->user))
      $t = Doctrine::getTable('meeting_poll')->getByParticipantName($request->getParameter('u'),$this->meeting->getId()) ;
    else
      $t = Doctrine::getTable('meeting_poll')->getByUid($this->user->getId(),$this->meeting->getId()) ;

    foreach($t as $poll)
      $poll->delete() ;

    $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
  }

  public function executeCsv(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h')) ;

    $counts        = Doctrine::getTable('meeting_poll')->getVotesByMeeting($this->meeting->getId()) ;
    $meeting_dates = Doctrine::getTable('meeting_date')->retrieveByMid($this->meeting->getId()) ;

    $this->counts = array() ;
    foreach($meeting_dates as $md)
      foreach($counts as $c)
        if($c->getDateId() == $md->getId())
          $this->counts[$md->getId()] = array('date' => $md->getDate(), 'count' => $c->getCnt(), 'comment' => $md->getComment()) ;
    
    $this->getResponse()->setContentType('text/comma-separated-values'); 
    $this->getResponse()->setHttpHeader('Content-Disposition', 'attachment; filename='.$this->meeting->getHash().'.csv');

  }

  public function executeNew(sfWebRequest $request)
  {
/*
    $m = new meeting() ;
    $m->setTitle('') ;
    $m->setDescription('') ;
    $m->save() ;

    $d = new meeting_date();
    $d->setMid($m->getId()) ;
    $d->setDate(date('Y-m-d')) ;
    $d->save() ;
*/
    $this->getUser()->setAttribute('mail', array(0 => '')) ;
    $this->getUser()->setAttribute('date', array()) ;
    $this->getUser()->setAttribute('comment', array()) ;
    $this->getUser()->setAttribute('new', true) ;
    $this->form = new meetingForm();
//    $this->redirect('meeting/edit?id='.$m->getId());
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $data = $request->getParameter('meeting');

    $dates = $this->fetchDates($data) ;
    $this->getUser()->setAttribute('mail',$this->fetchMails($data)) ;
    $this->getUser()->setAttribute('date',$dates) ;
    $this->getUser()->setAttribute('comment', $this->fetchComments($data)) ;
      
    $this->form = new meetingForm();

    if(empty($dates))
      $this->getUser()->setFlash('error', 'Vous devez sélectionner au moins une date pour créer un sondage.') ;
    else
      $this->processForm($data, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($meeting = Doctrine::getTable('meeting')->find($request->getParameter('id')), sprintf('Object meeting does not exist (%s).', $request->getParameter('id')));

    $dates    = array() ;
    $comments = array() ;

    $d = Doctrine::getTable('meeting_date')->retrieveByMid($meeting->getId()) ;
    
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

    $this->form = new meetingForm($meeting);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($meeting = Doctrine::getTable('meeting')->find($request->getParameter('id')), sprintf('Object meeting does not exist (%s).', $request->getParameter('id')));

    $data = $request->getParameter('meeting');
    
/*
    $submit = ($request->hasParameter('submit') ? $request->getParameter('submit') : '_');
    $submit = explode('_',$submit);
    
    $res = Doctrine::getTable('meeting_poll')->retrieveByMid($meeting->getId()) ;
    foreach($res as $p)
      $p->delete() ;

    $res = Doctrine::getTable('meeting_date')->retrieveByMid($meeting->getId()) ;
    foreach($res as $d)
      $d->delete() ;

    $this->processDates(&$data,$submit,&$meeting) ;

    $this->getUser()->setAttribute('mail',$this->fetchMails($data)) ;
*/
    $before_dates    = $this->getUser()->getAttribute('date_prime') ;
    $before_comments = $this->getUser()->getAttribute('comment_prime') ;
    $after_dates     = $this->fetchDates($data) ;
    $after_comments  = $this->fetchComments($data) ;

    $this->getUser()->setAttribute('date',$after_dates) ;
    $this->getUser()->setAttribute('comment', $after_comments) ;

    $this->form = new meetingForm($meeting);
    $this->form->bind($data) ;
//    $this->processForm($data, $this->form);
    if ($this->form->isValid())
    {
      $dates_to_add = array_diff_assoc($after_dates, $before_dates) ;
      $dates_to_remove = array_diff_assoc($before_dates, $after_dates) ;
      $comments_to_add = array_diff_assoc($after_comments, $before_comments) ;
  //    $comments_to_remove = array_diff_assoc($before_comments, $after_comments) ;

      foreach($dates_to_add as $did => $val)
      {
        $d = Doctrine::getTable('meeting_date')->findWithMid($did,$meeting->getId()) ;
        if(is_null($d))
        {
          $d = new meeting_date() ;
          $d->setDate(date_format(date_create($val),'Y-m-d')) ;
          $d->setComment($comments_to_add[$did]) ;
          $d->setMid($meeting->getId()) ;
        }
        else
        {
          $d->setDate(date_format(date_create($val),'Y-m-d')) ;
          if(array_key_exists($did,$comments_to_add)) $d->setComment($comments_to_add[$did]) ;
        }

        $d->save() ;
        unset($dates_to_remove[$did]) ;
        unset($comments_to_add[$did]) ;
      }

      foreach($dates_to_remove as $did => $val)
      {
        $polls = Doctrine::getTable('meeting_poll')->retrieveByDateId($did) ;
        foreach($polls as $p) 
          $p->delete() ;

        $d = Doctrine::getTable('meeting_date')->findWithMid($did, $meeting->getId()) ;
        $d->delete() ;
      }

      foreach($comments_to_add as $did => $comm)
      {
        $d = Doctrine::getTable('meeting_date')->find($did) ;
        $d->setComment($comments_to_add[$did]) ;
        $d->save() ;
      }

      $this->form->save() ;

      $this->getUser()->setAttribute('mail', array()) ;
      $this->getUser()->setAttribute('date', array()) ;
      $this->getUser()->setAttribute('new', false) ;

      $this->redirect('homepage') ; 
    }


    $this->setTemplate('edit');
  }

  public function executeDelete(sfWebRequest $request)
  {
    $request->checkCSRFProtection();

    $this->forward404Unless($meeting = Doctrine::getTable('meeting')->find($request->getParameter('id')), sprintf('Object meeting does not exist (%s).', $request->getParameter('id')));
    $meeting->delete();

    $this->redirect('meeting/index');
  }

  public function executeRenderMailInput(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('text/html; charset=utf-8');
    $current_id = $request->getParameter('current_id');

    $form = new meetingForm() ;

    $widget = $form->createMailInput() ;
    $html = $widget->render("meeting[input_mail_$current_id]") ;
    return $this->renderText($html) ;
  }

  public function executeRenderDateInput(sfWebRequest $request)
  {
    $this->getResponse()->setContentType('text/html; charset=utf-8');
    $current_id = $request->getParameter('current_id');
    $date = $request->getParameter('value') ;

    $form = new meetingForm() ;

    $widget_date = $form->createDateInput() ;
    $html = $widget_date->render("meeting[input_date_$current_id]",$date) ;

    return $this->renderText($html);//.$this->renderText($html2) ;
  }

  protected function fetchDates($data, $strict = false)
  {
  /*
    switch ($submit[0])
    {
      case 'Nouveau':
        $d = new meeting_date();
        $d->setMid($meeting->getId()) ;
        $d->setDate(date('Y-m-d')) ;
        $d->save() ;
        $meeting['meeting_dates'][] = $d ;
        break;
      case 'Supprimer':
        $this->forward404Unless($date = Doctrine::getTable('meeting_date')->find($submit[1]));
        unset($data['date_'.$date['id']]) ;
        $date->delete();
        break;
      default : break ;
    }
  */
    $dates = array() ;  

    foreach ($data as $widget => $value)
    {
      if(preg_match('/input_date_*/',$widget)) 
      {
        $parts = explode('_',$widget) ;
        $date = date_create($value) ;

        if ($strict && $date) $dates[$parts[2]] = date_format($date, 'd-m-Y') ;
        elseif (!$strict) $dates[$parts[2]] = $value ;
      }
    }

    return $dates ;
  }

  protected function fetchComments($data)
  {
    $comments = array() ;

    foreach ($data as $widget => $value)
    {
      if(preg_match('/input_comment_*/',$widget))
      {
        $parts = explode('_',$widget) ;
        $comments[$parts[2]] = $value ;
      }
    }

    return $comments ;
  }

  protected function fetchMails($data, $strict = false)
  {
    $mails = array() ;
 
    foreach ($data as $widget => $value)
    {
      if(preg_match('/input_mail_*/',$widget)) 
      {
        if ($strict && preg_match('/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/', $value))
          $mails[] = $value ;
        elseif (!$strict)
          $mails[] = $value ;
      }
    }

    return $mails ;
  }

  protected function processForm($data, sfForm $form)
  {
    $form->bind($data) ;

    if ($form->isValid())
    {
      $meeting = $form->save();

      $mails =    $this->fetchMails($data,true) ;
      $dates =    $this->fetchDates($data) ; 
      $comments = $this->fetchComments($data) ;

      $this->saveDates($dates,$comments,$meeting) ;

      $this->getUser()->setAttribute('mail', array()) ;
      $this->getUser()->setAttribute('date', array()) ;
      $this->getUser()->setAttribute('new', false) ;
      
      $this->sendMails($mails,$meeting) ;

      $this->redirect('homepage') ;
    }
  }

  protected function sendMails($mails,$meeting)
  {
    $subject = "[RdvZ] Proposition de rendez-vous" ;

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

  protected function sendNotifMail($meeting)
  {
    $subject = "[RdvZ] Nouveau vote pour votre rendez-vous" ;

    $user = Doctrine::getTable('user')->find($meeting->getUid()) ;
    try {
      $mailBody = $this->getPartial('meeting/notif_new_vote', array('meeting' => $meeting)) ;
      $this->getMailer()->composeAndSend('rdvz-admin@univ-avignon.fr', $user->getMail(), $subject, $mailBody);
    }
    catch(Exception $e)
    {
      echo $e->getMessage() ;
    }
  }

  private function saveDates($dates,$comments,$meeting)
  {
    foreach($dates as $num => $date)
    {
      $d = new meeting_date();
      $d->setMid($meeting->getId()) ;
      $d->setComment($comments[$num]) ;
      $d->setDate(date_format(date_create($date),'Y-m-d')) ;
      $d->save() ;
    }
  }

  private function isMailFromLdap($mail)
  {
    $ldap = new uapvLdap() ;
    $tmp = $ldap->search("mail=$mail") ;
    return empty($tmp) ? false : true ;
  }

  private function processShow(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    $meeting_dates = Doctrine::getTable('meeting_date')->retrieveByMid($this->meeting->getId()) ;
    
    setlocale(LC_TIME,'fr_FR.utf8','fra') ;

    $this->dates    = array() ;
    $this->months   = array() ;
    $this->comments = array() ;
    $this->votes    = array() ;
    
    foreach($meeting_dates as $d)
    {
      $f = strtotime($d->getDate()) ;
      $this->months[] = strftime("%B %Y",$f) ;
      $this->dates[strftime("%B %Y", $f)][$d->getId()] = strftime("%a %d", $f) ;
      //if ($d->getComment() != '')
      $this->comments[] = $d->getComment() ;

      $v = Doctrine::getTable('meeting_poll')->retrieveByDateId($d->getId()) ;
      
      if(!count($v))
      {
        $u = Doctrine::getTable('meeting_poll')->retrieveUidByMeetingId($this->meeting->getId()) ;
        $n = Doctrine::getTable('meeting_poll')->retrieveNameByMeetingId($this->meeting->getId()) ;

        foreach($u as $uid)
        {
          $p = new meeting_poll() ;
          $p->setPoll(-1000) ;
          $p->setDateId($d->getId()) ;
          $p->setUid($uid) ;
          $p->save() ;
          $this->votes[$uid][$d->getId()] = $p ;
        }

        foreach($n as $name)
        {
          $p = new meeting_poll() ;
          $p->setPoll(-1000) ;
          $p->setDateId($d->getId()) ;
          $p->setParticipantName($name) ;
          $p->save() ;
          $this->votes[$name][$d->getId()] = $p ;
        }
      }
      else
      {
        foreach($v as $poll)
        {
          if(is_null($poll->getUid()))
            $this->votes[$poll->getParticipantName()][$d->getId()] = $poll ;
          else
            $this->votes[$poll->getUid()][$d->getId()] = $poll ;
        }
      }
    }

    $this->months = array_unique($this->months) ;

    $t = Doctrine::getTable('meeting_poll')->getVotesByMeeting($this->meeting->getId()) ;
    $max = 0 ;

    foreach($t as $res)
      if ($res->getCnt() > $max) $max = $res->getCnt() ;

    $this->bests = array() ;
    $this->md = $meeting_dates ;
    
    foreach($t as $res)
      if($res->getCnt() == $max) $this->bests[] = $res->getDateId() ;
  }
}
