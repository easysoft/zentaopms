<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin' onsubmit='return checkLeft();'>
  <h3><?php echo $lang->task->close . $this->lang->colon . $task->name;?></h3>

  <table class='table-1'>
    <tr>
      <td class='w-70px'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '', "data-mini='true'");?></td>
    </tr>
    <tr class='a-center'>
      <td colspan="2"><?php echo html::submitButton('', 'data-inline="true" data-theme="b"');?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/m.action.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
