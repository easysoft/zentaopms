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
       $allLeft      = $estimate->left;
  }
  ?>
  <table class='table-1'>
    <tr>
      <td colspan="2"><?php echo $lang->task->consumed . ':' . $allConsumed . $lang->task->hour . ', ' . $lang->task->left . ':' . $allLeft . $lang->task->hour;?></td>
    </tr>
    <tr>
      <td class="w-70px"><?php echo $lang->task->consumedThisTime;?></td>
      <td><?php echo html::input('consumed[1]', '');?></td>
    </tr>
    <tr>
      <td class="w-70px"><?php echo $lang->task->left;?></td>
      <td><?php echo html::input('left[1]', '');?></td>
    </tr>
    <tr>
      <td class='w-70px'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "data-mini='true'");?></td>
    </tr>
    <tr>
      <td><?php echo html::submitButton() . html::hidden('dates[1]', helper::today()) . html::hidden("id[1]", 1); ?></td>
    </tr>
  <table>
  <?php endif;?>
</form>
<?php include '../../common/view/m.footer.html.php';?>
