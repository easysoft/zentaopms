<tr>
  <th class='rowhead'><?php echo $lang->convert->checkDB;?></th>
  <td><?php is_object($checkResult['db']) ? printf($lang->convert->ok) : printf($lang->convert->fail);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->checkTable;?></th>
  <td><?php $checkResult['table'] === true ? printf($lang->convert->ok) : printf($lang->convert->fail);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->checkRoot;?></th>
  <td><?php $checkResult['root'] === true ? printf($lang->convert->ok) : printf($lang->convert->fail);?></td>
</tr>
<tr>
  <td colspan='2' class='a-center'>
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
