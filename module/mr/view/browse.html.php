<?php
/**
 * The view file for browse page of mr module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @author      Guodong Ding
 * @package     mr
 * @version     $Id: create.html.php $
 */
?>
<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolBar pull-left">
    <?php
    $menus = customModel::getFeatureMenu('mr', 'browse');
    foreach($menus as $menuItem)
    {
        $label     = "<span class='text'>{$menuItem->text}</span>";
        $active    = ($param == $menuItem->name or $mode == $menuItem->name) ? 'btn-active-text' : '';
        $modeParam = in_array($menuItem->name, array('assignee', 'creator')) ? $menuItem->name : 'status';
        $paramName = in_array($menuItem->name, array('assignee', 'creator')) ? $this->app->user->account : $menuItem->name;

        if($param == $menuItem->name) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
        echo html::a(inlink('browse', "repoID=$repoID&mode=$modeParam&param=$paramName"), $label, '', "class='btn btn-link $active'");
    }
    ?>
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
          <?php $vars = "repoID=$repoID&mode=$mode&param=$param&objectID=$objectID&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
          <th class='c-id'><?php common::printOrderLink('id', $orderBy, $vars, $lang->mr->id);?></th>
          <th class='c-title'><?php common::printOrderLink('title', $orderBy, $vars, $lang->mr->title);?></th>
          <th class='c-branch'><?php common::printOrderLink('sourceBranch', $orderBy, $vars, $lang->mr->sourceBranch);?></th>
          <th class='c-branch'><?php common::printOrderLink('targetBranch', $orderBy, $vars, $lang->mr->targetBranch);?></th>
          <th class='c-status'><?php common::printOrderLink('mergeStatus', $orderBy, $vars, $lang->mr->mergeStatus);?></th>
          <th class='c-status'><?php common::printOrderLink('approvalStatus', $orderBy, $vars, $lang->mr->approvalStatus);?></th>
          <th class='c-author'><?php common::printOrderLink('createdBy', $orderBy, $vars, $lang->mr->author);?></th>
          <th class='c-date'><?php common::printOrderLink('createdDate', $orderBy, $vars, $lang->mr->createdDate);?></th>
          <th class='c-actions-5 text-center'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($MRList as $MR):?>
        <?php
        if($repo->SCM == 'Gitlab')
        {
            $sourceProject = isset($projects[$MR->hostID][$MR->sourceProject]) ? $projects[$MR->hostID][$MR->sourceProject]->name_with_namespace . ':' . $MR->sourceBranch : $MR->sourceProject . ':' . $MR->sourceBranch;
            $targetProject = isset($projects[$MR->hostID][$MR->targetProject]) ? $projects[$MR->hostID][$MR->targetProject]->name_with_namespace . ':' . $MR->targetBranch : $MR->targetProject . ':' . $MR->targetBranch;
        }
        else
        {
            $sourceProject = isset($projects[$MR->hostID][$MR->sourceProject]) ? $projects[$MR->hostID][$MR->sourceProject]->full_name . ':' . $MR->sourceBranch : $MR->sourceProject . ':' . $MR->sourceBranch;
            $targetProject = isset($projects[$MR->hostID][$MR->targetProject]) ? $projects[$MR->hostID][$MR->targetProject]->full_name . ':' . $MR->targetBranch : $MR->targetProject . ':' . $MR->targetBranch;
        }
        ?>
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
          <td class='text' title='<?php echo $MR->createdDate;?>'><?php echo $MR->createdDate;?></td>
          <td class='c-actions'>
            <?php
            $canDelete = ($app->user->admin or (isset($openIDList[$MR->hostID]) and isset($projects[$MR->hostID][$MR->sourceProject]->owner->id) and $projects[$MR->hostID][$MR->sourceProject]->owner->id == $openIDList[$MR->hostID])) ? '' : 'disabled';
            if($repo->SCM == 'Gitlab')
            {
                $canEdit = (isset($projects[$MR->hostID][$MR->sourceProject]->isDeveloper) and $projects[$MR->hostID][$MR->sourceProject]->isDeveloper == true) ? '' : 'disabled';
            }
            elseif($repo->SCM == 'Gitea')
            {
                $canEdit = (isset($projects[$MR->hostID][$MR->sourceProject]->allow_merge_commits) and $projects[$MR->hostID][$MR->sourceProject]->allow_merge_commits == true) ? '' : 'disabled';
            }
            elseif($repo->SCM == 'Gogs')
            {
                $canEdit = (isset($projects[$MR->hostID][$MR->sourceProject]->permissions->push) and $projects[$MR->hostID][$MR->sourceProject]->permissions->push) ? '' : 'disabled';
            }
            common::printLink('mr', 'view',   "MRID={$MR->id}", '<i class="icon icon-eye"></i>', '', "title='{$lang->mr->view}' class='btn btn-info'");
            common::printIcon('mr', 'edit',   "MRID={$MR->id}", $MR, 'list',  '', '', $canEdit);
            common::printLink('mr', 'diff',   "MRID={$MR->id}", '<i class="icon icon-diff"></i>', '', "title='{$lang->mr->viewDiff}' class='btn btn-info'");
            common::printLink('mr', 'link',   "MRID={$MR->id}", '<i class="icon icon-link"></i>', '', "title='{$lang->mr->link}' class='btn btn-info'" . ($MR->linkButton == false ? 'disabled' : ''));
            common::printLink('mr', 'delete', "MRID={$MR->id}", '<i class="icon icon-trash"></i>', 'hiddenwin', "title='{$lang->mr->delete}' class='btn btn-info {$canDelete}'");
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
