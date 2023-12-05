<?php
/**
 * The browse view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <?php echo $this->gitlab->getGitlabMenu($gitlabID, 'group');?>
  <div class="btn-toolbar pull-left">
    <form id='gitlabForm' method='post' class="not-watch">
      <?php echo html::input('keyword', $keyword, "class='form-control' placeholder='{$lang->gitlab->placeholderSearch}' style='display: inline-block;width:auto;margin:0 10px'");?>
      <a id="gitlabSearch" class="btn btn-primary"><?php echo $lang->gitlab->search?></a>
    </form>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('instance', 'manage')) common::printLink('gitlab', 'createGroup', "gitlabID=$gitlabID", "<i class='icon icon-plus'></i> " . $lang->gitlab->group->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($gitlabGroupList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(common::hasPriv('instance', 'manage')):?>
    <?php echo html::a($this->createLink('gitlab', 'createGroup', "gitlabID=$gitlabID"), "<i class='icon icon-plus'></i> " . $lang->gitlab->group->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabGroupList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "gitlabID=$gitlabID&orderBy=%s";?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->gitlab->group->id);?></th>
          <th class='c-name text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitlab->group->name);?></th>
          <th class='text-left'><?php common::printOrderLink('path', $orderBy, $vars, $lang->gitlab->group->path);?></th>
          <th class='text-left'><?php echo $lang->gitlab->group->createOn;?></th>
          <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabGroupList as $id => $gitlabGroup): ?>
        <tr class='text'>
          <td class='text'><?php echo $gitlabGroup->id;?></td>
          <td class='text-c-name' title='<?php echo $gitlabGroup->full_name;?>'>
            <?php $groupName = current(common::convert2Pinyin(array($gitlabGroup->name))); ?>
            <?php echo html::avatar(array('avatar' => $gitlabGroup->avatar_url, 'account' => $groupName), 20); ?>
            <span><?php echo $gitlabGroup->full_name;?></span>
          </td>
          <td class='text' title='<?php echo $gitlabGroup->full_path;?>'><?php echo html::a($gitlab->url . '/' . $gitlabGroup->full_path, $gitlabGroup->full_path, '_target');?></td>
          <td class='text' title='<?php echo substr($gitlabGroup->created_at, 0, 10);?>'><?php echo substr($gitlabGroup->created_at, 0, 10);?></td>
          <td class='c-actions text-left'>
            <?php
            $isAdmin = ($app->user->admin or in_array($gitlabGroup->id, $adminGroupIDList)) ? true : false;
            if($this->gitlab->isDisplay($gitlab, 'manageGroupMembers')) common::printLink('gitlab', 'manageGroupMembers', "gitlabID=$gitlabID&groupID=$gitlabGroup->id", "<i class='icon icon-team'></i> ", '',"title='{$lang->gitlab->group->manageMembers}' class='btn'");
            if($this->gitlab->isDisplay($gitlab, 'editGroup')) echo common::buildIconButton('gitlab', 'editGroup', "gitlabID=$gitlabID&groupID=$gitlabGroup->id", '', 'list', 'edit', '', '', false, '', '', 0, $isAdmin);
            if($this->gitlab->isDisplay($gitlab, 'deleteGroup')) echo common::buildIconButton('gitlab', 'deleteGroup', "gitlabID=$gitlabID&groupID=$gitlabGroup->id", '', 'list', 'trash', 'hiddenwin', '', false, '', '', 0, $isAdmin);
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
