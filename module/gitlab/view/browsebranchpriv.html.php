<?php
/**
 * The browse view file of gitlab protext branch of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL
 * @author      Yanyi Cao <caoyanyi@easycorp.ltd>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class='pull-left'>
    <?php echo html::a($this->createLink('gitlab', 'browseProject', "gitlabID=$gitlabID"), "<i class='icon icon-back icon-sm'></i> " . $lang->goback, '', "class='btn btn-secondary'");?>
  </div>
  <div id="sidebarHeader">
    <div class="title" title="<?php echo $project->name_with_namespace; ?>"><?php echo $project->name_with_namespace; ?></div>
  </div>
  <div class="btn-toolbar pull-left">
    <div>
      <form id='branchPrivForm' method='post'>
        <?php echo html::input('keyword', $keyword, "class='form-control' placeholder='{$lang->gitlab->branch->placeholderSearch}' style='display: inline-block;width:auto;margin:0 10px'");?>
        <a id="branchSearch" class="btn btn-primary"><?php echo $lang->gitlab->search?></a>
      </form>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('gitlab', 'createBranchPriv')) common::printLink('gitlab', 'createBranchPriv', "gitlabID=$gitlabID&projectID=$projectID", "<i class='icon icon-plus'></i> " . $lang->gitlab->createBranchPriv, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($branchList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(empty($keyword) and common::hasPriv('gitlab', 'createBranchPriv')):?>
    <?php echo html::a($this->createLink('gitlab', 'createBranchPriv', "gitlabID=$gitlabID&projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->gitlab->createBranchPriv, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='branchList' class='table has-sort-head table-fixed'>
      <?php $vars = "gitlabID={$gitlabID}&projectID={$projectID}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-name text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitlab->branch->name);?></th>
          <th class='text-left'><?php echo $lang->gitlab->branch->mergeAllowed;?></th>
          <th class='text-left'><?php echo $lang->gitlab->branch->pushAllowed;?></th>
          <th class='c-actions-4'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($branchList as $id => $branch): ?>
        <?php $branch->merge_access_level = $this->gitlab->checkAccessLevel($branch->merge_access_levels); ?>
        <?php $branch->push_access_level  = $this->gitlab->checkAccessLevel($branch->push_access_levels); ?>
        <tr class='text'>
          <td class='text-c-name' title='<?php echo $branch->name;?>'><?php echo $branch->name;?></td>
          <td class-'text' title="<?php echo $levelLang[$branch->merge_access_level];?>"><?php echo $levelLang[$branch->merge_access_level];?></td>
          <td class='text' title="<?php echo $levelLang[$branch->push_access_level];?>"><?php echo $levelLang[$branch->push_access_level];?></td>
          <td class='c-actions text-left'>
            <?php
            /* Fix error when request type is PATH_INFO and the branch name contains '-'.*/
            $branchName = helper::safe64Encode(urlencode($branch->name));
            if(common::hasPriv('gitlab', 'editBranchPriv')) common::printLink('gitlab', 'editBranchPriv', "gitlabID=$gitlabID&projectID=$projectID&branch=$branchName", "<i class='icon icon-edit'></i> ", '', "title={$lang->gitlab->editBranchPriv} class='btn btn-primary'");
            if(common::hasPriv('gitlab', 'deleteBranchPriv')) echo html::a($this->createLink('gitlab', 'deleteBranchPriv', "gitlabID=$gitlabID&projectID=$projectID&branch=$branchName"), '<i class="icon-trash"></i>', 'hiddenwin', "title='{$lang->gitlab->deleteBranchPriv}' class='btn'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($branchList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
