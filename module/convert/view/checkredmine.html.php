<tr>
  <th class='w-100px'><?php echo $lang->convert->checkDB;?></th>
  <td><?php is_object($checkInfo['db']) ? printf($lang->convert->ok) : printf($lang->convert->fail);?></td>
</tr>
<tr>
  <th><?php echo $lang->convert->checkPath;?></th>
  <td><?php $checkInfo['path'] === true ? printf($lang->convert->ok) : printf($lang->convert->fail);?></td>
</tr>
<tr>
<td colspan='2' style='padding:0px'>
<div class='panel'>
<div class='panel-heading'>
  <strong><?php echo $lang->convert->setParam; ?></strong>
</div>
<table class='table table-borderless table-data'>
<tr>
  <td>
    <table>
      <caption><?php echo $lang->convert->aimType; ?></caption>
      <tr>
        <th> <?php echo $lang->convert->issue->redmine; ?> </th>
        <th> <?php echo $lang->convert->issue->goto; ?> </th>
        <th> <?php echo $lang->convert->issue->zentao; ?> </th>
      </tr>
      <?php foreach($trackers as $id => $tracker):?>
      <tr>
        <td> <?php echo $tracker->name?> </td>
        <td> <?php echo "=>" ?> </td>
        <td> <?php echo html::select("aimTypes[$id]", $aimTypeList);?> </td>
      </tr>
      <?php endforeach;?>
    </table>
  </td>
  <tr>
    <td>
      <table>
      <caption><?php echo $lang->convert->statusType->bug; ?></caption>
      <tr>
        <th> <?php echo $lang->convert->issue->redmine; ?> </th>
        <th> <?php echo $lang->convert->issue->goto; ?> </th>
        <th> <?php echo $lang->convert->issue->zentao; ?> </th>
      </tr>
      <?php foreach($statuses as $id => $status):?>
      <tr>
        <td> <?php echo $status->name?> </td>
        <td> <?php echo "=>" ?> </td>
        <td> <?php echo html::select("statusTypesOfBug[$id]", $lang->bug->statusList);?> </td>
      </tr>
      <?php endforeach;?>
      </table>
    </td>
    <td>
      <table>
      <caption><?php echo $lang->convert->statusType->story; ?></caption>
      <tr>
        <th> <?php echo $lang->convert->issue->redmine; ?> </th>
        <th> <?php echo $lang->convert->issue->goto; ?> </th>
        <th> <?php echo $lang->convert->issue->zentao; ?> </th>
      </tr>
      <?php foreach($statuses as $id => $status):?>
      <tr>
        <td> <?php echo $status->name?> </td>
        <td> <?php echo "=>" ?> </td>
        <td> <?php echo html::select("statusTypesOfStory[$id]", $lang->story->statusList);?> </td>
      </tr>
      <?php endforeach;?>
      </table>
    </td>
    <td>
      <table>
      <caption><?php echo $lang->convert->statusType->task; ?></caption>
      <tr>
        <th> <?php echo $lang->convert->issue->redmine; ?> </th>
        <th> <?php echo $lang->convert->issue->goto; ?> </th>
        <th> <?php echo $lang->convert->issue->zentao; ?> </th>
      </tr>
      <?php foreach($statuses as $id => $status):?>
      <tr>
        <td> <?php echo $status->name?> </td>
        <td> <?php echo "=>" ?> </td>
        <td> <?php echo html::select("statusTypesOfTask[$id]", $lang->task->statusList);?> </td>
      </tr>
      <?php endforeach;?>
      </table>
    </td>
  </tr>
  <tr>
    <td>
      <table>
      <caption><?php echo $lang->convert->priType->bug; ?></caption>
      <tr>
        <th> <?php echo $lang->convert->issue->redmine; ?> </th>
        <th> <?php echo $lang->convert->issue->goto; ?> </th>
        <th> <?php echo $lang->convert->issue->zentao; ?> </th>
      </tr>
      <?php foreach($pries as $id => $pri):?>
      <tr>
        <td> <?php echo $pri->name?> </td>
        <td> <?php echo "=>" ?> </td>
        <td> <?php echo html::select("priTypesOfBug[$id]", $lang->bug->priList);?> </td>
      </tr>
      <?php endforeach;?>
      </table>
    </td>
    <td>
      <table>
      <caption><?php echo $lang->convert->priType->story; ?></caption>
      <tr>
        <th> <?php echo $lang->convert->issue->redmine; ?> </th>
        <th> <?php echo $lang->convert->issue->goto; ?> </th>
        <th> <?php echo $lang->convert->issue->zentao; ?> </th>
      </tr>
      <?php foreach($pries as $id => $pri):?>
      <tr>
        <td> <?php echo $pri->name?> </td>
        <td> <?php echo "=>" ?> </td>
        <td> <?php echo html::select("priTypesOfStory[$id]", $lang->story->priList);?> </td>
      </tr>
      <?php endforeach;?>
      </table>
    </td>
    <td>
      <table>
      <caption><?php echo $lang->convert->priType->task; ?></caption>
      <tr>
        <th> <?php echo $lang->convert->issue->redmine; ?> </th>
        <th> <?php echo $lang->convert->issue->goto; ?> </th>
        <th> <?php echo $lang->convert->issue->zentao; ?> </th>
      </tr>
      <?php foreach($pries as $id => $pri):?>
      <tr>
        <td> <?php echo $pri->name?> </td>
        <td> <?php echo "=>" ?> </td>
        <td> <?php echo html::select("priTypesOfTask[$id]", $lang->task->priList);?> </td>
      </tr>
      <?php endforeach;?>
      </table>
    </td>
  </tr>
</tr>
</table>
</div>
</td>
</tr>
<tr>
  <td colspan='2' class='text-center'>
    <?php
    if($result == 'pass')
    {
        echo html::hidden('source', $source) . html::hidden('version', $version) . html::submitButton($lang->convert->execute);
    }
    else
    {
        echo html::commonButton($lang->goback, 'onclick=history.back();');
    }
?>
  </td>
</tr>
