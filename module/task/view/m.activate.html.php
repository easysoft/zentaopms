<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin' onsubmit='return checkLeft();'>
  <h3><?php echo $lang->task->activate . $this->lang->colon . $task->name;?></h3>

  <table class='table-1'>
    <tr>
      <td class="w-60px"><?php echo $lang->task->assignedTo;?></td> 
      <td><?php echo html::select('assignedTo', $users, $task->openedBy);?>
    </tr>
    <tr>
      <td class="w-60px"><?php echo $lang->task->left;?></td>
      <td><?php echo html::input('left', '');?>
    </div>
    <tr>
      <td class='w-60px'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "data-mini='true'");?></td>
    </tr>
  </table>
  <div class="a-center">
    <?php echo html::submitButton('', 'data-inline="true" data-theme="b"');?>
  </div>
</form>
<?php include '../../common/view/m.action.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
