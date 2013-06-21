<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin'>
  <h3><?php echo $lang->task->recordEstimate . $this->lang->colon . $task->name;?></h3>
  <?php if($task->status == 'wait' or $task->status == 'doing'):?>
  <?php 
  $allConsumed = 0;
  $allLeft = 0;
  foreach($estimates as $estimate)
  {
       $allConsumed += $estimate->consumed;
       $left         = $estimate->left;
  }
  ?>
  <div data-role="fieldcontain">
    <?php echo $lang->task->consumed . ':' . $allConsumed . $lang->task->hour . ', ' . $lang->task->left . ':' . $left . $lang->task->hour;?>
  </div>
  <div data-role="fieldcontain">
    <?php echo '<label for="consumed">' . $lang->task->consumed . '(' . $lang->task->hour . ')</label>' . html::input('consumed[1]', '');?>
  </div>
  <div data-role="fieldcontain">
    <?php echo '<label for="left">' . $lang->task->left . '(' . $lang->task->hour . ')</label>' . html::input('left[1]', '');?>
  </div>
  <div data-role="fieldcontain">
    <?php echo html::textarea('comment', '', "placeholder='$lang->comment'");?>
  </div>
  <div data-role="fieldcontain">
    <?php echo html::submitButton() . html::hidden('dates[1]', helper::today()) . html::hidden("id[1]", 1); ?>
  </div>
  <?php endif;?>
</form>
<?php include '../../common/view/m.footer.html.php';?>
