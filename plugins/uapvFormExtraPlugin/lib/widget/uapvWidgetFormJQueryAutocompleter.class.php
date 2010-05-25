<?php

/**
  * TODO: doc
  *
  */

class uapvWidgetFormJQueryAutocompleter extends sfWidgetFormJQueryAutocompleter
{
  protected function configure($options = array(), $attributes = array())
  {
    $this->addRequiredOption('search_attr');

    parent::configure($options, $attributes) ;
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $visibleValue = $this->getOption('value_callback') ? call_user_func($this->getOption('value_callback'), $value) : $value;

    return $this->renderTag('input', array('type' => 'hidden', 'name' => $name, 'value' => $value)).
           sfWidgetFormInput::render('autocomplete_'.$name, $visibleValue, $attributes, $errors).
           sprintf(<<<EOF
<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery("#%s")
    .autocomplete('%s', jQuery.extend({}, {
      dataType: 'json',
      parse:    function(data) {
        var parsed = [];
        for (key in data) {
          parsed[parsed.length] = { data: [ data[key], key ], value: data[key], result: key };
        }
        return parsed;
      }
    }, %s, {extraParams : { attr : "%s" } }))
    .result(function(event, data) { jQuery("#%s").val(data[1]); });
  });
</script>
EOF
      ,
      $this->generateId('autocomplete_'.$name),
      $this->getOption('url'),
      $this->getOption('config'),
      $this->getOption('search_attr'),
      $this->generateId($name)
    );
  }

  public function getStylesheets()
  {
    return array('/uapvFormExtraPlugin/css/JqueryAutocomplete.css' => 'all', '/uapvFormExtraPlugin/css/ac_list.css' => 'all') ;
  }

  public function getJavaScripts()
  {
    return array('/uapvFormExtraPlugin/js/jquery.autocomplete.min.js') ;
  }
}
