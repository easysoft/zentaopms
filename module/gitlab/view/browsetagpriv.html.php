<?php
/**
 * The browse view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2021 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html) or AGPL
 * @author      Gang Zeng <zenggang@easycorp.ltd>
 * @package     gitlab
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('vars', "keyword=%s&orderBy=id_desc&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID=1")?>
<?php js::set('gitlabID', $gitlabID)?>
<div id="mainMenu" class="clearfix">
  <div class='pull-left'>
    <?php echo html::linkButton('<i class="icon icon-back icon-sm"></i> ' . $lang->goback, $this->createLink('gitlab', 'browseProject', "gitlabID=$gitlabID"), 'self', '','btn btn-secondary');?>
  </div>
  <div id="sidebarHeader">
    <div class="title" title="<?php echo $project->name_with_namespace; ?>"><?php echo $project->name_with_namespace; ?></div>
  </div>
  <div class="btn-toolbar pull-left">
    <div>
      <form id='tagForm' method='post'>
        <?php echo html::input('keyword', $keyword, "class='form-control' placeholder='{$lang->gitlab->tag->placeholderSearch}' style='display: inline-block;width:auto;margin:0 10px'");?>
        <a id="tagSearch" class="btn btn-primary"><?php echo $lang->gitlab->search?></a>
      </form>
    </div>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printLink('gitlab', 'createTagPriv', "gitlabID=$gitlabID&projectID=$projectID", "<i class='icon icon-plus'></i> " . $lang->gitlab->createTagPriv, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($gitlabTagList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(empty($keyword) and common::hasPriv('gitlab', 'createTag')):?>
    <?php echo html::a($this->createLink('gitlab', 'createTagPriv', "gitlabID=$gitlabID&projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->gitlab->createTagPriv, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else:?>
<div id='mainContent' class='main-row'>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabTagList' class='table has-sort-head table-fixed'>
      <?php $vars = "gitlabID={$gitlabID}&projectID={$projectID}&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='c-name text-left'><?php common::printOrderLink('name', $orderBy, $vars, $lang->gitlab->tag->name);?></th>
          <th class='text-left'><?php echo $lang->gitlab->tag->lastCommitter;?></th>
          <th class='text-left'><?php common::printOrderLink('accessLevels', $orderBy, $vars, $lang->gitlab->tag->accessLevel);?></th>
          <th class='c-actions-2'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabTagList as $id => $gitlabTag): ?>
        <?php $gitlabTag->accessLevel  = $this->gitlab->checkAccessLevel($gitlabTag->accessLevels); ?>
        <tr class='text'>
        <td class='text-c-name' title='<?php echo $gitlabTag->name;?>'><?php echo $gitlabTag->name;?></td>
          <td class='text'><?php echo $gitlabTag->lastCommitter;?></td>
          <td class='text'><?php echo zget($lang->gitlab->branch->branchCreationLevelList, $gitlabTag->accessLevel);?></td>
          <td class='c-actions text-left'>
            <?php
            /* Fix error when request type is PATH_INFO and the tag name contains '-'.*/
            $tagName = helper::safe64Encode(urlencode($gitlabTag->name));
            common::printLink('gitlab', 'editTagPriv', "gitlabID=$gitlabID&projectID=$projectID&tag_name=$tagName", "<i class='icon icon-edit'></i> ", '', "title={$lang->gitlab->editTagPriv} class='btn btn-primary'");
            common::printLink('gitlab', 'deleteTagPriv', "gitlabID=$gitlabID&projectID={$projectID}&tag_name=$tagName", "<i class='icon icon-trash'></i> ", '', "title='{$lang->gitlab->deleteTagPriv}' class='btn btn-primary' target='hiddenwin' onclick='if(confirm(\"{$lang->gitlab->tag->protectConfirmDel}\")==false) return false;'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <?php if($gitlabTagList):?>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
    <?php endif;?>
  </form>
</div>
<?php endif;?>
<?php include '../../common/view/footer.html.php';?>
