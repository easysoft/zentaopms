<?php
/**
 * The browse view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Liu <liugang@cnezsoft.com>
 * @package     gitlab
 * @version     $Id$
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('vars', "keyword=%s&orderBy=id_desc&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID=1")?>
<?php js::set('gitlabID', $gitlabID)?>
<div id="mainMenu" class="clearfix">
  <?php echo $this->gitlab->getGitlabMenu($gitlabID, 'project');?>
  <div class="btn-toolbar pull-left">
    <form id='gitlabForm' method='post' class="not-watch">
      <?php echo html::input('keyword', $keyword, "class='form-control' placeholder='{$lang->gitlab->placeholderSearch}' style='display: inline-block;width:auto;margin:0 10px'");?>
      <a id="gitlabSearch" class="btn btn-primary"><?php echo $lang->gitlab->search?></a>
    </form>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('instance', 'manage')) common::printLink('gitlab', 'createProject', "gitlabID=$gitlabID", "<i class='icon icon-plus'></i> " . $lang->gitlab->project->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($gitlabProjectList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(empty($keyword) and common::hasPriv('instance', 'manage')):?>
    <?php echo html::a($this->createLink('gitlab', 'createProject', "gitlabID=$gitlabID"), "<i class='icon icon-plus'></i> " . $lang->gitlab->project->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabProjectList' class='table has-sort-head table-fixed'>
      <?php $vars = "gitlabID={$gitlabID}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->gitlab->id);?></th>
          <th class='c-name'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitlab->project->name);?></th>
          <th class='text'></th>
          <th class='text'><?php echo $lang->gitlab->lastUpdate;?></th>
          <th class='c-actions-9 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabProjectList as $id => $gitlabProject): ?>
        <tr class='text'>
          <td class='text'><?php echo $gitlabProject->id;?></td>
          <td class='text-c-name' title='<?php echo $gitlabProject->name;?>'><?php echo $gitlabProject->name_with_namespace;?></td>
          <td class='text text-c-counts'>
            <span title="<?php echo $lang->gitlab->project->star;?>"><i class="icon icon-star"></i> <?php echo $gitlabProject->star_count;?></span>
            <span title="<?php echo $lang->gitlab->project->fork;?>"><i class="icon icon-code-fork"></i> <?php echo $gitlabProject->forks_count;?></span>
          </td>
          <td class='text' title='<?php echo substr($gitlabProject->last_activity_at, 0, 10);?>'><?php echo substr($gitlabProject->last_activity_at, 0, 10);?></td>
          <td class='c-actions'>
            <?php
            $hasRepoClass    = isset($repoPairs[$gitlabProject->id]) ? '' : 'disabled';
            $maintainerClass = $gitlabProject->isMaintainer          ? '' : 'disabled';
            $developerClass  = $gitlabProject->isDeveloper           ? '' : 'disabled';

            $defaultBranchClass = $gitlabProject->adminer || $gitlabProject->isMaintainer ? '' : 'disabled';
            if($this->gitlab->isDisplay($gitlab, 'browseBranch')) echo common::printIcon('gitlab', 'browseBranch', "gitlabID=$gitlabID&projectID=$gitlabProject->id", '', 'list', 'treemap', '', $developerClass, false, '', $this->lang->gitlab->browseBranch);
            if($this->gitlab->isDisplay($gitlab, 'browseTag')) echo common::printIcon('gitlab', 'browseTag', "gitlabID=$gitlabID&projectID=$gitlabProject->id", '', 'list', 'tag', '', $developerClass, false, '', $this->lang->gitlab->browseTag);
            if($this->gitlab->isDisplay($gitlab, 'manageBranchPriv')) echo common::printIcon('gitlab', 'manageBranchPriv', "gitlabID=$gitlabID&projectID=$gitlabProject->id", '', 'list', 'branch-lock', '', $defaultBranchClass, false, '', $this->lang->gitlab->browseBranchPriv);
            if($this->gitlab->isDisplay($gitlab, 'manageTagPriv')) echo common::printIcon('gitlab', 'manageTagPriv', "gitlabID=$gitlabID&projectID=$gitlabProject->id", '', 'list', 'tag-lock', '', $defaultBranchClass, false, '', $this->lang->gitlab->browseTagPriv);
            if($this->gitlab->isDisplay($gitlab, 'manageProjectMembers')) echo common::printIcon('gitlab', 'manageProjectMembers', 'repoID=' . zget($repoPairs, $gitlabProject->id), '', 'list', 'team', '', $hasRepoClass);
            if($this->gitlab->isDisplay($gitlab, 'createWebhook')) echo common::printIcon('gitlab', 'createWebhook', 'repoID=' . zget($repoPairs, $gitlabProject->id), '', 'list', 'change', 'hiddenwin', $hasRepoClass);
            if($this->gitlab->isDisplay($gitlab, 'importIssue')) echo common::printIcon('gitlab', 'importIssue', "gitlabID=$gitlabID&projectID=$gitlabProject->id", '', 'list', 'link');
            if($this->gitlab->isDisplay($gitlab, 'editProject')) echo common::printIcon('gitlab', 'editProject', "gitlabID=$gitlabID&projectID=$gitlabProject->id", '', 'list', 'edit', '', $defaultBranchClass);
            if($this->gitlab->isDisplay($gitlab, 'deleteProject')) echo common::printIcon('gitlab', 'deleteProject', "gitlabID=$gitlabID&projectID=$gitlabProject->id", '', 'list', 'trash', 'hiddenwin', $defaultBranchClass);
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($gitlabProjectList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs', 100);?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
