<tr>
  <th class='rowhead w-200px'><?php echo $lang->convert->dbHost;?></th>
  <td><?php echo html::input('dbHost', $config->db->host, "class='text-3'");?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->dbPort;?></th>
  <td><?php echo html::input('dbPort', $config->db->port, "class='text-3'");?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->dbUser;?></th>
  <td><?php echo html::input('dbUser', $config->db->user, "class='text-3'");?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->dbPassword;?></th>
  <td><?php echo html::input('dbPassword', $config->db->password, "class='text-3'");?></td>
</tr>
<tr>
  <th class='rowhead'><?php printf($lang->convert->dbName, $source);?></th>
  <td><?php echo html::input('dbName', $dbName, "class='text-3'");?></td>
</tr>
<tr>
  <th class='rowhead'><?php printf($lang->convert->dbCharset, $source);?></th>
  <td><?php echo html::input('dbCharset', $dbCharset, "class='text-3'");?></td>
</tr>
<?php if($version > 1):?>
<tr>
  <th class='rowhead'><?php printf($lang->convert->dbPrefix, $source);?></th>
  <td><?php echo html::input('dbPrefix', $tablePrefix, "class='text-3'");?></td>
</tr>
<?php endif;?>
<tr>
  <th class='rowhead'><?php printf($lang->convert->installPath, $source);?></th>
  <td><?php echo html::input('installPath', '', "class='text-3'");?></td>
</tr>
