<div class='block linkbox2'>
<table class='table-1 fixed colored'>
  <caption>
    <div class='f-left'><span class='icon-story'>&nbsp;</span> <?php echo $lang->my->story;?></div>
    <div class='f-right'><?php echo html::a($this->createLink('my', 'story'), $lang->more . "<span class='icon-more'></span>");?></div>
  </caption>
  <?php 
  foreach($stories as $story)
  {
      echo "<tr><td class='nobr'>" . "#$story->id " . html::a($this->createLink('story', 'view', "id=$story->id"), $story->title) . "</td><td width='5'></td></tr>";
  }
  ?>
</table>
</div>
