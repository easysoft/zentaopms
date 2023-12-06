<?php
/**
 * The browse view file of gitlab module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Gang Zeng <zenggang@easycorp.ltd>
 * @package     gitlab
 * @version     $Id$
 * @link        https://www.zentao.net
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
    <?php if(common::hasPriv('gitlab', 'createTag')) common::printLink('gitlab', 'createTag', "gitlabID=$gitlabID&projectID=$projectID", "<i class='icon icon-plus'></i> " . $lang->gitlab->createTag, '', "class='btn btn-primary'");?>
  </div>
</div>
<?php if(empty($gitlabTagList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData;?></span>
    <?php if(empty($keyword) and common::hasPriv('gitlab', 'createTag')):?>
    <?php echo html::a($this->createLink('gitlab', 'createTag', "gitlabID=$gitlabID&projectID=$projectID"), "<i class='icon icon-plus'></i> " . $lang->gitlab->createTag, '', "class='btn btn-info'");?>
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
          <th class='text-left'><?php common::printOrderLink('updated', $orderBy, $vars, $lang->gitlab->tag->lastCommittedDate);?></th>
          <th class='c-actions-1 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($gitlabTagList as $id => $gitlabTag): ?>
        <tr class='text'>
          <td class='text-c-name' title='<?php echo $gitlabTag->name;?>'>
            <div class='has-prefix has-suffix'>
              <span class='tag-name text-ellipsis'><?php echo $gitlabTag->name;?></span>
              <?php if($gitlabTag->protected) echo '<span class="label label-badge label-info">' . $lang->gitlab->tag->protected . '</span>';?>
            </div>
          </td>
          <td class='text'><?php echo $gitlabTag->lastCommitter;?></td>
          <td class='text'><?php echo $gitlabTag->updated?></td>
          <td class='c-actions text-left'>
            <?php
            /* Fix error when request type is PATH_INFO and the tag name contains '-'.*/
            $tagName    = helper::safe64Encode(urlencode($gitlabTag->name));
            $isDisabled = $gitlabTag->protected ? 'disabled' : '';
            common::printLink('gitlab', 'deleteTag', "gitlabID=$gitlabID&projectID={$projectID}&tag_name=$tagName", "<i class='icon icon-trash'></i> ", '', "title='{$lang->gitlab->deleteTag}' class='btn' target='hiddenwin' $isDisabled");
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
