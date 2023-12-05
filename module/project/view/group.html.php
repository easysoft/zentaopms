<?php
/**
 * The prigroup view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: prigroup.html.php 4769 2013-05-05 07:24:21Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/tablesorter.html.php';?>
<?php js::set('confirmDelete', $lang->group->confirmDelete);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php // echo html::backButton($lang->goback, '', 'btn-secondary');?>
    <span class='btn btn-link btn-active-text'><span class='text'><?php echo $lang->group->browse;?></span></span>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php if(common::hasPriv('project', 'createGroup')) echo html::a($this->createLink('project', 'createGroup', "projectID=$projectID&projectID=$projectID", '', true), '<i class="icon-plus"></i> ' . $lang->group->create, '', 'class="btn btn-primary iframe" data-width="550"');?>
  </div>
</div>
<div id='mainContent' class='main-table'>
  <?php if(empty($groups)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->group->noGroup;?></span>
    </p>
  </div>
  <?php else:?>
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
        <td title='<?php echo $users;?>'><?php echo $users;?></td>
        <td class='c-actions'>
          <?php $lang->group->managepriv = $lang->group->managePrivByGroup;?>
          <?php $disabled = $group->role == 'limited' ? 'disabled' : '';?>
          <?php common::printIcon('project', 'managePriv', "projectID=$projectID&type=byGroup&param=$group->id", $group, 'list', 'lock', '', '', '', "data-app='project'");?>
          <?php $lang->group->managemember = $lang->group->manageMember;?>
          <?php common::printIcon('project', 'manageGroupMember', "groupID=$group->id", $group, 'list', 'persons', '', 'iframe', 'yes', "data-width='90%'");?>
          <?php common::printIcon('project', 'editGroup', "groupID=$group->id", $group, 'list', 'edit', '', 'iframe', 'yes', "data-width='550'");?>
          <?php common::printIcon('project', 'copyGroup', "groupID=$group->id", $group, 'list', 'copy', '', "iframe $disabled", 'yes', "data-width='550'");?>
          <?php
          if(common::hasPriv('group', 'delete') and $group->role != 'limited')
          {
              $deleteURL     = $this->createLink('group', 'delete', "groupID=$group->id&confirm=yes");
              $confirmDelete = htmlspecialchars(sprintf($lang->group->confirmDelete, $group->name));
              echo html::a("###", '<i class="icon icon-trash"></i>', '', "onclick='ajaxDelete(\"$deleteURL\", \"groupList\", \"$confirmDelete\")' title='{$lang->group->delete}' class='btn btn-icon'");
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
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
