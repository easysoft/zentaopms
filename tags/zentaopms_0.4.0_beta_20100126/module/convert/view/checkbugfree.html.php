<tr>
  <th><?php echo $lang->convert->dbHost;?></th>
  <td><?php echo html::input('dbHost', 'localhost');?></td>
</tr>
<?php echo html::hidden('source', $source) . html::hidden('version', $version);?>
