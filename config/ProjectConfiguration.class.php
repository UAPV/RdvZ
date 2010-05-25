<?php

require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfDoctrinePlugin','uapvAuthPlugin','uapvErrorPlugin','uapvFormExtraPlugin');
    $this->enablePlugins('sfFormExtraPlugin');
    $this->enablePlugins('sfJqueryReloadedPlugin');
  }
}
