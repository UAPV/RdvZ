<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Upgrades project tests.
 *
 * @package    symfony
 * @subpackage task
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfTestsUpgrade.class.php 23205 2009-10-20 13:20:17Z Kris.Wallsmith $
 */
class sfTestsUpgrade extends sfUpgrade
{
  public function upgrade()
  {
    if (file_exists($file = sfConfig::get('sf_test_dir').'/bootstrap/unit.php'))
    {
      $current = file_get_contents($file);

      $new = file_get_contents(sfConfig::get('sf_symfony_lib_dir').'/task/generator/skeleton/project/test/bootstrap/unit.php');
      $old = <<<EOF
<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

\$_test_dir = realpath(dirname(__FILE__).'/..');

require_once(dirname(__FILE__).'/../../config/ProjectConfiguration.class.php');
\$configuration = new ProjectConfiguration(realpath(\$_test_dir.'/..'));
include(\$configuration->getSymfonyLibDir().'/vendor/lime/lime.php');

EOF;

      if ($old == $current)
      {
        $this->logSection('file+', $file);
        file_put_contents($file, $new);
      }
      else if ($new != $current)
      {
        $temp = sfConfig::get('sf_test_dir').'/bootstrap/unit_new.php';

        $this->logSection('tests', 'You must update test/bootstrap/unit.php manually', null, 'ERROR');
        $this->logSection('tests', 'see '.$temp, null, 'ERROR');

        $this->logSection('file+', $temp);
        file_put_contents($temp, $new);
      }
    }
  }
}
