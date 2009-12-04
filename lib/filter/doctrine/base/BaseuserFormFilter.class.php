<?php

/**
 * user filter form base class.
 *
 * @package    rdvz
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id$
 */
class BaseuserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'login'   => new sfWidgetFormFilterInput(),
      'pass'    => new sfWidgetFormFilterInput(),
      'name'    => new sfWidgetFormFilterInput(),
      'surname' => new sfWidgetFormFilterInput(),
      'mail'    => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'login'   => new sfValidatorPass(array('required' => false)),
      'pass'    => new sfValidatorPass(array('required' => false)),
      'name'    => new sfValidatorPass(array('required' => false)),
      'surname' => new sfValidatorPass(array('required' => false)),
      'mail'    => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'user';
  }

  public function getFields()
  {
    return array(
      'id'      => 'Number',
      'login'   => 'Text',
      'pass'    => 'Text',
      'name'    => 'Text',
      'surname' => 'Text',
      'mail'    => 'Text',
    );
  }
}
