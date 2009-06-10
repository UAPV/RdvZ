      <hr/>
      <h2 class="thanks"><?php tr('Thanks'); ?></h2>
      <hr/>
      <p><?php tr('Information saved', array($mid)); ?></p>
<?php
      $adr= "http://".$_SERVER['SERVER_NAME'].ereg_replace("\/[^\/]*\.(html|php)(\?.*)?","",$_SERVER['REQUEST_URI'])."/participation.php?mid=$mid";
?>
      <p><a href="<?php echo $adr; ?>"><?php echo $adr; ?></a></p>
      <p><?php tr('Information saved notice'); ?></p>
      <div class="buttons">
        <form action="index.php">
          <input type="submit" class="next" value="<?php tr('My Meetings'); ?>"/>
        </form>
      </div>
