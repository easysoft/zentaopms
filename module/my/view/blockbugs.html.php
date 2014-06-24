<div class='panel panel-block'>
  <div class='panel-heading'>
    <i class='icon icon-bug'></i> <strong><?php echo $lang->my->bug;?></strong>
    <div class='panel-actions pull-right'>
      <?php echo html::a($this->createLink('my', 'bug'), $lang->more . "&nbsp;<i class='icon icon-double-angle-right'></i>");?>
    </div>
  </div>
  <table class='table table-condensed table-hover table-striped table-borderless table-fixed'>
  <?php
  foreach($bugs as $bugID => $bugTitle)
  {
      echo "<tr><td class='nobr'>" . "#$bugID " . html::a($this->createLink('bug', 'view', "id=$bugID"), $bugTitle, '', "title=$bugTitle") . "</td><td width='5'></td></tr>";
  }
  ?>
  </table>
</div>
