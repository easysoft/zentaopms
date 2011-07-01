<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<form method='post'>
  <table align='center' class='table-5 f-14px'>
    <caption><?php echo $lang->convert->direction;?></caption>
    <tr>
      <th class='w-p20'><?php echo $lang->convert->questionTypeOfRedmine;?></th>
      <th><?php echo $lang->convert->aimTypeOfZentao;?></th>
    </tr>
    <?php foreach($trackers as $tracker):?>
    <tr>
      <th class='rowhead'><?php echo $tracker->name;?></th>
      <td><?php html::select("$tracker->name", $lang->convert->directionList, '', class='select-3');?></td>
    </tr>
    <?php endforeach;?>
    <tr>
      <td colspan='2' class='a-center'><?php echo html::submitButton();?></td>
    </tr>
  </table>
</form>
<?php include '../../common/view/footer.html.php';?>
