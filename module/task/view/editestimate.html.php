<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<form method='post' target='hiddenwin'>
  <table class='table-1 a-left'> 
    <caption><?php echo $lang->task->editEstimate;?></caption>
    <tr>
      <th class='rowhead'><?php echo $lang->task->date;?></th>
      <td><?php echo html::input('date', $estimate->date, 'class="select-3 date"');?></td>
    </tr>  
    <tr>
      <th class='rowhead'><?php echo $lang->task->consumed;?></th>
      <td><?php echo html::input('consumed', $estimate->consumed, 'class="select-3"');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->task->left;?></th>
      <td><?php echo html::input('left', $estimate->left, 'class="select-3"');?></td>
    </tr>
    <tr>
      <th class='rowhead'><?php echo $lang->comment;?></th>
      <td><?php echo html::textarea('comment', $estimate->comment, "class=text-5");?></td>
    </tr>  
    <tr>
      <td colspan='2' class='a-center'>
        <?php echo html::submitButton() . html::resetButton();?>
      </td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
