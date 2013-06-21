<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' enctype='multipart/form-data' target='hiddenwin'>
  <div><?php echo $bug->title;?></caption>
  <div><?php echo $lang->bug->assignedTo . html::select('assignedTo', $users, $bug->resolvedBy, 'class=select-3');?></div>
  <div><?php echo $lang->bug->openedBuild . html::select('openedBuild[]', $builds, $bug->openedBuild, 'size=4 multiple=multiple class=select-3');?></div>
  <div><?php echo html::textarea('comment', '', "rows='6' class='area-1' placeholder=$lang->comment");?></div>
  <div class='a-center'>
  <?php echo html::submitButton($lang->bug->buttonConfirm, 'data-inline="true" data-theme="b"');?>
  <?php echo html::backButton("data-inline='true'");?>
  </div>
</form>
<?php include '../../common/view/action.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
