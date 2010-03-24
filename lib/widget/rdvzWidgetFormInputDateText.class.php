<?php

class rdvzWidgetFormInputDateText extends sfWidgetFormInputText
{
  private
    $comment_value ;

  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes) ;
    $this->comment_value = "" ;
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $res = parent::render($name, $value, $attributes, $errors) ;
    $res .= ' '.__('Commentaire').' (<em>'.__('facultatif').'</em>) : <input type="text" name="'.str_replace('date','comment',$name).'" value="'.$this->comment_value.'" />' ;
    
    return $res ;
  }

  public function setCommentValue($com_val = null)
  {
    $this->comment_value = $com_val ;
  }
}
