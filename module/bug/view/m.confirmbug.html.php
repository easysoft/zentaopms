<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin'>
  <div><?php echo $bug->title;?></div>
  <div><?php echo $lang->bug->assignedTo . html::select('assignedTo', $users, $bug->assignedTo, "class='text-3'");?></div>
  <div><?php echo $lang->comment . html::textarea('comment', '', "rows='6' class='area-1'");?></div>
  <?php echo html::submitButton($lang->bug->buttonConfirm, 'data-inline="true" data-theme="b"');?>
  <?php echo html::backButton("data-inline='true'");?>
</form>
<?php include '../../common/view/action.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
