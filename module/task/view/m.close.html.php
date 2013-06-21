<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin' onsubmit='return checkLeft();'>
  <h3><?php echo $lang->task->close . $this->lang->colon . $task->name;?></h3>
  <div data-role="fieldcontain">
    <?php echo html::textarea('comment', '', "placeholder='$lang->comment'");?>
  </div>
  <div data-role="fieldcontain">
    <?php echo html::submitButton(); ?>
  </div>
  <?php include '../../common/view/m.action.html.php';?>
</form>
<?php include '../../common/view/m.footer.html.php';?>
