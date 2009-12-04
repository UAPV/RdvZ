<?php

/**
 * meeting form.
 *
 * @package    rdvz
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id$
 */
class meetingForm extends BasemeetingForm
{
  public function configure()
  {
    unset(
      $this['created_at'], $this['updated_at'],
      $this['uid'], $this['closed'], $this['date_del'],
      $this['date_end'], $this['user'], $this['hash']
    );

    $this->widgetSchema['aifna'] = new sfWidgetFormChoice(array('choices' => Doctrine::getTable('meeting')->getChoices(), 'expanded' => true)) ;
    $this->widgetSchema['notif'] = new sfWidgetFormChoice(array('choices' => Doctrine::getTable('meeting')->getChoices(), 'expanded' => true)) ;
    
    $this->widgetSchema->setLabels(array(
      'title' => 'Titre : ',
      'description' => 'Description : ',
      'aifna' => 'Autoriser les votes<br />"Disponible en cas de besoin" : ',
      'notif' => 'Recevoir une notification<br />par mél lors d\'un vote : '
      )) ;
    
    foreach ($this->object['meeting_dates'] as $index => $date)
    {
      $name = 'date_'.$date['id'] ;

      $form = new meeting_dateForm($date) ;
      
      $this->embedForm($name, $form);

      $label = ' ' ;
      if($index > 0)
        $label.= '<input type="image" src="/images/close_16.png" name="submit" value="Supprimer_'.$date['id'].'" />' ;
      
      // c'est la dernière date...
      if (count($this->object['meeting_dates']) -1 == $index)
        $label.= '<input type="image" src="/images/add_16.png" name="submit" value="Nouveau" title="Ajouter une date" />';  

//      $label.= ' Date '.($index+1);
    
      $this->widgetSchema->setLabel($name, $label);    
    }
    
    $mails = sfContext::getInstance()->getUser()->getAttribute('mail') ;

    foreach($mails as $i => $mail)
    {
      $this->widgetSchema['input_mail_'.($i+1)] = new sfWidgetFormInputText(array(),array('class' => 'dynamic_mail', 'size' => '30')) ;
      $this->widgetSchema['input_mail_'.($i+1)]->setAttribute('value',$mail) ; 
      if($i != 0)
      {
        $this->widgetSchema->setLabel('input_mail_'.($i+1),"<a href='#' onclick=\"deleteWidget('Mail',".($i+1).")\"><img src='/images/close_16.png' class='mail_icon' alt='Supprimer' /></a>") ;
        $this->setValidator('input_mail_'.($i+1),new sfValidatorEmail(array('required' => false),array('invalid' => 'Cette adresse mail n\'est pas valide.'))) ;
      }
      else
      {
        $this->widgetSchema->setLabel('input_mail_1', ' ') ;
        $this->setValidator('input_mail_1', new sfValidatorEmail(array('required' => true), array('required' => 'Vous devez entrer au moins une adresse mail.', 'invalid' => 'Cette adresse mail n\'est pas valide.'))) ;
      }
    }

    $this->setValidator('title', new sfValidatorString(array('max_length' => 255), array('required' => 'Vous devez donner un titre au rendez-vous.', 'max_length' => 'Le titre ne peut pas excéder 255 caractères.'))) ;
    $this->setValidator('description', new sfValidatorString(array('required' => false))) ;


    $this->validatorSchema->setOption('allow_extra_fields', true) ;
    $this->validatorSchema->setOption('filter_extra_fields', false) ;
  }

  public function createMailInput()
  {
    $w = new sfWidgetFormInputText(array(), array('class' => 'dynamic_mail', 'size' => '30')) ;
    return $w ;
  }

  public function getJavascripts()
  {
    return array('jquery-ui-1.7.2.custom.min.js','jquery.livequery.js','add_input.js','ui.datepicker-fr.js') ;
  }

  public function getStylesheets()
  {
    return array('jquery-ui-1.7.2.custom.css' => 'screen') ;
  }
}
