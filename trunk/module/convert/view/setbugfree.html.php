<tr>
  <th class='rowhead w-200px'><?php echo $lang->convert->dbHost;?></th>
  <td><?php echo html::input('dbHost', 'localhost');?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->dbPort;?></th>
  <td><?php echo html::input('dbPort', '3306');?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->dbUser;?></th>
  <td><?php echo html::input('dbUser', 'root');?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->dbPassword;?></th>
  <td><?php echo html::input('dbPassword');?></td>
</tr>
<tr>
  <th class='rowhead'><?php printf($lang->convert->dbName, $source);?></th>
  <td><?php echo html::input('dbName', $dbName);?></td>
</tr>
<!--
<tr>
  <th class='rowhead'><?php printf($lang->convert->dbPrefix, $source);?></th>
  <td><?php echo html::input('dbPrefix', $tablePrefix);?></td>
</tr>
-->
<tr>
  <th class='rowhead'><?php printf($lang->convert->installPath, $source);?></th>
  <td><?php echo html::input('installPath');?></td>
</tr>
<?php echo html::hidden('source', $source) . html::hidden('version', $version);?>
