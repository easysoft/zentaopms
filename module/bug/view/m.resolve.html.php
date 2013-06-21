<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin'>
  <div><?php echo $bug->title;?></div>
  <div><?php unset($lang->bug->resolutionList['tostory']); echo $lang->bug->resolution . html::select('resolution', $lang->bug->resolutionList, '', 'class=select-3 onchange=setDuplicate(this.value)');?></div>
  <div id='duplicateBugBox' style='display:none'>
    <div><?php echo $lang->bug->duplicateBug . html::input('duplicateBug', '', 'class=text-3');?></div>
  </div>

  <div><?php echo $lang->bug->resolvedBuild . html::select('resolvedBuild', $builds, '', 'class=select-3');?></div>
  <div><?php echo $lang->bug->resolvedDate . html::input('resolvedDate', helper::now(), "class='select-3'");?></div>
  <div><?php echo $lang->bug->assignedTo . html::select('assignedTo', $users, $bug->openedBy, 'class=select-3');?></div>
  <div><?php echo html::textarea('comment', '', "rows='6' class='area-1' placeholder='$lang->comment'");?></div>
  <div class='a-center'>
  <?php echo html::submitButton('', 'data-inline="true" data-theme="b"');?>
  <?php echo html::backButton("data-inline='true'");?>
  </div>
</form>
<?php include '../../common/view/action.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
