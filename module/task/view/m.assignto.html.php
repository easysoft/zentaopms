<?php include '../../common/view/m.header.html.php';?>
</div>
<form method='post' target='hiddenwin'>
    <h3><?php echo $lang->task->assignTo . $this->lang->colon . $task->name;?></h3>
    <div data-role="fieldcontain">
      <?php echo '<label for="assignedTo">' . $lang->task->assignedTo. '</label>' . html::select('assignedTo', $users, $task->openedBy);?>
    </div>
    <div data-role="fieldcontain">
      <?php echo '<label for="left">' . $lang->task->left . '(' . $lang->task->hour . ')</label>' . html::input('left', $task->left);?>
    </div>
    <div data-role="fieldcontain">
      <?php echo html::textarea('comment', '', "placeholder='$lang->comment'");?>
    </div>
    <div data-role="fieldcontain">
      <?php echo html::submitButton();?>
    </div>
  </table>
  <?php include '../../common/view/m.action.html.php';?>
</form>
<?php include '../../common/view/m.footer.html.php';?>
