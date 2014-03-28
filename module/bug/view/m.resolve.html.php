<?php include '../../common/view/m.header.html.php';?>
</div>
<form class='form-condensed' method='post' target='hiddenwin'>
  <h3><?php echo "BUG#$bug->id $bug->title";?></h3>
  <table class='table-1'>
    <tr>
      <td class='w-80px'><?php unset($lang->bug->resolutionList['tostory']); echo $lang->bug->resolution ;?></td>
      <td><?php echo html::select('resolution', $lang->bug->resolutionList, '', 'class=form-control onchange=setDuplicate(this.value)');?></div>
    </tr>
    <tr id='duplicateBugBox' style='display:none'>
      <td class='w-80px'><?php echo $lang->bug->duplicateBug;?></td>
      <td><?php echo $lang->bug->duplicateBug . html::input('duplicateBug');?></div>
    </tr>
    <tr>
      <td class='w-80px'><?php echo $lang->bug->resolvedBuild?></td>
      <td><?php echo html::select('resolvedBuild', $builds);?></td>
    </tr>
    <tr>
      <td><?php echo $lang->bug->resolvedDate?></div>
      <td><?php echo html::input('resolvedDate', helper::now());?></div>
    </tr>
    <tr>
      <td><?php echo $lang->bug->assignedTo?></div>
      <td><?php echo html::select('assignedTo', $users, $bug->openedBy);?></div>
    </tr>
    <tr>
      <td><?php echo $lang->comment;?></td>
      <td><?php echo html::textarea('comment');?></td>
    </tr>
    <tr>
      <td class='text-center' colspan='2'>
      <?php echo html::submitButton('', 'data-inline="true" data-theme="b"');?>
      <?php echo html::linkButton($lang->goback, $this->createLink('bug', 'view', "bugID=$bug->id"), 'self', "data-inline='true'");?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/m.action.html.php';?>
<?php include '../../common/view/m.footer.html.php';?>
