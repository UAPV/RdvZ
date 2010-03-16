<?php

require_once 'propel/engine/builder/om/php5/PHP5ExtensionPeerBuilder.php';

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package    symfony
 * @subpackage propel
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: SfExtensionPeerBuilder.php 23357 2009-10-26 17:29:41Z Kris.Wallsmith $
 * 
 * @deprecated since symfony 1.3
 */
class SfExtensionPeerBuilder extends PHP5ExtensionPeerBuilder
{
  public function build()
  {
    $code = parent::build();
    if (!$this->getBuildProperty('builderAddComments'))
    {
      $code = sfToolkit::stripComments($code);
    }

    return $code;
  }

  /**
   * Adds class phpdoc comment and openning of class.
   *
   * @param string &$script The script will be modified in this method
   */
  protected function addClassOpen(&$script)
  {
    parent::addClassOpen($script);

    // remove comments and fix coding standards
    $script = str_replace(array(" {\n", "\n\n\n"), array("\n{", "\n"), sfToolkit::stripComments($script));
  }

  /**
   * Closes class.
   *
   * @param string &$script The script will be modified in this method
   */
  protected function addClassClose(&$script)
  {
    parent::addClassClose($script);

    // fix coding standards
    $script = preg_replace('#} // .+$#m', '}', $script);
  }
}
