<?php
/**
 * The browse view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<table align='center' class='table-1 tablesorter'>
  <caption class='caption-tl pb-10px'>
    <div class='f-left'><?php echo $lang->group->browse;?></div>
    <div class='f-right'><?php common::printIcon('group', 'create');?></div>
  </caption>
  <thead>
  <tr class='colhead'>
   <th class='w-id'><?php echo $lang->group->id;?></th>
   <th class='w-100px'><?php echo $lang->group->name;?></th>
   <th><?php echo $lang->group->desc;?></th>
   <th class='w-p60'><?php echo $lang->group->users;?></th>
   <th class='w-120px {sorter:false}'><?php echo $lang->actions;?></th>
  </tr>
  </thead>
  <tbody>
  <?php foreach($groups as $group):?>
  <tr class='a-center'>
    <td class='strong'><?php echo $group->id;?></td>
    <td class='a-left'><?php echo $group->name;?></td>
    <td class='a-left'><?php echo $group->desc;?></td>
    <td class='a-left'>
    <?php 
    $groupUsers[$group->id] = array_slice($groupUsers[$group->id], 0, $this->config->group->showUsers);
    foreach($groupUsers[$group->id] as $user) echo $user . ' ';
    ?>
    </td>
    <td class='a-center'>
      <?php $lang->group->managepriv = $lang->group->managePrivByGroup;?>
      <?php common::printIcon('group', 'managepriv',   "type=byGroup&param=$group->id", '', 'list');?>
      <?php $lang->group->managemember = $lang->group->manageMember;?>
      <?php common::printIcon('group', 'managemember', "groupID=$group->id", '', 'list');?>
      <?php common::printIcon('group', 'edit',         "groupID=$group->id", '', 'list');?>
      <?php common::printIcon('group', 'copy',         "groupID=$group->id", '', 'list');?>
      <?php common::printIcon('group', 'delete',       "groupID=$group->id", '', 'list', '', "hiddenwin");?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
  <tfoot>
  <tr><td colspan='5' class='a-center'><?php echo html::linkButton($lang->group->managePrivByModule, inlink('managePriv', 'type=byModule'));?></td></tr>
  </tfoot>
</table>
<?php include '../../common/view/footer.html.php';?>
