<?php
/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
 
/**
 * sfWidgetFormJQueryDate represents a date widget rendered by JQuery UI.
 *
 * This widget needs JQuery and JQuery UI to work.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormJQueryDate.class.php 12875 2008-11-10 12:22:33Z fabien $
 */
class sfWidgetFormJQueryI18nDate extends sfWidgetFormI18nDate
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   *  * image:   The image path to represent the widget (false by default)
   *  * config:  A JavaScript array that configures the JQuery date widget
   *  * culture: The user culture
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetForm
   */ 
 
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('image', $options["image"]);
    $this->addOption('config', $options["config"]);
    $this->addOption('culture', $options["culture"]);
    $this->addOption('years', $options["years"]);
 
    parent::configure($options, $attributes);
 
    if ('en' == $this->getOption('culture'))
    {
      $this->setOption('culture', 'en');
    }
  }
 
  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $prefix = $this->generateId($name);
 
    $image = '';
    if (false !== $this->getOption('image'))
    {
      $image = sprintf(', buttonImage: %s, buttonImageOnly: true', $this->getOption('image'));
    }

    return parent::render($name, $value, $attributes, $errors).
           $this->renderTag('input', array('type' => 'hidden', 'size' => 10, 'id' => $id = $this->generateId($name).'_jquery_control', 'disabled' => 'disabled')).
           sprintf(<<<EOF
<script type="text/javascript">
 
  function %s_read_linked()
  {
    \$("#%s").val(\$("#%s").val() + "/" + \$("#%s").val() + "/" + \$("#%s").val());
 
    return {};
  }
 
  function %s_update_linked(date)
  {
    \$("#%s").val(date.substring(0, 2));
    \$("#%s").val(parseInt(date.substring(3, 5), 10));
    \$("#%s").val(date.substring(6, 10));
  }
 
  jQuery(document).ready(function() {
    \$("#%s").datepicker(\$.extend({}, {
      minDate:    new Date(%s, 1 - 1, 1),
      maxDate:    new Date(%s, 12 - 1, 31),
      onSelect:   %s_update_linked,
      beforeShow: %s_read_linked,    
      %
      showOn:     "both"
      %s
    }, \$.datepicker.regional["%s"]));
  }) ;
 
</script>
EOF
      ,
      $prefix, $id,
      $this->generateId($name.'[day]'), $this->generateId($name.'[month]'), $this->generateId($name.'[year]'),
      $prefix,
      $this->generateId($name.'[day]'), $this->generateId($name.'[month]'), $this->generateId($name.'[year]'),
      $id,
      min($this->getOption('years')), max($this->getOption('years')),
      $prefix, $prefix, $this->getOption('config'), $image, $this->getOption('culture')
    );
  }
}
