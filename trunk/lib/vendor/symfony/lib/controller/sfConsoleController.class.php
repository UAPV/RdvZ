<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * @package    symfony
 * @subpackage controller
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfConsoleController.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfConsoleController extends sfController
{
  /**
   * Dispatches a request.
   *
   * @param string $moduleName A module name
   * @param string $actionName An action name
   * @param array  $parameters An associative array of parameters to be set
   *
   * @throws sfException
   */
  public function dispatch($moduleName, $actionName, $parameters = array())
  {
    try
    {
      // set parameters
      $this->context->getRequest()->getParameterHolder()->add($parameters);

      // make the first request
      $this->forward($moduleName, $actionName);
    }
    catch (sfException $e)
    {
      $e->printStackTrace();
    }
    catch (Exception $e)
    {
      sfException::createFromException($e)->printStackTrace();
    }
  }
}
