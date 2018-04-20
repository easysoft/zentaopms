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
    <?php echo html::commonButton('<i class="icon icon-caret-left"></i>', '', 'btn btn-icon btn-sm btn-info sidebar-toggle');?>
    <div class="title" title='<?php echo $project->name?>'><?php echo $project->name;?></div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php if(common::hasPriv('project', 'story')) echo html::a($this->createLink('project', 'story', "project=$project->id"), "<span class='text'>{$lang->project->story}</span>", '', "class='btn btn-link btn-active-text'");?>
    <?php if(common::hasPriv('project', 'storykanban')) echo html::a($this->createLink('project', 'storykanban', "project=$project->id"), "<span class='text'>{$lang->project->kanban}</span>", '', "class='btn btn-link'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
    <?php
    common::printIcon('story', 'export', "productID=$productID&orderBy=id_desc", '', 'button', '', '', 'export');

    $this->lang->story->create = $this->lang->project->createStory;
    if($productID and !$this->loadModel('story')->checkForceReview())
    {
        echo "<div class='btn-group' id='createActionMenu'>";
        common::printIcon('story', 'create', "productID=$productID&branch=0&moduleID=0&story=0&project=$project->id");

        $misc = common::hasPriv('story', 'batchCreate') ? '' : "disabled";
        $link = common::hasPriv('story', 'batchCreate') ?  $this->createLink('story', 'batchCreate', "productID=$productID&branch=0&moduleID=0&story=0&project=$project->id") : '#';
        echo "<button type='button' class='btn btn-link dropdown-toggle {$misc}' data-toggle='dropdown'>";
        echo "<span class='caret'></span>";
        echo '</button>';
        echo "<ul class='dropdown-menu pull-right'>";
        echo "<li>" . html::a($link, $lang->story->batchCreate, '', "class='$misc'") . "</li>";
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
        echo "<div class='btn-group' id='createActionMenu'>";
        common::printIcon('project', 'linkStory', "project=$project->id", '', 'button', 'link', '', 'link-story-btn');
        if(common::hasPriv('project', 'importPlanStories'))
        {
            echo "<button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown'>";
            echo "<span class='caret'></span>";
            echo '</button>';
            echo "<ul class='dropdown-menu pull-right'>";
            echo "<li>" . html::a('###', $lang->project->linkStoryByPlan, '', 'data-toggle="linkStoryByPlan"') . "</li>";
            echo '</ul>';
        }
        echo '</div>';
    }
    ?>
    </div>
  </div>
</div>

<div id='queryBox' class='show'></div>
<div id="mainContent" class="main-row">
  <div class='side-col' id='sidebar'>
    <div class="cell">
      <?php echo $moduleTree;?>
    </div>
  </div>
  <div class="main-col">
    <form class='main-table table-story' method='post' data-ride='table' id='projectStoryForm'>
      <div class="table-header">
        <div class="table-statistic"><?php echo $summary;?></div>
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
            <th class='w-80px'> <?php common::printOrderLink('order',      $orderBy, $vars, $lang->project->updateOrder);?></th>
            <?php endif;?>
            <th class='w-pri'>  <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
            <th> <?php common::printOrderLink('title',      $orderBy, $vars, $lang->story->title);?></th>
            <th class='w-user'> <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
            <th class='w-80px'> <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
            <th class='w-80px'> <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
            <th class='w-80px'> <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
            <th class='w-70px'> <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
            <th title='<?php echo $lang->story->taskCount?>' class='w-30px'><?php echo $lang->story->taskCountAB;?></th>
            <th title='<?php echo $lang->story->bugCount?>'  class='w-30px'><?php echo $lang->story->bugCountAB;?></th>
            <th title='<?php echo $lang->story->caseCount?>' class='w-30px'><?php echo $lang->story->caseCountAB;?></th>
            <th class='w-160px'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody id='storyTableList' class='sortable'>
          <?php foreach($stories as $key => $story):?>
          <?php
          $storyLink      = $this->createLink('story', 'view', "storyID=$story->id&version=$story->version&from=project&param=$project->id");
          $totalEstimate += $story->estimate;
          ?>
          <tr id="story<?php echo $story->id;?>" data-id='<?php echo $story->id;?>' data-order='<?php echo $story->order ?>' data-estimate='<?php echo $story->estimate?>' data-cases='<?php echo zget($storyCases, $story->id, 0)?>'>
            <td class='c-id'>
              <div class="checkbox-primary">
                <?php if($canBatchEdit or $canBatchClose):?>
                <input type='checkbox' name='storyIDList[<?php echo $story->id;?>]' value='<?php echo $story->id;?>' />
                <label></label>
                <?php endif;?>
                <?php printf('%03d', $story->id);?>
              </div>
            </td>
            <?php if($canOrder):?>
            <td class='sort-handler'><i class='icon-move'></i></td>
            <?php endif;?>
            <td><span class='label-pri <?php echo 'label-pri-' . $story->pri?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
            <td class='text-left' title="<?php echo $story->title?>">
              <?php if(isset($branchGroups[$story->product][$story->branch])) echo "<span class='label label-info label-badge'>" . $branchGroups[$story->product][$story->branch] . '</span>';?>
              <?php echo html::a($storyLink,$story->title, null, "style='color: $story->color'");?>
            </td>
            <td><?php echo $users[$story->openedBy];?></td>
            <td><?php echo $users[$story->assignedTo];?></td>
            <td><?php echo $story->estimate;?></td>
            <td class='story-<?php echo $story->status;?>'><?php echo zget($lang->story->statusList, $story->status);?></td>
            <td><?php echo $lang->story->stageList[$story->stage];?></td>
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
                  echo html::a($this->createLink('tutorial', 'wizard', "module=task&method=create&params=$wizardParams"), "<i class='icon-plus-border'></i>",'', "class='btn btn-link btn-task-create' title='{$lang->project->wbs}'");
              }
              else
              {
                  if($hasDBPriv) common::printIcon('task', 'create', $param, '', 'list', 'plus', '', 'btn-task-create');
              }

              $lang->task->batchCreate = $lang->project->batchWBS;
              if($hasDBPriv) common::printIcon('task', 'batchCreate', "projectID={$project->id}&story={$story->id}", '', 'list', 'plus-sign');

              $lang->testcase->batchCreate = $lang->testcase->create;
              if($productID && $hasDBPriv) common::printIcon('testcase', 'batchCreate', "productID=$story->product&branch=$story->branch&moduleID=$story->module&storyID=$story->id", '', 'list', 'sitemap');

              if(common::hasPriv('project', 'unlinkStory', $project))
              {
                  $unlinkURL = $this->createLink('project', 'unlinkStory', "projectID=$project->id&storyID=$story->id&confirm=yes");
                  echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"storyList\",confirmUnlinkStory)", '<i class="icon-unlink"></i>', '', "class='btn btn-link' title='{$lang->project->unlinkStory}'");
              }
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if($stories):?>
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
        <?php echo $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>

<div class="modal fade" id="linkStoryByPlan">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title"><i class="icon-file-text"></i> <?php echo $lang->project->linkStoryByPlan;?></h4>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('plan', $allPlans, '', "class='form-control chosen' id='plan'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->project->linkStory, "id='toTaskButton'");?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php js::set('checkedSummary', $lang->product->checkedSummary);?>
<?php js::set('projectID', $project->id);?>
<?php js::set('orderBy', $orderBy)?>
<?php include '../../common/view/footer.html.php';?>
