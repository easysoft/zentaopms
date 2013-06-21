<?php include '../../common/view/m.header.html.php';?>
</div>
<?php include '../../common/view/datepicker.html.php';?>
<form method='post' target='hiddenwin' onsubmit='return checkLeft();'>
    <h3><?php echo $lang->task->start . $this->lang->colon . $task->name;?></h3>
    <?php echo $lang->task->consumed . '(' . $lang->task->hour . ')' . html::input('consumed', $task->consumed);?>
    <?php echo $lang->task->left . '(' . $lang->task->hour . ')' . html::input('left', $task->left);?>
    <?php echo html::textarea('comment', '', "placeholder='$lang->comment'");?>
    <?php echo html::submitButton() .html::hidden('realStarted', helper::today()); ?>
  </table>
  <?php include '../../common/view/m.action.html.php';?>
</form>
<?php include '../../common/view/m.footer.html.php';?>
