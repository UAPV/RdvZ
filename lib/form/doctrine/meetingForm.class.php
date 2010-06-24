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
  protected $current_user ;

  public function configure()
  {
    if (!($this->getOption("current_user") instanceof sfUser))
      throw new InvalidArgumentException("You must pass a user object as an option to this form!");
    else
      $this->current_user = $this->getOption("current_user");

    unset(
      $this['created_at'], $this['updated_at'],
      $this['uid'], $this['closed'], $this['date_del'],
      $this['date_end'], $this['user'], $this['hash'],
      $this['aifna']
    );

    $this->widgetSchema['notif'] = new sfWidgetFormChoice(array('choices' => Doctrine::getTable('meeting')->getChoices(), 'expanded' => true)) ;
    
    
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
        $this->widgetSchema->setLabel('input_mail_1', 'Adresses mail : ' /* <img src="/images/info_button_16.png" class="mail_icon help" alt="Aide" /> '*/) ;
        $this->setValidator('input_mail_1', new sfValidatorEmail(array('required' => false), array(/*'required' => 'Vous devez entrer au moins une adresse mail.', */'invalid' => "Cette adresse mail n'est pas valide."))) ;
      }
    }

    $dates    = sfContext::getInstance()->getUser()->getAttribute('date') ;
    $comments = sfContext::getInstance()->getUser()->getAttribute('comment') ;

    foreach($dates as $i => $date)
    {
      $this->widgetSchema['input_date_'.$i] = new rdvzWidgetFormInputDateText(array(),array('class' => 'dynamic_date', 'size' => '10', 'disabled' => 'disabled')) ;
      $this->widgetSchema['input_date_'.$i]->setCommentValue($comments[$i]) ; 
      $this->widgetSchema['input_date_'.$i]->setAttribute('value',$date) ; 

      $this->widgetSchema->setLabel('input_date_'.($i),"<a href='#' onclick=\"deleteWidget('Date',".($i).")\"><img src='/images/close_16.png' class='mail_icon' alt='Supprimer' /></a>") ;
      $this->setValidator('input_date_'.$i,new sfValidatorDate(array('required' => false, 'date_output' => 'd-m-Y'),array('invalid' => "Cette date n'est pas valide."))) ;
    }


    $this->widgetSchema->setLabels(array(
      'title' => 'Titre : ',
      'description' => 'Description : ',
      'notif' => "Recevoir une notification par mail lors d'un vote : ",
      )) ;

    $this->setValidator('title', new sfValidatorString(array('max_length' => 100), array('required' => 'Vous devez donner un titre au rendez-vous.', 'max_length' => 'Le titre ne peut pas excéder 100 caractères, vous pouvez également utiliser la description.'))) ;
    $this->setValidator('description', new sfValidatorString(array('required' => false))) ;

    $this->validatorSchema->setOption('allow_extra_fields', true) ;
    $this->validatorSchema->setOption('filter_extra_fields', false) ;
  }

  public function createMailInput()
  {
    $w = new sfWidgetFormInputText(array(), array('class' => 'dynamic_mail', 'size' => '30')) ;
    return $w ;
  }

  public function createDateInput()
  {
    $w = new rdvzWidgetFormInputDateText(array(), array('class' => 'dynamic_date', 'size' => '10', 'disabled' => 'disabled')) ;
    return $w ;
  }

  public function createCommentInput()
  {
    $w = new sfWidgetFormInputText(array(), array('class' => 'dynamic_comment', 'size' => '30')) ;
    return $w ;
  }

  public function getJavascripts()
  {
    $js = array() ;
    if($this->current_user->getCulture() == 'fr')
      $js[] = 'ui.datepicker-fr.js' ;

    array_push($js,'jquery-ui-1.7.2.custom.min.js','jquery.i18n.js','add_input.js') ;

    return $js ;
  }

  public function getStylesheets()
  {
    return array('jquery-ui-1.7.2.custom2.css' => 'screen') ;
  }
}
