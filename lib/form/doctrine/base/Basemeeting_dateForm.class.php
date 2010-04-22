<?php

/**
 * meeting_date form base class.
 *
 * @method meeting_date getObject() Returns the current form's model object
 *
 * @package    rdvz
 * @subpackage form
 * @author     Romain Deveaud <romain.deveaud@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class Basemeeting_dateForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'      => new sfWidgetFormInputHidden(),
      'mid'     => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('meeting'), 'add_empty' => false)),
      'date'    => new sfWidgetFormDateTime(),
      'comment' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'mid'     => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('meeting'))),
      'date'    => new sfValidatorDateTime(),
      'comment' => new sfValidatorString(array('max_length' => 255, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('meeting_date[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'meeting_date';
  }

}
