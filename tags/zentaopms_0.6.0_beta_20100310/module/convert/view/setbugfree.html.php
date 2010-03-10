<tr>
  <th class='rowhead w-200px'><?php echo $lang->convert->dbHost;?></th>
  <td><?php echo html::input('dbHost', $config->db->host);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->dbPort;?></th>
  <td><?php echo html::input('dbPort', $config->db->port);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->dbUser;?></th>
  <td><?php echo html::input('dbUser', $config->db->user);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->dbPassword;?></th>
  <td><?php echo html::input('dbPassword', $config->db->password);?></td>
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
