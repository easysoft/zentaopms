<tr class='a-center'>
  <th><?php echo $lang->convert->item?></th>
  <th><?php echo $lang->convert->count?></th>
  <th><?php echo $lang->convert->info?></th>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->users;?></th>
  <td><?php echo $result['users'];?></td>
  <td class='f-12px'><?php if(isset($info['users'])) echo join('<br />', $info['users']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->groups;?></th>
  <td><?php echo $result['groups'];?></td>
  <td class='f-12px'><?php if(isset($info['groups'])) echo join('<br />', $info['groups']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->products;?></th>
  <td><?php echo $result['products'];?></td>
  <td class='f-12px'><?php if(isset($info['products'])) echo join('<br />', $info['products']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->projects;?></th>
  <td><?php echo $result['projects'];?></td>
  <td class='f-12px'><?php if(isset($info['projects'])) echo join('<br />', $info['projects']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->stories;?></th>
  <td><?php echo $result['stories'];?></td>
  <td class='f-12px'><?php if(isset($info['stories'])) echo join('<br />', $info['stories']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->tasks;?></th>
  <td><?php echo $result['tasks'];?></td>
  <td class='f-12px'><?php if(isset($info['tasks'])) echo join('<br />', $info['tasks']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->bugs;?></th>
  <td><?php echo $result['bugs'];?></td>
  <td class='f-12px'><?php if(isset($info['bugs'])) echo join('<br />', $info['bugs']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->productPlans;?></th>
  <td><?php echo $result['productPlans'];?></td>
  <td class='f-12px'><?php if(isset($info['productPlans'])) echo join('<br />', $info['productPlans']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->teams;?></th>
  <td><?php echo $result['teams'];?></td>
  <td class='f-12px'><?php if(isset($info['teams'])) echo join('<br />', $info['teams']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->releases;?></th>
  <td><?php echo $result['releases'];?></td>
  <td class='f-12px'><?php if(isset($info['releases'])) echo join('<br />', $info['releases']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->builds;?></th>
  <td><?php echo $result['builds'];?></td>
  <td class='f-12px'><?php if(isset($info['builds'])) echo join('<br />', $info['builds']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->docLibs;?></th>
  <td><?php echo $result['docLibs'];?></td>
  <td class='f-12px'><?php if(isset($info['docLibs'])) echo join('<br />', $info['docLibs']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->docs;?></th>
  <td><?php echo $result['docs'];?></td>
  <td class='f-12px'><?php if(isset($info['docs'])) echo join('<br />', $info['docs']);?></td>
</tr>
<tr>
  <th class='rowhead'><?php echo $lang->convert->redmine->files;?></th>
  <td><?php echo $result['files'];?></td>
  <td class='f-12px'><?php if(isset($info['files'])) echo join('<br />', $info['files']);?></td>
</tr>
