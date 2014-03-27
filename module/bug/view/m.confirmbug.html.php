<?php include '../../common/view/m.header.html.php';?>
</div>
<form class='form-condensed' method='post' target='hiddenwin'>
  <h3><?php echo "BUG#$bug->id $bug->title";?></h3>
  <table class='table-1'>
    <tr>
      <td class='w-60px'><?php echo $lang->bug->assignedTo;?></td>
      <td><?php echo html::select('assignedTo', $users, $bug->assignedTo);?></td>
    </tr>
    <tr>
      <td class='w-60px'><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment', '');?></td>
    </tr>
    <tr>
      <td class='text-center' colspan='2'>
      <?php echo html::submitButton($lang->bug->buttonConfirm, 'data-inline="true" data-theme="b"');?>
      <?php echo html::linkButton($lang->goback, $this->createLink('bug', 'view', "bugID=$bug->id"), 'self', "data-inline='true'");?>
     </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/m.action.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
