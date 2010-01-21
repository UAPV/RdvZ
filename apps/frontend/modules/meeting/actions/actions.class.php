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
    $mwnt = Doctrine::getTable('meeting')->getWithNoTitle() ;
    foreach($mwnt as $m)
      $m->delete() ;

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

    $this->redirect('meeting/show?h='.$this->meeting->getHash()) ;
  }

  public function executeVoteclose(sfWebRequest $request)
  {
    $meeting = Doctrine::getTable('meeting')->find($request->getParameter('id'));

    $closed = $meeting->getClosed() ? 0 : 1 ;
    $meeting->setClosed($closed) ;
    $meeting->save() ;

    $this->redirect('homepage') ;
  }

  public function executeNew(sfWebRequest $request)
  {
    $m = new meeting() ;
    $m->setTitle('') ;
    $m->setDescription('') ;
    $m->save() ;

    $d = new meeting_date();
    $d->setMid($m->getId()) ;
    $d->setDate(date('Y-m-d')) ;
    $d->save() ;

    $this->getUser()->setAttribute('mail', array(0 => '')) ;
    $this->getUser()->setAttribute('new', true) ;
    $this->redirect('meeting/edit?id='.$m->getId());
  }

  public function executeCreate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST));
    $data = $request->getParameter('meeting');

    $submit = ($request->hasParameter('submit') ? $request->getParameter('submit') : '_');
    $submit = explode('_',$submit);

    $this->processDates(&$data,$submit,&$meeting) ;

    $this->form = new meetingForm($meeting);
    $this->processForm($data, $this->form);

    $this->setTemplate('new');
  }

  public function executeEdit(sfWebRequest $request)
  {
    $this->forward404Unless($meeting = Doctrine::getTable('meeting')->find($request->getParameter('id')), sprintf('Object meeting does not exist (%s).', $request->getParameter('id')));
    $this->form = new meetingForm($meeting);
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod(sfRequest::POST) || $request->isMethod(sfRequest::PUT));
    $this->forward404Unless($meeting = Doctrine::getTable('meeting')->find($request->getParameter('id')), sprintf('Object meeting does not exist (%s).', $request->getParameter('id')));
    $data = $request->getParameter('meeting');

    $submit = ($request->hasParameter('submit') ? $request->getParameter('submit') : '_');
    $submit = explode('_',$submit);
    
/*
    $res = Doctrine::getTable('meeting_poll')->retrieveByMid($meeting->getId()) ;
    foreach($res as $p)
      $p->delete() ;

    $res = Doctrine::getTable('meeting_date')->retrieveByMid($meeting->getId()) ;
    foreach($res as $d)
      $d->delete() ;
*/

    $this->processDates(&$data,$submit,&$meeting) ;

    $this->getUser()->setAttribute('mail',$this->fetchMails($data)) ;
    $this->form = new meetingForm($meeting);
    $this->processForm($data, $this->form);

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
  
  protected function processDates($data, $submit, $meeting)
  {
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

      $mails = $this->fetchMails($data,true) ;
      $this->getUser()->setAttribute('mail', array()) ;
      $this->getUser()->setAttribute('new', false) ;
      
      $this->sendMails($mails,$meeting) ;

      $this->redirect('homepage') ;
    }
  }

  protected function sendMails($mails,$meeting)
  {
    $subject = "[RDVZ] Proposition de rendez-vous" ;

    try {     
      foreach($mails as $mail)
      {
        $mailBody = $this->getPartial('meeting/notif_new_meeting', array('user' => Doctrine::getTable('user')->find($meeting->getUid()), 'meeting' => $meeting, 'action' => ($this->isMailFromLdap($mail) ? 'show' : 'showua' ))) ;
        $this->getMailer()->composeAndSend('rdvz-admin@univ-avignon.fr', $mail, $subject, $mailBody);
      }
    }
    catch(Exception $e)
    {
      echo $e->getMessage();
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
      if ($d->getComment() != '')
        $this->comments[] = $d->getComment() ;

      $v = Doctrine::getTable('meeting_poll')->retrieveByDateId($d->getId()) ;
      foreach($v as $poll)
      {
        if(is_null($poll->getUid()))
        {
//          if(!array_key_exists($poll->getParticipantName(),$this->votes))
            $this->votes[$poll->getParticipantName()][$d->getId()] = $poll ;
/*          else
          {
            $i = 1 ;
            while(array_key_exists($poll->getParticipantName().++$i, $this->votes));
            $this->votes[$poll->getParticipantName().$i][$d->getId()] = $poll ;
          }*/
        }
        else
        {
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
