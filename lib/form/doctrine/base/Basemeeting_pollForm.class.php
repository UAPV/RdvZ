<?php

/**
 * meeting_poll form base class.
 *
 * @method meeting_poll getObject() Returns the current form's model object
 *
 * @package    rdvz
 * @subpackage form
 * @author     Romain Deveaud <romain.deveaud@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class Basemeeting_pollForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'               => new sfWidgetFormInputHidden(),
      'date_id'          => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('meeting_date'), 'add_empty' => false)),
      'poll'             => new sfWidgetFormInputText(),
      'uid'              => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('user'), 'add_empty' => true)),
      'comment'          => new sfWidgetFormInputText(),
      'participant_name' => new sfWidgetFormInputText(),
      'created_at'       => new sfWidgetFormDateTime(),
      'updated_at'       => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'               => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'date_id'          => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('meeting_date'))),
      'poll'             => new sfValidatorInteger(),
      'uid'              => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('user'), 'required' => false)),
      'comment'          => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'participant_name' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
      'created_at'       => new sfValidatorDateTime(),
      'updated_at'       => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('meeting_poll[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'meeting_poll';
  }

}
