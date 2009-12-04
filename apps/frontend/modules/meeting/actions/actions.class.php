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
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    $this->meeting_dates = Doctrine::getTable('meeting_date')->retrieveByMid($this->meeting->getId()) ;
    setlocale(LC_TIME,'fr_FR.utf8','fra') ;
  }

  public function executeShowua(sfWebRequest $request)
  {
    $this->meeting = Doctrine::getTable('meeting')->getByHash($request->getParameter('h'));
    $this->forward404Unless($this->meeting);

    $this->meeting_dates = Doctrine::getTable('meeting_date')->retrieveByMid($this->meeting->getId()) ;
    setlocale(LC_TIME,'fr_FR.utf8','fra') ;
    $this->form = new meeting_pollForm() ;

    $this->setTemplate('show') ;
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
    $mailBody = $this->getPartial('meeting/notif_new_meeting', array('user' => Doctrine::getTable('user')->find($meeting->getUid()), 'meeting' => $meeting)) ;
    $subject = "[RDVZ] Proposition de rendez-vous" ;

    try {     
    foreach($mails as $mail)
      $this->getMailer()->composeAndSend('rdvz-admin@univ-avignon.fr', $mail, $subject, $mailBody);
    }
    catch(Exception $e)
    {
      echo $e->getMessage();
    }
  }
}
