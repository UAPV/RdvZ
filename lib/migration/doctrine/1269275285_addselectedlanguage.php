<?php

class Addselectedlanguage extends Doctrine_Migration_Base
{
  public function up()
  {
    $this->addColumn('user', 'language', 'string', '8', array('default' => 'fr')) ;
  }

  public function down()
  {
    $this->removeColumn('user', 'language') ;
  }
}
