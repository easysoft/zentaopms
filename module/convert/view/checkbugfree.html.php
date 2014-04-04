<tr>
  <th class='w-100px'><?php echo $lang->convert->checkDB;?></th>
  <td><?php is_object($checkInfo['db']) ? printf($lang->convert->ok) : printf($lang->convert->fail);?></td>
</tr>
<!--
<tr>
  <th><?php echo $lang->convert->checkTable;?></th>
  <td>
    <?php
    if(is_object($checkInfo['db'])) $checkInfo['table'] === true ? printf($lang->convert->ok) : printf($lang->convert->fail);
    ?>
  </td>
</tr>
-->
<tr>
  <th><?php echo $lang->convert->checkPath;?></th>
  <td><?php $checkInfo['path'] === true ? printf($lang->convert->ok) : printf($lang->convert->fail);?></td>
</tr>
<tfoot>
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
</tfoot>
