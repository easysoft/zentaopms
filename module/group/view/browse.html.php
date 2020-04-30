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
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->group->browse;?></span></span>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('group', 'managePriv')) echo html::a($this->createLink('group', 'managePriv', 'type=byModule', '', true), $lang->group->managePrivByModule, '', 'class="btn btn-link iframe"');?>
    <?php if(common::hasPriv('group', 'create')) echo html::a($this->createLink('group', 'create', '', '', true), '<i class="icon-plus"></i> ' . $lang->group->create, '', 'class="btn btn-primary iframe" data-width="550"');?>
  </div>
</div>
<div id='mainContent' class='main-table'>
  <table class='table tablesorter' id='groupList'>
    <thead>
      <tr>
        <th class='w-id text-center'><?php echo $lang->group->id;?></th>
        <th class='w-130px'><?php echo $lang->group->name;?></th>
        <th class='w-300px'><?php echo $lang->group->desc;?></th>
        <th><?php echo $lang->group->users;?></th>
        <th class='c-actions-6 text-center'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($groups as $group):?>
      <?php $users = implode(',', $groupUsers[$group->id]);?>
      <tr>
        <td class='text-center'><?php echo $group->id;?></td>
        <td><?php echo $group->name;?></td>
        <td title='<?php echo $group->desc?>'><?php echo $group->desc;?></td>
        <td title='<?php echo $users;?>' class="text-ellipsis"><?php echo $users;?></td>
        <td class='c-actions'>
          <?php $lang->group->managepriv = $lang->group->managePrivByGroup;?>
          <?php common::printIcon('group', 'manageView', "groupID=$group->id", $group, 'list', 'eye');?>
          <?php common::printIcon('group', 'managepriv', "type=byGroup&param=$group->id", $group, 'list', 'lock');?>
          <?php $lang->group->managemember = $lang->group->manageMember;?>
          <?php common::printIcon('group', 'managemember', "groupID=$group->id", $group, 'list', 'persons', '', 'iframe', 'yes', "data-width='90%'");?>
          <?php common::printIcon('group', 'edit', "groupID=$group->id", $group, 'list', '', '', 'iframe', 'yes', "data-width='550'");?>
          <?php common::printIcon('group', 'copy', "groupID=$group->id", $group, 'list', '', '', 'iframe', 'yes', "data-width='550'");?>
          <?php
          if(common::hasPriv('group', 'delete') and $group->role != 'limited')
          {
              $deleteURL = $this->createLink('group', 'delete', "groupID=$group->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"groupList\", confirmDelete)", '<i class="icon icon-trash"></i>', '', "title='{$lang->group->delete}' class='btn'");
          }
          else
          {
              echo "<button class='btn disabled'><i class='icon icon-trash disabled' title='{$lang->group->delete}'></i></button>";
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
