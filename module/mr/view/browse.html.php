<?php
/**
 * The view file for browse page of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2012 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @author      Guodong Ding
 * @package     mr
 * @version     $Id: create.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class='pull-right'>
    <?php common::printLink('mr', 'create', '', "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-primary'");?>
  </div>
</div>
<div id='mainContent'>
<?php if(empty($MRList)):?>
<div class="table-empty-tip">
  <p>
    <span class="text-muted"><?php echo $lang->noData . $lang->mr->common;?></span>
    <?php if(common::hasPriv('mr', 'create')):?>
    <?php echo html::a($this->createLink('mr', 'create'), "<i class='icon icon-plus'></i> " . $lang->mr->create, '', "class='btn btn-info'");?>
    <?php endif;?>
  </p>
</div>
<?php else: ?>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabProjectList' class='table has-sort-head table-fixed'>
      <thead>
        <tr>
          <?php $vars = "objectID=$objectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}"; ?>
          <th class='w-60px  text-left'><?php common::printOrderLink('id', $orderBy, $vars, $lang->mr->id); ?></th>
          <th class='w-200px text-left'><?php common::printOrderLink('title', $orderBy, $vars, $lang->mr->title); ?></th>
          <th class='text-left'><?php common::printOrderLink('sourceProject', $orderBy, $vars, $lang->mr->sourceProject); ?></th>
          <th class='w-120px text-left'><?php common::printOrderLink('sourceBranch', $orderBy, $vars, $lang->mr->sourceBranch); ?></th>
          <th class='text-left'><?php common::printOrderLink('targetProject', $orderBy, $vars, $lang->mr->targetProject); ?></th>
          <th class='w-120px text-left'><?php common::printOrderLink('targetBranch', $orderBy, $vars, $lang->mr->targetBranch); ?></th>
          <th class='w-120px text-left'><?php common::printOrderLink('mergeStatus', $orderBy, $vars, $lang->mr->mergeStatus); ?></th>
          <th class='c-actions-3'><?php echo $lang->actions; ?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($MRList as $MR):?>
        <tr>
          <td class='text'><?php echo $MR->id; ?></td>
          <td class='text'><?php echo $MR->title; ?></td>
          <td class='text'><?php echo $this->loadModel('gitlab')->apiGetSingleProject($MR->gitlabID, $MR->sourceProject)->name_with_namespace; ?></td>
          <td class='text'><?php echo $MR->sourceBranch;?></td>
          <td class='text'><?php echo $this->loadModel('gitlab')->apiGetSingleProject($MR->gitlabID, $MR->targetProject)->name_with_namespace; ?></td>
          <td class='text'><?php echo $MR->targetBranch;?></td>
          <td class='text'><?php echo ($MR->status == 'merged') ? zget($lang->mr->statusList, $MR->status) : zget($lang->mr->mergeStatusList, $MR->mergeStatus); ?></td>
          <td class='c-actions'>
            <?php
            common::printLink('mr', 'view',   "mr={$MR->id}", '<i class="icon icon-eye"></i>', '', "title='{$lang->mr->view}' class='btn btn-info'");
            common::printLink('mr', 'edit',   "mr={$MR->id}", '<i class="icon icon-edit"></i>', '', "title='{$lang->mr->edit}' class='btn btn-info'");
            /* Function diff is not ready yet. so comment it. */
            //common::printLink('mr', 'diff',   "mr={$MR->id}", '<i class="icon icon-review"></i>', '', "title='{$lang->mr->viewDiff}' class='btn btn-info'");
            common::printLink('mr', 'delete', "mr={$MR->id}", '<i class="icon icon-trash"></i>', 'hiddenwin', "title='{$lang->mr->delete}' class='btn btn-info'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class='table-footer'><?php $pager->show('right', 'pagerjs');?></div>
  </form>
<?php endif;?>
</div>
<?php include '../../common/view/footer.html.php'; ?>
