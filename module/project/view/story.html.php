<?php
/**
 * The story view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: story.html.php 5117 2013-07-12 07:03:14Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php $canOrder = common::hasPriv('project', 'storySort');?>
<?php include '../../common/view/header.html.php';?>
<?php if($canOrder) include '../../common/view/sortable.html.php';?>
<?php js::set('moduleID', ($type == 'byModule' ? $param : 0));?>
<?php js::set('productID', ($type == 'byProduct' ? $param : 0));?>
<?php js::set('branchID', ($type == 'byBranch' ? (int)$param : ''));?>
<?php js::set('confirmUnlinkStory', $lang->project->confirmUnlinkStory)?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <?php if(!empty($module->name)):?>
    <div class="title" title='<?php echo $module->name?>'>
      <?php $removeLink = inlink('story', "projectID=$project->id&orderBy=$orderBy&type=$type&param=0&recTotal=0&recPerPage={$pager->recPerPage}");?>
      <?php echo $module->name;?>
      <?php echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");?>
    </div>
    <?php else:?>
    <div class="title" title='<?php echo $project->name?>'><?php echo $project->name;?></div>
    <?php endif;?>
  </div>
  <div class="btn-toolbar pull-left">
    <?php if(common::hasPriv('project', 'story')) echo html::a($this->createLink('project', 'story', "projectID=$project->id"), "<span class='text'>{$lang->story->allStories}</span><span class='label label-light label-badge'>{$pager->recTotal}</span>", '', "class='btn btn-link btn-active-text'");?>
    <?php if(common::hasPriv('project', 'storykanban')) echo html::a($this->createLink('project', 'storykanban', "projectID=$project->id"), "<span class='text'>{$lang->project->kanban}</span>", '', "class='btn btn-link'");?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php
    common::printLink('story', 'export', "productID=$productID&orderBy=id_desc", "<i class='icon icon-export muted'></i> " . $lang->story->export, '', "class='btn btn-link export'");

    $this->lang->story->create = $this->lang->project->createStory;
    if($productID and !$this->loadModel('story')->checkForceReview())
    {
        echo "<div class='btn-group dropdown-hover'>";
        echo "<button type='button' class='btn btn-link'>";
        echo "<i class='icon-plus'></i> {$lang->story->create} <span class='caret'></span>";
        echo '</button>';
        echo "<ul class='dropdown-menu pull-right' id='createActionMenu'>";
        if(common::hasPriv('story', 'create')) echo '<li>' . html::a($this->createLink('story', 'create',  "productID=$productID&branch=0&moduleID=0&story=0&project=$project->id"), $lang->story->create) . '</li>';
        if(common::hasPriv('story', 'batchCreate')) echo '<li>' . html::a($this->createLink('story', 'batchCreate', "productID=$productID&branch=0&moduleID=0&story=0&project=$project->id"), $lang->story->batchCreate) . '</li>';
        echo '</ul>';
        echo '</div>';
    }

    if(commonModel::isTutorialMode())
    {
        $wizardParams = helper::safe64Encode("project=$project->id");
        echo html::a($this->createLink('tutorial', 'wizard', "module=project&method=linkStory&params=$wizardParams"), "<i class='icon-link'></i> {$lang->project->linkStory}",'', "class='btn btn-link link-story-btn'");
    }
    else
    {
        echo "<div class='btn-group dropdown-hover'>";
        echo "<button type='button' class='btn btn-primary' id='linkButton'>";
        echo "<i class='icon-link'></i> {$lang->project->linkStory} <span class='caret'></span>";
        echo '</button>';
        echo "<ul class='dropdown-menu pull-right' id='linkActionMenu'>";
        if(common::hasPriv('project', 'linkStory')) echo '<li>' . html::a(inlink('linkStory', "project=$project->id"), $lang->project->linkStory). "</li>";
        if(common::hasPriv('project', 'importPlanStories')) echo '<li>' . html::a('#linkStoryByPlan', $lang->project->linkStoryByPlan, '', 'data-toggle="modal"') . "</li>";
        echo '</ul>';
        echo '</div>';
    }
    ?>
  </div>
</div>

<div id="mainContent" class="main-row fade">
  <div class='side-col' id='sidebar'>
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $moduleTree;?>
    </div>
  </div>
  <div class="main-col">
    <div class="cell" id="queryBox"></div>
    <?php if(empty($stories)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->story->noStory;?></span>
        <?php if(common::hasPriv('project', 'linkStory')):?>
        <span class="text-muted"><?php echo $lang->youCould;?></span>
        <?php echo html::a($this->createLink('project', 'linkStory', "project=$project->id"), "<i class='icon icon-link'></i> " . $lang->project->linkStory, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table table-story skip-iframe-modal' method='post' id='projectStoryForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <table class='table has-sort-head' id='storyList'>
        <thead>
          <tr>
          <?php
          $totalEstimate = 0;
          $canBatchEdit  = common::hasPriv('story', 'batchEdit');
          $canBatchClose = common::hasPriv('story', 'batchClose');
          ?>
          <?php $vars = "projectID={$project->id}&orderBy=%s&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
            <th class='c-id'>
              <?php if($canBatchEdit or $canBatchClose):?>
              <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                <label></label>
              </div>
              <?php endif;?>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <?php if($canOrder):?>
            <th class='w-80px c-sort'> <?php common::printOrderLink('order',      $orderBy, $vars, $lang->project->updateOrder);?></th>
            <?php endif;?>
            <th class='c-pri'>  <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
            <th class='c-name'> <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
            <th class='c-user'> <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
            <th class='c-user'> <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
            <th class='c-estimate w-80px'> <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
            <th class='c-status'> <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
            <th class='c-stage w-70px'> <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
            <th title='<?php echo $lang->story->taskCount?>' class='w-30px'><?php echo $lang->story->taskCountAB;?></th>
            <th title='<?php echo $lang->story->bugCount?>'  class='w-30px'><?php echo $lang->story->bugCountAB;?></th>
            <th title='<?php echo $lang->story->caseCount?>' class='w-30px'><?php echo $lang->story->caseCountAB;?></th>
            <th class='c-actions-4 text-center'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody id='storyTableList' class='sortable'>
          <?php foreach($stories as $key => $story):?>
          <?php
          $storyLink      = $this->createLink('story', 'view', "storyID=$story->id&version=$story->version&from=project&param=$project->id");
          $totalEstimate += $story->estimate;
          ?>
          <tr id="story<?php echo $story->id;?>" data-id='<?php echo $story->id;?>' data-order='<?php echo $story->order ?>' data-estimate='<?php echo $story->estimate?>' data-cases='<?php echo zget($storyCases, $story->id, 0)?>'>
            <td class='cell-id'>
              <?php if($canBatchEdit or $canBatchClose):?>
              <?php echo html::checkbox('storyIDList', array($story->id => sprintf('%03d', $story->id)));?>
              <?php else:?>
              <?php printf('%03d', $story->id);?>
              <?php endif;?>
            </td>
            <?php if($canOrder):?>
            <td class='sort-handler c-sort'><i class='icon-move'></i></td>
            <?php endif;?>
            <td class='c-pri'><span class='label-pri <?php echo 'label-pri-' . $story->pri?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
            <td class='c-name' title="<?php echo $story->title?>">
              <?php if(isset($branchGroups[$story->product][$story->branch])) echo "<span class='label label-info label-badge'>" . $branchGroups[$story->product][$story->branch] . '</span>';?>
              <?php echo html::a($storyLink,$story->title, null, "style='color: $story->color'");?>
            </td>
            <td class='c-user'><?php echo $users[$story->openedBy];?></td>
            <td class='c-user'><?php echo $users[$story->assignedTo];?></td>
            <td class='c-estimate'><?php echo $story->estimate;?></td>
            <td class='c-status' title='<?php echo zget($lang->story->statusList, $story->status);?>'>
              <span class='status-<?php echo $story->status;?>'>
                <span class='label label-dot'></span>
                <span class='status-text'><?php echo zget($lang->story->statusList, $story->status);?></span>
              </span>
            </td>
            <td class='c-stage'><?php echo $lang->story->stageList[$story->stage];?></td>
            <td class='linkbox'>
              <?php
              $tasksLink = $this->createLink('story', 'tasks', "storyID=$story->id&projectID=$project->id");
              $storyTasks[$story->id] > 0 ? print(html::a($tasksLink, $storyTasks[$story->id], '', 'class="iframe"')) : print(0);
              ?>
            <td>
              <?php
              $bugsLink = $this->createLink('story', 'bugs', "storyID=$story->id&projectID=$project->id");
              $storyBugs[$story->id] > 0 ? print(html::a($bugsLink, $storyBugs[$story->id], '', 'class="iframe"')) : print(0);
              ?>
            </td>
            <td>
              <?php
              $casesLink = $this->createLink('story', 'cases', "storyID=$story->id&projectID=$project->id");
              $storyCases[$story->id] > 0 ? print(html::a($casesLink, $storyCases[$story->id], '', 'class="iframe"')) : print(0);
              ?>
            </td>
            <td class='c-actions'>
              <?php
              $hasDBPriv = common::hasDBPriv($project, 'project');
              $param = "projectID={$project->id}&story={$story->id}&moduleID={$story->module}";

              $lang->task->create = $lang->project->wbs;
              if(commonModel::isTutorialMode())
              {
                  $wizardParams = helper::safe64Encode($param);
                  echo html::a($this->createLink('tutorial', 'wizard', "module=task&method=create&params=$wizardParams"), "<i class='icon-plus'></i>",'', "class='btn btn-task-create' title='{$lang->project->wbs}'");
              }
              else
              {
                  if($hasDBPriv) common::printIcon('task', 'create', $param, '', 'list', 'plus', '', 'btn-task-create');
              }

              $lang->task->batchCreate = $lang->project->batchWBS;
              if($hasDBPriv) common::printIcon('task', 'batchCreate', "projectID={$project->id}&story={$story->id}", '', 'list', 'pluses');

              $lang->testcase->batchCreate = $lang->testcase->create;
              if($productID && $hasDBPriv) common::printIcon('testcase', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id", '', 'list', 'sitemap');

              if(common::hasPriv('project', 'unlinkStory', $project))
              {
                  $unlinkURL = $this->createLink('project', 'unlinkStory', "projectID=$project->id&storyID=$story->id&confirm=yes");
                  echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-unlink"></i>', '', "class='btn' title='{$lang->project->unlinkStory}'");
              }
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php if($canBatchEdit or $canBatchClose):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class='table-actions btn-toolbar'>
          <?php
          if($canBatchEdit)
          {
              $actionLink = $this->createLink('story', 'batchEdit', "productID=0&projectID=$project->id");
              echo html::commonButton($lang->edit, "data-form-action='$actionLink'");
          }
          if($canBatchClose)
          {
              $actionLink = $this->createLink('story', 'batchClose', "productID=0&projectID=$project->id");
              echo html::commonButton($lang->close, "data-form-action='$actionLink'");
          }
          ?>
          <?php if(common::hasPriv('story', 'batchChangeStage')):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->stageAB;?> <span class="caret"></span></button>
            <?php
            echo "<ul class='dropdown-menu'>";
            $lang->story->stageList[''] = $lang->null;
            foreach($lang->story->stageList as $key => $stage)
            {
                $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
            }
            echo '</ul>';
            ?>
          </div>
          <?php endif;?>
          <?php
          if(common::hasPriv('project', 'batchUnlinkStory'))
          {
              $actionLink = $this->createLink('project', 'batchUnlinkStory', "projectID=$project->id");
              echo html::commonButton($lang->project->unlinkStory, "data-form-action='$actionLink'");
          }
          ?>
        </div>
        <div class="table-statistic"><?php echo $summary;?></div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>

<div class="modal fade" id="linkStoryByPlan">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->project->linkStoryByPlan;?></h4>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('plan', $allPlans, '', "class='form-control chosen' id='plan'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->project->linkStory, "id='toTaskButton'", 'btn btn-primary');?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('checkedSummary', $lang->product->checkedSummary);?>
<?php js::set('projectID', $project->id);?>
<?php js::set('orderBy', $orderBy)?>
<script>
$(function()
{
    // Update table summary text
    var checkedSummary = '<?php echo $lang->product->checkedSummary?>';
    $('#projectStoryForm').table(
    {
        statisticCreator: function(table)
        {
            var $checkedRows = table.$.find('tbody>tr.checked');
            var checkedTotal = $checkedRows.length;
            if(!checkedTotal) return;

            var checkedEstimate = 0;
            var checkedCase     = 0;
            $checkedRows.each(function()
            {
                var $row = $(this);
                var data = $row.data();
                checkedEstimate += data.estimate;
                checkedCase += data.cases;
            });
            var rate = Math.round(checkedCase / checkedTotal * 10000) / 100 + '' + '%';
            return checkedSummary.replace('%total%', checkedTotal)
                  .replace('%estimate%', checkedEstimate)
                  .replace('%rate%', rate);
        }
    });
    <?php if(!$stories):?>
    $("#main").addClass('hide-sidebar');
    <?php endif;?>
});
</script>
<?php include '../../common/view/footer.html.php';?>
