<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin' onsubmit='return checkLeft();'>
  <h3><?php echo $lang->task->finish . $this->lang->colon . $task->name;?></h3>

  <table class='table-1'>
    <tr>
      <td class="w-70px"><?php echo $lang->task->consumed;?></td>
      <td><?php echo html::input('consumed', $task->consumed);?></td>
    </tr>
    <tr>
      <td class="w-70px"><?php echo $lang->task->assignedTo;?></td>
      <td><?php echo html::select('assignedTo', $users, $task->openedBy);?></td>
    </tr>
    <tr>
      <td class='w-70px'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "data-mini='true'");?></td>
    </tr>
    <tr class='a-center'>
      <td colspan="2"><?php echo html::submitButton('', 'data-inline="true" data-theme="b"') .  . html::hidden('finishedDate', $date);?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/m.action.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
