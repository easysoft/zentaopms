<?php
/**
 * The story view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: story.html.php 5117 2013-07-12 07:03:14Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php $canOrder = common::hasPriv('execution', 'storySort');?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datatable.fix.html.php';?>
<?php if($canOrder) include '../../common/view/sortable.html.php';?>
<?php js::set('moduleID', $this->cookie->storyModuleParam);?>
<?php js::set('productID', $this->cookie->storyProductParam);?>
<?php js::set('branchID', str_replace(',', '_', $this->cookie->storyBranchParam));?>
<?php js::set('executionID', $execution->id);?>
<?php js::set('confirmUnlinkStory', $lang->execution->confirmUnlinkStory)?>
<?php js::set('typeError', sprintf($this->lang->error->notempty, $this->lang->task->type))?>
<?php js::set('workingHourError', sprintf($this->lang->error->notempty, $this->lang->workingHour))?>
<style>
.btn-group a i.icon-plus, .btn-group a i.icon-link {font-size: 16px;}
.btn-group a.btn-secondary, .btn-group a.btn-primary {border-right: 1px solid rgba(255,255,255,0.2);}
.btn-group button.dropdown-toggle.btn-secondary, .btn-group button.dropdown-toggle.btn-primary {padding:6px;}
</style>
<div id="mainMenu" class="clearfix">
  <?php if(!empty($module->name) or !empty($product->name) or !empty($branch)):?>
  <div id="sidebarHeader">
    <?php
    $sidebarName = isset($product) ? $product->name : (isset($branch) ? $branch : $module->name);
    $removeType  = isset($product) ? 'byproduct' : (isset($branch) ? 'bybranch' : 'bymodule');
    $removeLink  = inlink('story', "executionID=$execution->id&orderBy=$orderBy&type=$removeType&param=0&recTotal=0&recPerPage={$pager->recPerPage}");
    ?>
    <div class="title" title='<?php echo $sidebarName;?>'>
      <?php echo $sidebarName;?>
      <?php echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");?>
    </div>
  </div>
  <?php endif;?>
  <div class="btn-toolbar pull-left">
    <?php
    if(common::hasPriv('execution', 'story'))
    {
        echo html::a($this->createLink('execution', 'story', "executionID=$execution->id&orderBy=order_desc&type=all"), "<span class='text'>{$lang->story->allStories}</span>" . ($type == 'all' ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link" . ($type == 'all' ? " btn-active-text" : '') . "'");
        echo html::a($this->createLink('execution', 'story', "executionID=$execution->id&orderBy=order_desc&type=unclosed"), "<span class='text'>{$lang->story->unclosed}</span>" . ($type == 'unclosed' ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : ''), '', "class='btn btn-link" . ($type == 'unclosed' ? " btn-active-text" : '') . "'");
    }
    if(common::hasPriv('execution', 'storykanban')) echo html::a($this->createLink('execution', 'storykanban', "executionID=$execution->id"), "<span class='text'>{$lang->execution->kanban}</span>", '', "class='btn btn-link'");
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php
    common::printLink('story', 'export', "productID=$productID&orderBy=id_desc&executionID=$execution->id", "<i class='icon icon-export muted'></i> " . $lang->story->export, '', "class='btn btn-link export iframe' data-app='execution'");

    if(common::canModify('execution', $execution))
    {
        $this->lang->story->create = $this->lang->execution->createStory;

        if($productID and !$this->loadModel('story')->checkForceReview())
        {
            $storyModuleID   = (int)$this->cookie->storyModuleParam;
            $createStoryLink = $this->createLink('story', 'create', "productID=$productID&branch=0&moduleID={$storyModuleID}&story=0&execution=$execution->id");
            $batchCreateLink = $this->createLink('story', 'batchCreate', "productID=$productID&branch=0&moduleID={$storyModuleID}&story=0&execution=$execution->id");

            $buttonLink  = '';
            $buttonTitle = '';
            if(common::hasPriv('story', 'batchCreate'))
            {
                $buttonLink  = $batchCreateLink;
                $buttonTitle = $lang->story->batchCreate;
            }
            if(common::hasPriv('story', 'create'))
            {
                $buttonLink  = $createStoryLink;
                $buttonTitle = $lang->story->create;
            }

            $hidden = empty($buttonLink) ? 'hidden' : '';
            echo "<div class='btn-group dropdown'>";
            echo html::a($buttonLink, "<i class='icon icon-plus'></i> $buttonTitle", '', "class='btn btn-secondary $hidden' data-app='execution'");

            if($common::hasPriv('story', 'create') and common::hasPriv('story', 'batchCreate'))
            {
                echo "<button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
                echo "<ul class='dropdown-menu pull-right'>";
                echo '<li>' . html::a($createStoryLink, $lang->story->create, '', "data-app='execution'") . '</li>';
                echo '<li>' . html::a($batchCreateLink, $lang->story->batchCreate, '', "data-app='execution'") . '</li>';
                echo '</ul>';
            }

            echo '</div>';
        }

        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("execution=$execution->id");
            echo html::a($this->createLink('tutorial', 'wizard', "module=execution&method=linkStory&params=$wizardParams"), "<i class='icon-link'></i> {$lang->execution->linkStory}",'', "class='btn btn-link link-story-btn'");
        }
        else
        {
            echo "<div class='btn-group dropdown'>";

            $buttonLink  = '';
            $buttonTitle = '';
            $dataToggle  = '';
            if(common::hasPriv('execution', 'importPlanStories'))
            {
                $buttonLink  = '#linkStoryByPlan';
                $buttonTitle = $lang->execution->linkStoryByPlan;
                $dataToggle  = 'data-toggle="modal"';
            }
            if(common::hasPriv('execution', 'linkStory'))
            {
                $buttonLink  = inlink('linkStory', "execution=$execution->id");
                $buttonTitle = $lang->execution->linkStory;
                $dataToggle  = '';
            }
            $hidden = empty($buttonLink) ? 'hidden' : '';
            echo html::a($buttonLink, "<i class='icon-link'></i> $buttonTitle", '', "class='btn btn-primary $hidden' $dataToggle");

            if(common::hasPriv('execution', 'linkStory') and common::hasPriv('execution', 'importPlanStories'))
            {
                echo "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
                echo "<ul class='dropdown-menu pull-right'>";
                echo '<li>' . html::a(inlink('linkStory', "execution=$execution->id"), $lang->execution->linkStory). "</li>";
                echo '<li>' . html::a('#linkStoryByPlan', $lang->execution->linkStoryByPlan, '', 'data-toggle="modal"') . "</li>";
                echo '</ul>';
            }

            echo '</div>';
        }
    }
    ?>
  </div>
</div>

<div id="mainContent" class="main-row fade">
  <div class='side-col' id='sidebar'>
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $moduleTree;?>
      <div class="text-center"></div>
    </div>
  </div>
  <div class="main-col">
    <div id='queryBox' data-module='executionStory' class='cell <?php if($type =='bysearch') echo 'show';?>'></div>
    <?php if(empty($stories)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->story->noStory;?></span>
        <?php if(common::canModify('execution', $execution) and common::hasPriv('execution', 'linkStory')):?>
        <?php echo html::a($this->createLink('execution', 'linkStory', "execution=$execution->id"), "<i class='icon icon-link'></i> " . $lang->execution->linkStory, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table table-story skip-iframe-modal' method='post' id='executionStoryForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <table class='table tablesorter has-sort-head' id='storyList'>
        <thead>
          <tr>
          <?php
          $totalEstimate       = 0;
          $canBatchEdit        = common::hasPriv('story', 'batchEdit');
          $canBatchClose       = common::hasPriv('story', 'batchClose');
          $canBatchChangeStage = common::hasPriv('story', 'batchChangeStage');
          $canBatchUnlink      = common::hasPriv('execution', 'batchUnlinkStory');
          $canBatchToTask      = common::hasPriv('story', 'batchToTask');
          $canBatchAction      = ($canBeChanged and ($canBatchEdit or $canBatchClose or $canBatchChangeStage or $canBatchUnlink or $canBatchToTask));
          ?>
          <?php $vars = "executionID={$execution->id}&orderBy=%s&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
            <th class='c-id {sorter:false}'>
              <?php if($canBatchAction):?>
              <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                <label></label>
              </div>
              <?php endif;?>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <?php if($canOrder):?>
            <th class='w-60px c-sort {sorter:false}'> <?php common::printOrderLink('order',      $orderBy, $vars, $lang->execution->orderAB);?></th>
            <?php endif;?>
            <th class='c-pri {sorter:false}'>  <?php common::printOrderLink('pri',        $orderBy, $vars, $lang->priAB);?></th>
            <th class='c-name {sorter:false}'> <?php common::printOrderLink('title',      $orderBy, $vars, $lang->execution->storyTitle);?></th>
            <th class='c-user {sorter:false}'> <?php common::printOrderLink('openedBy',   $orderBy, $vars, $lang->openedByAB);?></th>
            <th class='c-user {sorter:false}'> <?php common::printOrderLink('assignedTo', $orderBy, $vars, $lang->assignedToAB);?></th>
            <th class='c-estimate w-80px {sorter:false} text-right'> <?php common::printOrderLink('estimate',   $orderBy, $vars, $lang->story->estimateAB);?></th>
            <th class='c-status {sorter:false}'> <?php common::printOrderLink('status',     $orderBy, $vars, $lang->statusAB);?></th>
            <th class='c-stage w-70px {sorter:false}'> <?php common::printOrderLink('stage',      $orderBy, $vars, $lang->story->stageAB);?></th>
            <th title='<?php echo $lang->story->taskCount?>' class='w-30px'><?php echo $lang->story->taskCountAB;?></th>
            <th title='<?php echo $lang->story->bugCount?>'  class='w-30px'><?php echo $lang->story->bugCountAB;?></th>
            <th title='<?php echo $lang->story->caseCount?>' class='w-30px'><?php echo $lang->story->caseCountAB;?></th>
            <th class='c-actions-4 text-center {sorter:false}'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody id='storyTableList' class='sortable'>
          <?php foreach($stories as $key => $story):?>
          <?php
          $storyLink      = $this->createLink('story', 'view', "storyID=$story->id&version=$story->version&param=$execution->id");
          $totalEstimate += $story->estimate;
          ?>
          <tr id="story<?php echo $story->id;?>" data-id='<?php echo $story->id;?>' data-order='<?php echo $story->order ?>' data-estimate='<?php echo $story->estimate?>' data-cases='<?php echo zget($storyCases, $story->id, 0)?>'>
            <td class='cell-id'>
              <?php if($canBatchAction):?>
              <?php echo html::checkbox('storyIdList', array($story->id => '')) . html::a(helper::createLink('story', 'view', "storyID=$story->id&version=$story->version&from=execution&param=$execution->id"), sprintf('%03d', $story->id), null, "data-app='execution'");?>
              <?php else:?>
              <?php printf('%03d', $story->id);?>
              <?php endif;?>
            </td>
            <?php if($canOrder):?>
            <td class='sort-handler c-sort'><i class='icon-move'></i></td>
            <?php endif;?>
            <td class='c-pri'><span class='label-pri <?php echo 'label-pri-' . $story->pri?>' title='<?php echo zget($lang->story->priList, $story->pri, $story->pri);?>'><?php echo zget($lang->story->priList, $story->pri, $story->pri);?></span></td>
            <td class='c-name' title="<?php echo $story->title?>">
              <?php if(isset($branchGroups[$story->product][$story->branch])) echo "<span class='label label-outline label-badge'>" . $branchGroups[$story->product][$story->branch] . '</span>';?>
              <?php if(!empty($story->module) and isset($modulePairs[$story->module])) echo "<span class='label label-gray label-badge'>{$modulePairs[$story->module]}</span> ";?>
              <?php if($story->parent > 0) echo "<span class='label'>{$lang->story->childrenAB}</span>";?>
              <?php echo html::a($storyLink,$story->title, null, "style='color: $story->color' data-app='execution'");?>
            </td>
            <td class='c-user' title='<?php echo zget($users, $story->openedBy);?>'><?php echo zget($users, $story->openedBy);?></td>
            <td class='c-user' title='<?php echo zget($users, $story->assignedTo);?>'><?php echo zget($users, $story->assignedTo);?></td>
            <td class='c-estimate text-right' title="<?php echo $story->estimate . ' ' . $lang->hourCommon;?>"><?php echo $story->estimate . $config->hourUnit;?></td>
            <?php $status = $this->processStatus('story', $story);?>
            <td class='c-status' title='<?php echo $status;?>'>
              <span class='status-story status-<?php echo $story->status;?>'><?php echo $status;?></span>
            </td>
            <td class='c-stage'><?php echo $lang->story->stageList[$story->stage];?></td>
            <td class='linkbox'>
              <?php
              $tasksLink = $this->createLink('story', 'tasks', "storyID=$story->id&executionID=$execution->id");
              $storyTasks[$story->id] > 0 ? print(html::a($tasksLink, $storyTasks[$story->id], '', 'class="iframe"')) : print(0);
              ?>
            <td>
              <?php
              $bugsLink = $this->createLink('story', 'bugs', "storyID=$story->id&executionID=$execution->id");
              $storyBugs[$story->id] > 0 ? print(html::a($bugsLink, $storyBugs[$story->id], '', 'class="iframe"')) : print(0);
              ?>
            </td>
            <td>
              <?php
              $casesLink = $this->createLink('story', 'cases', "storyID=$story->id&executionID=$execution->id");
              $storyCases[$story->id] > 0 ? print(html::a($casesLink, $storyCases[$story->id], '', 'class="iframe"')) : print(0);
              ?>
            </td>
            <td class='c-actions'>
              <?php
              $hasDBPriv = common::hasDBPriv($execution, 'execution');
              if($canBeChanged)
              {
                  $param = "executionID={$execution->id}&story={$story->id}&moduleID={$story->module}";

                  $lang->task->create = $lang->execution->wbs;
                  if(commonModel::isTutorialMode())
                  {
                      $wizardParams = helper::safe64Encode($param);
                      echo html::a($this->createLink('tutorial', 'wizard', "module=task&method=create&params=$wizardParams"), "<i class='icon-plus'></i>",'', "class='btn btn-task-create' title='{$lang->execution->wbs}' data-app='execution'");
                  }
                  else
                  {
                      if($hasDBPriv) common::printIcon('task', 'create', $param, '', 'list', 'plus', '', 'btn-task-create');
                  }

                  $lang->task->batchCreate = $lang->execution->batchWBS;
                  if($hasDBPriv) common::printIcon('task', 'batchCreate', "executionID={$execution->id}&story={$story->id}", '', 'list', 'pluses');

                  $lang->testcase->batchCreate = $lang->testcase->create;
                  if($productID and $hasDBPriv and common::hasPriv('testcase', 'create'))
                  {
                      echo html::a($this->createLink('testcase', 'create', "productID=$story->product&branch=$story->branch&moduleID=$story->module&form=&param=0&storyID=$story->id"), '<i class="icon-testcase-create icon-sitemap"></i>', '', "title='{$lang->testcase->create}' data-app='qa'");
                  }

                  if($canBeChanged and common::hasPriv('execution', 'unlinkStory', $execution))
                  {
                      common::printIcon('execution', 'unlinkStory', "executionID=$execution->id&storyID=$story->id&confirm=no", '', 'list', 'unlink', 'hiddenwin');
                  }
              }
              ?>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <div class='table-footer'>
        <?php if($canBatchAction):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class='table-actions btn-toolbar'>
          <div class='btn-group dropup'>
            <?php
            $disabled   = $canBatchEdit ? '' : "disabled='disabled'";
            $actionLink = $this->createLink('story', 'batchEdit', "productID=0&executionID=$execution->id");
            echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");
            ?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu'>
              <?php
              $class = $canBatchToTask ? '' : "class='hidden'";
              echo "<li $class>" . html::a('#batchToTask', $lang->story->batchToTask, '', "data-toggle='modal' id='batchToTaskButton'") . "</li>";
              ?>
            </ul>
          </div>
          <?php
          if($canBatchClose)
          {
              $actionLink = $this->createLink('story', 'batchClose', "productID=0&executionID=$execution->id");
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
                if(empty($key)) continue;
                if(strpos('wait|planned|projected', $key) !== false) continue;
                $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"") . "</li>";
            }
            echo '</ul>';
            ?>
          </div>
          <?php endif;?>
          <?php
          if(common::hasPriv('execution', 'batchUnlinkStory'))
          {
              $actionLink = $this->createLink('execution', 'batchUnlinkStory', "executionID=$execution->id");
              echo html::commonButton($lang->execution->unlinkStoryAB, "data-form-action='$actionLink'");
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
        <h4 class="modal-title"><?php echo $lang->execution->linkStoryByPlan;?></h4><?php echo '(' . $lang->execution->linkStoryByPlanTips . ')';?>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('plan', $allPlans, '', "class='form-control chosen' id='plan'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->execution->linkStory, "id='toTaskButton'", 'btn btn-primary');?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="batchToTask">
  <div class="modal-dialog mw-600px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->story->batchToTask;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('story', 'batchToTask', "executionID=$execution->id");?>'>
          <table class='table table-form'>
            <tr>
              <th class="<?php echo strpos($this->app->getClientLang(), 'zh') === false ? 'w-140px' : 'w-80px';?>"><?php echo $lang->task->type?></th>
              <td><?php echo html::select('type', $lang->task->typeList, '', "class='form-control chosen' required");?></td>
              <td></td>
            </tr>
            <?php if($lang->hourCommon !== $lang->workingHour):?>
            <tr>
              <th><?php echo $lang->story->one . $lang->hourCommon?></th>
              <td><div class='input-group'><span class='input-group-addon'><?php echo "=";?></span><?php echo html::input('hourPointValue', '', "class='form-control' required");?> <span class='input-group-addon'><?php echo $lang->workingHour;?></span></div></td>
              <td></td>
            </tr>
            <?php endif;?>
            <tr>
              <th><?php echo $lang->story->field;?></th>
              <td colspan='2'><?php echo html::checkbox('fields', $lang->story->convertToTask->fieldList, '', 'checked');?></td>
            </tr>
            <tr>
              <td colspan='3'><div class='alert alert-info no-margin'><?php echo $lang->story->batchToTaskTips?></div></td>
            </tr>
            <tr>
              <td colspan='3' class='text-center'>
                <?php echo html::hidden('storyIdList', '');?>
                <?php echo html::submitButton($lang->story->toTask, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<?php js::set('checkedSummary', $lang->product->checkedSummary);?>
<?php js::set('executionID', $execution->id);?>
<?php js::set('orderBy', $orderBy)?>
<script>
$(function()
{
    /* Remove datatable setting. */
    $('#executionStoryForm .table-header .btn-toolbar.pull-right').remove();

    /* Update table summary text. */
    <?php $storyCommon = $lang->SRCommon;?>
    var checkedSummary = '<?php echo str_replace('%storyCommon%', $storyCommon, $lang->product->checkedSummary)?>';
    $('#executionStoryForm').table(
    {
        statisticCreator: function(table)
        {
            var $checkedRows = table.getTable().find(table.isDataTable ? '.datatable-row-left.checked' : 'tbody>tr.checked');
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var checkedTotal = $checkedRows.length;
            if(!checkedTotal) return;

            var checkedEstimate = 0;
            var checkedCase     = 0;
            $checkedRows.each(function()
            {
                var $row = $(this);
                if ($originTable)
                {
                    $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');
                }
                var data = $row.data();
                checkedEstimate += data.estimate;
                if(data.cases > 0) checkedCase += 1;
            });
            var rate = Math.round(checkedCase / checkedTotal * 10000 / 100) + '' + '%';
            return checkedSummary.replace('%total%', checkedTotal)
                  .replace('%estimate%', checkedEstimate.toFixed(1))
                  .replace('%rate%', rate);
        }
    });
});
</script>
<?php include '../../common/view/footer.html.php';?>
