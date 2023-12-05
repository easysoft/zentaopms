<?php
/**
 * The browse view file of group module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
    <?php if(common::hasPriv('group', 'editManagePriv')) echo html::a($this->createLink('group', 'editManagePriv', '', '', false), '<i class="icon icon-backend muted" style="padding-right: 5px"></i>' . $lang->group->editManagePriv, '', 'class="btn btn-link"');?>
    <?php if(common::hasPriv('group', 'managePriv')) echo html::a($this->createLink('group', 'managePriv', 'type=byModule', '', true), $lang->group->managePrivByModule, '', 'class="btn btn-link iframe"');?>
    <?php if(common::hasPriv('group', 'create')) echo html::a($this->createLink('group', 'create', '', '', true), '<i class="icon-plus"></i> ' . $lang->group->create, '', 'class="btn btn-primary iframe" data-width="550"');?>
  </div>
</div>
<div id='mainContent' class='main-table'>
  <table class='table tablesorter' id='groupList'>
    <thead>
      <tr>
        <th class='c-id text-center'><?php echo $lang->group->id;?></th>
        <th class='c-name'><?php echo $lang->group->name;?></th>
        <th class='c-desc'><?php echo $lang->group->desc;?></th>
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
          <?php $lang->group->managemember = $lang->group->manageMember;?>
          <?php $isProjectAdmin = $group->role == 'projectAdmin';?>
          <?php if($isProjectAdmin):?>
          <?php echo "<button class='btn disabled'><i class='icon icon-eye disabled' title='{$lang->group->manageView}'></i></button>";?>
          <?php echo "<button class='btn disabled'><i class='icon icon-lock disabled' title='{$lang->group->managepriv}'></i></button>";?>
          <?php common::printIcon('group', 'manageProjectAdmin', "groupID=$group->id", $group, 'list', 'persons');?>
          <?php echo "<button class='btn disabled'><i class='icon icon-edit disabled' title='{$lang->group->edit}'></i></button>";?>
          <?php echo "<button class='btn disabled'><i class='icon icon-copy disabled' title='{$lang->group->copy}'></i></button>";?>
          <?php else:?>
          <?php common::printIcon('group', 'manageView', "groupID=$group->id", $group, 'list', 'eye');?>
          <?php common::printIcon('group', 'managepriv', "type=byPackage&param=$group->id", $group, 'list', 'lock');?>
          <?php common::printIcon('group', 'manageMember', "groupID=$group->id", $group, 'list', 'persons', '', "iframe", true, "data-width='90%'");?>
          <?php common::printIcon('group', 'edit', "groupID=$group->id", $group, 'list', '', '', "iframe", true, "data-width='550'");?>
          <?php common::printIcon('group', 'copy', "groupID=$group->id", $group, 'list', '', '', "iframe", true, "data-width='550'");?>
          <?php endif;?>
          <?php
          if(common::hasPriv('group', 'delete'))
          {
              if($isProjectAdmin)
              {
                  echo "<a class='btn btn-admin disabled'><i class='icon icon-trash disabled' title='{$lang->group->delete}'></i></a>";
              }
              else
              {
                  $deleteURL     = $this->createLink('group', 'delete', "groupID=$group->id&confirm=yes");
                  $confirmDelete = htmlspecialchars(sprintf($lang->group->confirmDelete, $group->name));
                  echo html::a("javascript:ajaxDelete(\"$deleteURL\", \"groupList\", \"$confirmDelete\")", '<i class="icon icon-trash"></i>', '', "title='{$lang->group->delete}' class='btn'");
              }
          }
          ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
