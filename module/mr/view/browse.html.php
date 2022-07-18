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
  <div class="btn-toolBar pull-left">
    <?php foreach($lang->mr->statusList as $key => $label):?>
    <?php $active = $param == $key ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>$label</span>";?>
    <?php if($param == $key) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('browse', "repoID=$repoID&mode=status&param=$key"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <?php $active = $mode == 'assignee' ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>{$lang->mr->assignedToMe}</span>";?>
    <?php if($mode == 'assignee') $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('browse', "repoID=$repoID&mode=assignee&param={$this->app->user->account}"), $label, '', "class='btn btn-link $active'");?>
    <?php $active = $mode == 'creator' ? 'btn-active-text' : '';?>
    <?php $label = "<span class='text'>{$lang->mr->createdByMe}</span>";?>
    <?php if($mode == 'creator') $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('browse', "repoID=$repoID&mode=creator&param={$this->app->user->account}"), $label, '', "class='btn btn-link $active'");?>
  </div>
  <div class="btn-toolbar pull-right">
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
<?php else:?>
  <form class='main-table' id='ajaxForm' method='post'>
    <table id='gitlabProjectList' class='table has-sort-head table-fixed'>
      <thead>
        <tr class='text-left'>
          <?php $vars = "mode=$mode&param=$param&objectID=$objectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='w-60px'><?php common::printOrderLink('id', $orderBy, $vars, $lang->mr->id);?></th>
          <th class='c-name'><?php common::printOrderLink('title', $orderBy, $vars, $lang->mr->title);?></th>
          <th class='w-200px'><?php common::printOrderLink('sourceBranch', $orderBy, $vars, $lang->mr->sourceBranch);?></th>
          <th class='w-200px'><?php common::printOrderLink('targetBranch', $orderBy, $vars, $lang->mr->targetBranch);?></th>
          <th class='w-120px'><?php common::printOrderLink('mergeStatus', $orderBy, $vars, $lang->mr->mergeStatus);?></th>
          <th class='w-120px'><?php common::printOrderLink('approvalStatus', $orderBy, $vars, $lang->mr->approvalStatus);?></th>
          <th class='w-120px'><?php common::printOrderLink('createdBy', $orderBy, $vars, $lang->mr->author);?></th>
          <th class='c-actions-5'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($MRList as $MR):?>
        <?php $sourceProject = isset($projects[$MR->hostID][$MR->sourceProject]) ? $projects[$MR->hostID][$MR->sourceProject]->name_with_namespace . ':' . $MR->sourceBranch : $MR->sourceProject . ':' . $MR->sourceBranch; ?>
        <?php $targetProject = isset($projects[$MR->hostID][$MR->targetProject]) ? $projects[$MR->hostID][$MR->targetProject]->name_with_namespace . ':' . $MR->targetBranch : $MR->targetProject . ':' . $MR->targetBranch; ?>
        <tr>
          <td class='text'><?php echo $MR->id;?></td>
          <td class='text'><?php echo html::a(inlink('view', "mr={$MR->id}"), $MR->title);?></td>
          <td class='text' title='<?php echo $sourceProject;?>'><?php echo $sourceProject;?></td>
          <td class='text' title='<?php echo $targetProject;?>'><?php echo $targetProject;?></td>
          <?php if($MR->status == 'closed'):?>
            <td class='text'><?php echo zget($lang->mr->statusList, $MR->status);?></td>
          <?php else:?>
            <td class='text'><?php echo ($MR->status == 'merged') ? zget($lang->mr->statusList, $MR->status) : zget($lang->mr->mergeStatusList, $MR->mergeStatus);?></td>
          <?php endif;?>

          <?php if($MR->status == 'merged' or $MR->status == 'closed'):?>
            <td class='text'><?php echo '-';?></td> <!-- Keep page clean that make user focus to the MR not reviewed. -->
          <?php else:?>
            <td><?php echo empty($MR->approvalStatus) ? $lang->mr->approvalStatusList['notReviewed'] : $lang->mr->approvalStatusList[$MR->approvalStatus];?></td>
          <?php endif;?>
          <td class='text' title='<?php echo zget($users, $MR->createdBy);?>'><?php echo zget($users, $MR->createdBy);?></td>
          <td class='c-actions'>
            <?php
            $canDelete = ($app->user->admin or (isset($projects[$MR->hostID][$MR->sourceProject]->owner->id) and $projects[$MR->hostID][$MR->sourceProject]->owner->id == $openIDList[$MR->hostID])) ? '' : 'disabled';
            $canEdit   = (isset($projects[$MR->hostID][$MR->sourceProject]->isDeveloper) and $projects[$MR->hostID][$MR->sourceProject]->isDeveloper == true) ? '' : 'disabled';
            common::printLink('mr', 'view',   "mr={$MR->id}", '<i class="icon icon-eye"></i>', '', "title='{$lang->mr->view}' class='btn btn-info'");
            common::printIcon('mr', 'edit',   "mr={$MR->id}", $MR, 'list',  '', '', '', false, "{$canEdit}");
            common::printLink('mr', 'diff',   "mr={$MR->id}", '<i class="icon icon-diff"></i>', '', "title='{$lang->mr->viewDiff}' class='btn btn-info'");
            common::printLink('mr', 'link',   "mr={$MR->id}", '<i class="icon icon-link"></i>', '', "title='{$lang->mr->link}' class='btn btn-info'" . ($MR->linkButton == false ? 'disabled' : ''));
            common::printLink('mr', 'delete', "mr={$MR->id}", '<i class="icon icon-trash"></i>', 'hiddenwin', "title='{$lang->mr->delete}' class='btn btn-info {$canDelete}'");
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
<?php include '../../common/view/footer.html.php';?>
