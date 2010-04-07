<?php

/**
 * meeting form base class.
 *
 * @method meeting getObject() Returns the current form's model object
 *
 * @package    rdvz
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BasemeetingForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'          => new sfWidgetFormInputHidden(),
      'hash'        => new sfWidgetFormInputText(),
      'title'       => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormTextarea(),
      'uid'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('user'), 'add_empty' => true)),
      'closed'      => new sfWidgetFormInputCheckbox(),
      'date_del'    => new sfWidgetFormDateTime(),
      'date_end'    => new sfWidgetFormDateTime(),
      'notif'       => new sfWidgetFormInputCheckbox(),
      'created_at'  => new sfWidgetFormDateTime(),
      'updated_at'  => new sfWidgetFormDateTime(),
    ));

    $this->setValidators(array(
      'id'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'hash'        => new sfValidatorString(array('max_length' => 8)),
      'title'       => new sfValidatorString(array('max_length' => 255)),
      'description' => new sfValidatorString(array('max_length' => 4000)),
      'uid'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('user'), 'required' => false)),
      'closed'      => new sfValidatorBoolean(array('required' => false)),
      'date_del'    => new sfValidatorDateTime(array('required' => false)),
      'date_end'    => new sfValidatorDateTime(array('required' => false)),
      'notif'       => new sfValidatorBoolean(array('required' => false)),
      'created_at'  => new sfValidatorDateTime(),
      'updated_at'  => new sfValidatorDateTime(),
    ));

    $this->widgetSchema->setNameFormat('meeting[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'meeting';
  }

}
