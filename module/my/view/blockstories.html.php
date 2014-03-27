<div class='panel panel-block'>
  <div class='panel-heading'>
    <i class='icon-lightbulb'></i> <strong><?php echo $lang->my->story;?></strong>
    <div class='panel-actions pull-right'>
      <?php echo html::a($this->createLink('my', 'story'), $lang->more . "&nbsp;<i class='icon icon-double-angle-right'></i>");?>
    </div>
  </div>
  <table class='table table-condensed table-hover table-striped table-borderless'>
  <?php 
  foreach($stories as $story)
  {
      echo "<tr><td class='nobr'>" . "#$story->id " . html::a($this->createLink('story', 'view', "id=$story->id"), $story->title, '', "title=$story->title") . "</td><td width='5'></td></tr>";
  }
  ?>
  </table>
</div>
