<?php
  function tr ($index, $return=false)
  {
    
    global $translations;
    $translated_string = $index;
    if (isset($translations[$index]))
    {
      
      $translated_string = $translations[$index];
    }
    if ($return)
      return $translated_string;
      
    else
      echo $return.$translations[$index];
     
    return true;
  }
?>