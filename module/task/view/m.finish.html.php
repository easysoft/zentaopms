<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin' onsubmit='return checkLeft();'>
    <h3><?php echo $lang->task->finish . $this->lang->colon . $task->name;?></h3>
    <div data-role="fieldcontain">
      <?php echo '<label for="consumed">' . $lang->task->consumed . '(' . $lang->task->hour . ')</label>' . html::input('consumed', $task->consumed);?>
    </div>
    <div data-role="fieldcontain">
      <?php echo '<label for="assignedTo">' . $lang->task->assignedTo. '</label>' . html::select('assignedTo', $users, $task->openedBy);?>
    </div>
    <div data-role="fieldcontain">
      <?php echo html::textarea('comment', '', "placeholder='$lang->comment'");?>
    </div>
    <div data-role="fieldcontain">
      <?php echo html::submitButton() .html::hidden('finishedDate', $date); ?>
    </div>
  </table>
  <?php include '../../common/view/m.action.html.php';?>
</form>
<?php include '../../common/view/m.footer.html.php';?>
