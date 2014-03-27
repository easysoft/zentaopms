<tr>
  <th class='rowhead w-200px'><?php echo $lang->convert->dbHost;?></th>
  <td><?php echo html::input('dbHost', $config->db->host, "class='form-control'");?></td>
</tr>
<tr>
  <th><?php echo $lang->convert->dbPort;?></th>
  <td><?php echo html::input('dbPort', $config->db->port, "class='form-control'");?></td>
</tr>
<tr>
  <th><?php echo $lang->convert->dbUser;?></th>
  <td><?php echo html::input('dbUser', $config->db->user, "class='form-control'");?></td>
</tr>
<tr>
  <th><?php echo $lang->convert->dbPassword;?></th>
  <td><?php echo html::input('dbPassword', $config->db->password, "class='form-control'");?></td>
</tr>
<tr>
  <th><?php printf($lang->convert->dbName, $source);?></th>
  <td><?php echo html::input('dbName', $dbName, "class='form-control'");?></td>
</tr>
<tr>
  <th><?php printf($lang->convert->dbCharset, $source);?></th>
  <td><?php echo html::input('dbCharset', $dbCharset, "class='form-control'");?></td>
</tr>
<?php if($version > 1):?>
<tr>
  <th><?php printf($lang->convert->dbPrefix, $source);?></th>
  <td><?php echo html::input('dbPrefix', $tablePrefix, "class='form-control'");?></td>
</tr>
<?php endif;?>
<tr>
  <th><?php printf($lang->convert->installPath, $source);?></th>
  <td><?php echo html::input('installPath', '', "class='form-control'");?></td>
</tr>
