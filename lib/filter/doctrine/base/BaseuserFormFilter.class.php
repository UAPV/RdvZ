<?php

/**
 * user filter form base class.
 *
 * @package    rdvz
 * @subpackage filter
 * @author     Romain Deveaud <romain.deveaud@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24051 2009-11-16 21:08:08Z Kris.Wallsmith $
 */
abstract class BaseuserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'ldap_id'  => new sfWidgetFormFilterInput(),
      'login'    => new sfWidgetFormFilterInput(),
      'pass'     => new sfWidgetFormFilterInput(),
      'name'     => new sfWidgetFormFilterInput(),
      'surname'  => new sfWidgetFormFilterInput(),
      'mail'     => new sfWidgetFormFilterInput(),
      'language' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'ldap_id'  => new sfValidatorPass(array('required' => false)),
      'login'    => new sfValidatorPass(array('required' => false)),
      'pass'     => new sfValidatorPass(array('required' => false)),
      'name'     => new sfValidatorPass(array('required' => false)),
      'surname'  => new sfValidatorPass(array('required' => false)),
      'mail'     => new sfValidatorPass(array('required' => false)),
      'language' => new sfValidatorPass(array('required' => false)),
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
      'id'       => 'Number',
      'ldap_id'  => 'Text',
      'login'    => 'Text',
      'pass'     => 'Text',
      'name'     => 'Text',
      'surname'  => 'Text',
      'mail'     => 'Text',
      'language' => 'Text',
    );
  }
}
