<?php
/**
 * The browse view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     group
 * @version     $Id: browse.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmDelete', $lang->group->confirmDelete);?>
<div id='titlebar'>
  <div class='heading'><?php echo html::icon($lang->icons['group']);?> <?php echo $lang->group->browse;?></div>
  <div class='actions'>
    <?php common::printIcon('group', 'create', '', '', 'button', '', '', 'iframe', true, "data-width='550'");?>
    <?php if(common::hasPriv('group', 'managePriv')):?>
    <?php echo html::a($this->createLink('group', 'managePriv', 'type=byModule', '', true), $lang->group->managePrivByModule, '', 'class="btn btn-primary iframe"');?>
    <?php endif;?>
  </div>
</div>
<table align='center' class='table table-condensed table-hover table-striped  tablesorter table-fixed' id='groupList'>
  <thead>
    <tr>
     <th class='w-id'><?php echo $lang->group->id;?></th>
     <th class='w-100px'><?php echo $lang->group->name;?></th>
     <th class='w-250px'><?php echo $lang->group->desc;?></th>
     <th><?php echo $lang->group->users;?></th>
     <th class='w-150px {sorter:false}'><?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <tbody>
  <?php foreach($groups as $group):?>
  <?php $users = implode(' ', $groupUsers[$group->id]);?>
  <tr class='text-center'>
    <td class='strong'><?php echo $group->id;?></td>
    <td class='text-left'><?php echo $group->name;?></td>
    <td class='text-left' title='<?php echo $group->desc?>'><?php echo $group->desc;?></td>
    <td class='text-left' title='<?php echo $users;?>'><?php echo $users;?></td>
    <td class='text-center'>
      <?php $lang->group->managepriv = $lang->group->managePrivByGroup;?>
      <?php common::printIcon('group', 'manageView', "groupID=$group->id", $group, 'list', 'eye-open');?>
      <?php common::printIcon('group', 'managepriv', "type=byGroup&param=$group->id", $group, 'list', 'lock');?>
      <?php $lang->group->managemember = $lang->group->manageMember;?>
      <?php common::printIcon('group', 'managemember', "groupID=$group->id", $group, 'list', 'group', '', 'iframe', 'yes');?>
      <?php common::printIcon('group', 'edit', "groupID=$group->id", $group, 'list', '', '', 'iframe', 'yes', "data-width='550'");?>
      <?php common::printIcon('group', 'copy', "groupID=$group->id", $group, 'list', '', '', 'iframe', 'yes', "data-width='550'");?>
      <?php
      if(common::hasPriv('group', 'delete') and $group->role != 'limited')
      {
          $deleteURL = $this->createLink('group', 'delete', "groupID=$group->id&confirm=yes");
          echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"groupList\", confirmDelete)", '<i class="icon-remove"></i>', '', "title='{$lang->group->delete}' class='btn-icon'");
      }
      else
      {
          echo "<button class='btn-icon disabled'><i class='icon-remove disabled' title='{$lang->group->delete}'></i></button>";
      }
      ?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>
<?php include '../../common/view/footer.html.php';?>
