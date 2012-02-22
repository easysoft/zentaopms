<div class='block linkbox2'>
<table class='table-1 fixed colored'>
  <caption>
    <div class='f-left'> <?php echo $lang->my->bug;?></div>
    <div class='f-right'><?php echo html::a($this->createLink('my', 'bug'), $lang->more);?></div>
  </caption>
  <?php 
  foreach($bugs as $bugID => $bugTitle)
  {
      echo "<tr><td class='nobr'>" . "#$bugID " . html::a($this->createLink('bug', 'view', "id=$bugID"), $bugTitle) . "</td><td width='5'></td></tr>";
  }
  ?>
</table>
</div>
