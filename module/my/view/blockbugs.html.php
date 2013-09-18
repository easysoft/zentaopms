<div class='block linkbox2'>
<table class='table-1 fixed colored'>
  <caption>
    <div class='f-left'><i class='icon icon-bug'></i>&nbsp; <?php echo $lang->my->bug;?></div>
    <div class='f-right'><?php echo html::a($this->createLink('my', 'bug'), $lang->more . "&nbsp;<i class='icon-th icon icon-double-angle-right'></i>");?></div>
  </caption>
  <?php 
  foreach($bugs as $bugID => $bugTitle)
  {
      echo "<tr><td class='nobr'>" . "#$bugID " . html::a($this->createLink('bug', 'view', "id=$bugID"), $bugTitle, '', "title=$bugTitle") . "</td><td width='5'></td></tr>";
  }
  ?>
</table>
</div>
