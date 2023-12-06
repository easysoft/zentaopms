<?php
/**
 * The story view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: story.html.php 5117 2013-07-12 07:03:14Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
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
<?php js::set('projectID', $execution->project);?>
<?php js::set('storyType', $storyType);?>
<?php js::set('isDropMenu', (!$execution->hasProduct and !$execution->multiple and $config->URAndSR));?>
<?php js::set('confirmUnlinkStory', $lang->execution->confirmUnlinkStory)?>
<?php js::set('typeError', sprintf($this->lang->error->notempty, $this->lang->task->type))?>
<?php js::set('typeNotEmpty', sprintf($this->lang->error->notempty, $this->lang->task->type));?>
<?php js::set('hourPointNotEmpty', sprintf($this->lang->error->notempty, $this->lang->story->convertRelations));?>
<?php js::set('hourPointNotError', sprintf($this->lang->story->float, $this->lang->story->convertRelations));?>
<?php js::set('workingHourError', sprintf($this->lang->error->notempty, $this->lang->workingHour))?>
<?php js::set('linkedTaskStories', $linkedTaskStories);?>
<?php js::set('confirmStoryToTask', $lang->execution->confirmStoryToTask);?>
<style>
.btn-group a i.icon-plus, .btn-group a i.icon-link {font-size: 16px;}
.btn-group a.btn-secondary, .btn-group a.btn-primary {border-right: 1px solid rgba(255,255,255,0.2);}
.btn-group button.dropdown-toggle.btn-secondary, .btn-group button.dropdown-toggle.btn-primary {padding:6px;}
.export {margin-left: 0px !important;}
</style>
<?php if(!$execution->hasProduct && $execution->type != 'sprint'):?>
<style>
#executionStoryForm th.c-plan {display: none !important;}
#executionStoryForm td.c-plan {display: none !important;}
#customDatatable div.col[data-key=plan] {display: none !important;}
</style>
<?php endif;?>
<?php $isAllModules = empty($module->name) && empty($product->name) && empty($branch);?>
<?php $sidebarName  = $lang->tree->all;?>
<?php $removeBtn    = '';?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <?php
    if(!$isAllModules)
    {
        $sidebarName = isset($product) ? $product->name : (isset($branch) ? $branch : $module->name);
        $removeType  = isset($product) ? 'byproduct' : (isset($branch) ? 'bybranch' : 'bymodule');
        $removeLink  = inlink('story', "executionID=$execution->id&storyType=$storyType&orderBy=$orderBy&type=$removeType&param=0&recTotal=0&recPerPage={$pager->recPerPage}");
        $removeBtn   = html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
    }
    ?>
    <div class="title" title='<?php echo $sidebarName;?>'>
      <?php echo $sidebarName;?>
      <?php echo $removeBtn;?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php foreach($lang->execution->featureBar['story'] as $featureType => $label):?>
    <?php $active = $type == $featureType ? 'btn-active-text' : '';?>
    <?php $label  = "<span class='text'>$label</span>";?>
    <?php if($type == $featureType) $label .= " <span class='label label-light label-badge'>{$pager->recTotal}</span>";?>
    <?php echo html::a(inlink('story', "executionID=$execution->id&storyType=$storyType&orderBy=order_desc&type=$featureType"), $label, '', "class='btn btn-link $active'");?>
    <?php endforeach;?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::hasPriv('execution', 'storykanban') and $storyType == 'story'):?>
    <div class="btn-group panel-actions">
      <?php echo html::a($this->createLink('execution', 'story', "executionID=$execution->id&storyType=$storyType&orderBy=order_desc&type=all"), "<i class='icon-list'></i> &nbsp;", '', "class='btn btn-icon text-primary switchBtn' title='{$lang->execution->list}' data-type='bylist'");?>
      <?php echo html::a($this->createLink('execution', 'storykanban', "executionID=$execution->id"), "<i class='icon-kanban'></i> &nbsp;", '', "class='btn btn-icon switchBtn' title='{$lang->execution->kanban}' data-type='bykanban'");?>
    </div>
    <?php endif;?>
    <?php
    if($productID) common::printIcon('story', 'report', "productID=$productID&branchID=&storyType=$storyType&browseType=$type&moduleID=$param&chartType=pie&projectID=$execution->id", '', 'button', 'bar-chart muted');
    common::printLink('story', 'export', "productID=$productID&orderBy=id_desc&executionID=$execution->id&browseType=$type&storyType=$storyType", "<i class='icon icon-export muted'></i> " . $lang->story->export, '', "class='btn btn-link export iframe' data-width=620");

    $canLinkStory = ($execution->hasProduct or $execution->multiple);
    if(common::canModify('execution', $execution))
    {
        $this->lang->story->create = $this->lang->execution->createStory;

        if($productID)
        {
            $storyModuleID   = (int)$this->cookie->storyModuleParam;
            $createStoryLink = $this->createLink('story', 'create', "productID=$productID&branch=0&moduleID={$storyModuleID}&story=0&execution=$execution->id&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType");
            $batchCreateLink = $this->createLink('story', 'batchCreate', "productID=$productID&branch=0&moduleID={$storyModuleID}&story=0&execution=$execution->id&plan=0&storyType=$storyType");

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
            echo "<div class='btn-group dropdown' title='{$buttonTitle}'>";
            echo html::a($buttonLink, "<i class='icon icon-plus'></i> $buttonTitle", '', "class='btn btn-secondary $hidden' data-app='$app->tab'");

            if($common::hasPriv('story', 'create') and common::hasPriv('story', 'batchCreate'))
            {
                if(!($isAllProduct and count($products) > 1)) echo "<button type='button' class='btn btn-secondary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
                echo "<ul class='dropdown-menu pull-right'>";
                echo '<li>' . html::a($createStoryLink, $lang->story->create, '', "data-app=$app->tab") . '</li>';
                echo '<li>' . html::a($batchCreateLink, $lang->story->batchCreate, '', "data-app=$app->tab") . '</li>';
                echo '</ul>';
            }

            echo '</div>';
        }

        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("execution=$execution->id");
            echo html::a($this->createLink('tutorial', 'wizard', "module=execution&method=linkStory&params=$wizardParams"), "<i class='icon-link'></i> {$lang->execution->linkStory}",'', "class='btn btn-link link-story-btn'");
        }
        elseif($canLinkStory)  // The none-product and single execution cann't link stories.
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

            if(common::hasPriv('execution', 'linkStory') and common::hasPriv('execution', 'importPlanStories') and ($execution->hasProduct or $execution->type == 'sprint'))
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

<?php if($this->app->getViewType() == 'xhtml'):?>
<div id="xx-title">
  <strong>
  <?php echo ($this->project->getById($execution->project)->name . ' / ' . $this->execution->getByID($execution->id)->name) ?>
  </strong>
</div>
<?php endif;?>
<div id="mainContent" class="main-row fade">
  <div class='side-col' id='sidebar'>
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php if($productID and !$execution->hasProduct and !$execution->multiple) common::printLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=all", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
    <div id='queryBox' data-module='executionStory' class='cell <?php if($type =='bysearch') echo 'show';?>'></div>
    <?php if(empty($stories)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->story->noStory;?></span>
        <?php
        if(common::canModify('execution', $execution))
        {
            if($canLinkStory && common::hasPriv('execution', 'linkStory'))
            {
                echo html::a($this->createLink('execution', 'linkStory', "execution=$execution->id"), "<i class='icon icon-link'></i> " . $lang->execution->linkStory, '', "class='btn btn-info'");
            }
            else
            {
                $storyModuleID = (int)$this->cookie->storyModuleParam;
                if(common::hasPriv('story', 'create'))
                {
                    $createStoryLink = $this->createLink('story', 'create', "productID=$productID&branch=0&moduleID={$storyModuleID}&story=0&execution=$execution->id&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType");
                    echo html::a($createStoryLink, "<i class='icon icon-plus'></i> " . $lang->execution->createStory, '', "class='btn btn-info' data-app=$app->tab");
                }
                elseif(common::hasPriv('story', 'batchCreate'))
                {
                    $batchCreateLink = $this->createLink('story', 'batchCreate', "productID=$productID&branch=0&moduleID={$storyModuleID}&story=0&execution=$execution->id&plan=0&storyType=$storyType");
                    echo html::a($batchCreateLink, "<i class='icon icon-plus'></i> " . $lang->story->batchCreate, '', "class='btn btn-info' data-app=$app->tab");
                }
            }
        }
        ?>
      </p>
    </div>
    <?php else:?>
    <div class="table-header fixed-right">
      <nav class="btn-toolbar pull-right setting"></nav>
    </div>
    <form class='main-table table-story skip-iframe-modal' method='post' id='executionStoryForm'>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
      $vars = "executionID={$execution->id}&storyType=$storyType&orderBy=%s&type=$type&param=$param&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";

      if($useDatatable) include '../../common/view/datatable.html.php';
      if(!$execution->hasProduct and !$execution->multiple)
      {
          $planKey = array_search('plan', $config->story->datatable->defaultField);
          if($planKey) unset($config->story->datatable->defaultField[$planKey]);
      }

      $setting = $this->datatable->getSetting('execution');
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      $columns = 0;

      $checkObject = new stdclass();
      $checkObject->execution = $execution->id;

      $totalEstimate       = 0;
      $canBatchEdit        = common::hasPriv('story', 'batchEdit');
      $canBatchClose       = (common::hasPriv('story', 'batchClose') and $storyType != 'requirement');
      $canBatchChangeStage = (common::hasPriv('story', 'batchChangeStage') and $storyType != 'requirement');
      $canBatchUnlink      = common::hasPriv('execution', 'batchUnlinkStory');
      $canBatchToTask      = (common::hasPriv('story', 'batchToTask', $checkObject) and $storyType != 'requirement');
      $canBatchAssignTo    = common::hasPriv($storyType, 'batchAssignTo');

      $canBatchAction      = ($canBeChanged and ($canBatchEdit or $canBatchClose or $canBatchChangeStage or $canBatchUnlink or $canBatchToTask or $canBatchAssignTo));
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table tablesorter has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='storyList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
        <thead>
          <tr>
          <?php
          foreach($setting as $key => $value)
          {
              if(!$execution->hasProduct and $value->id == 'branch') continue;
              if(!$execution->hasProduct and !$execution->multiple and $value->id == 'plan') continue;
              if(!$execution->hasProduct and !$execution->multiple and $storyType == 'requirement' and $value->id == 'stage') continue;
              if(!$execution->hasProduct and !$execution->multiple and $storyType == 'requirement' and $value->id == 'taskCount') continue;
              if($value->show)
              {
                  $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
                  $columns ++;
              }
          }
          ?>
          </tr>
        </thead>
        <tbody id='storyTableList' class='sortable'>
          <?php foreach($stories as $key => $story):?>
          <?php
          $totalEstimate += $story->estimate;
          $story->from    = $this->app->tab;
          ?>
          <tr id="story<?php echo $story->id;?>" data-id='<?php echo $story->id;?>' data-order='<?php echo $story->order ?>' data-estimate='<?php echo $story->estimate?>' data-cases='<?php echo zget($storyCases, $story->id, 0)?>'>
          <?php foreach($setting as $key => $value)
          {
              if(!$execution->hasProduct and $value->id == 'branch') continue;
              if(!$execution->hasProduct and !$execution->multiple and $value->id == 'plan') continue;
              if(!$execution->hasProduct and !$execution->multiple and $storyType == 'requirement' and $value->id == 'stage') continue;
              if(!$execution->hasProduct and !$execution->multiple and $storyType == 'requirement' and $value->id == 'taskCount') continue;
              $this->story->printCell($value, $story, $users, $branchOption, $storyStages, $modulePairs, $storyTasks, $storyBugs, $storyCases, $useDatatable ? 'datatable' : 'table', $storyType, $execution, $showBranch);
          }
          ?>
          </tr>
          <?php if(!empty($story->children)):?>
          <?php $i = 0;?>
          <?php foreach($story->children as $key => $child):?>
          <?php $child->from = $this->app->tab;?>
          <?php $class  = $i == 0 ? ' table-child-top' : '';?>
          <?php $class .= ($i + 1 == count($story->children)) ? ' table-child-bottom' : '';?>
          <tr class='table-children<?php echo $class;?> parent-<?php echo $story->id;?>' data-id='<?php echo $child->id?>' data-status='<?php echo $child->status?>' data-estimate='<?php echo $child->estimate?>' data-cases='<?php echo zget($storyCases, $story->id, 0);?>'>
          <?php foreach($setting as $key => $value)
          {
              if(!$execution->hasProduct and $value->id == 'branch') continue;
              if(!$execution->hasProduct and !$execution->multiple and $value->id == 'plan') continue;
              if(!$execution->hasProduct and !$execution->multiple and $storyType == 'requirement' and $value->id == 'stage') continue;
              if(!$execution->hasProduct and !$execution->multiple and $storyType == 'requirement' and $value->id == 'taskCount') continue;
              $this->story->printCell($value, $child, $users, $branchOption, $storyStages, $modulePairs, $storyTasks, $storyBugs, $storyCases, $useDatatable ? 'datatable' : 'table', $storyType, $execution);
          }?>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
          <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!$useDatatable) echo '</div>';?>
      <div class='table-footer'>
        <?php if($canBatchAction):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class='table-actions btn-toolbar'>
          <div class='btn-group dropup'>
            <?php
            $disabled   = $canBatchEdit ? '' : "disabled='disabled'";
            $actionLink = $this->createLink('story', 'batchEdit', "productID=0&executionID=$execution->id&branch=0&storyType=$storyType");
            echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");
            ?>
            <?php if($canBatchToTask):?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu'>
              <?php
              echo "<li>" . html::a('#batchToTask', $lang->story->batchToTask, '', "data-toggle='modal' id='batchToTaskButton'") . "</li>";
              ?>
            </ul>
            <?php endif;?>
          </div>

          <?php if($canBatchAssignTo):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn assignedTo"><?php echo $lang->story->assignedTo;?> <span class="caret"></span></button>
            <?php
            $withSearch = count($users) > 10;
            $actionLink = $this->createLink('story', 'batchAssignTo', "storyType=$storyType");
            echo html::select('assignedTo', $users, '', 'class="hidden"');
            ?>
            <div class="dropdown-menu search-list<?php if($withSearch) echo ' search-box-sink';?>" data-ride="searchList">
              <?php if($withSearch):?>
              <?php $usersPinYin = common::convert2Pinyin($users);?>
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
              <?php endif;?>
              <div class="list-group">
              <?php foreach ($users as $key => $value):?>
              <?php
              if(empty($key) or $key == 'closed') continue;
              $searchKey = $withSearch ? ('data-key="' . zget($usersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
              echo html::a("javascript:$(\"#assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#storyList\")", $value, '', $searchKey);
              ?>
              <?php endforeach;?>
              </div>
            </div>
          </div>
          <?php endif;?>

          <?php
          if($canBatchClose)
          {
              $actionLink = $this->createLink('story', 'batchClose', "productID=0&executionID=$execution->id");
              echo html::commonButton($lang->close, "data-form-action='$actionLink'");
          }
          ?>
          <?php if($canBatchChangeStage):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->stageAB;?> <span class="caret"></span></button>
            <ul class='dropdown-menu <?php echo count($stories) == 1 ? 'stageBox' : '';?>'>
            <?php
            $lang->story->stageList[''] = $lang->null;
            foreach($lang->story->stageList as $key => $stage)
            {
                if(empty($key)) continue;
                if(strpos('wait|planned|projected', $key) !== false) continue;
                $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#storyList')\"") . "</li>";
            }
            ?>
            </ul>
          </div>
          <?php endif;?>
          <?php
          if($canLinkStory && common::hasPriv('execution', 'batchUnlinkStory'))
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
        <?php
        $linkStoryByPlanTips = $multiBranch ? sprintf($lang->execution->linkBranchStoryByPlanTips, $lang->project->branch) : $lang->execution->linkNormalStoryByPlanTips;
        $linkStoryByPlanTips = $execution->multiple ? $linkStoryByPlanTips : str_replace($lang->execution->common, $lang->projectCommon, $linkStoryByPlanTips);
        ?>
        <h4 class="modal-title"><?php echo $lang->execution->linkStoryByPlan;?></h4><?php echo '(' . $linkStoryByPlanTips . ')';?>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('plan', $allPlans, '', "class='form-control chosen' id='plan'");?>
          <?php $allPlans = array_filter($allPlans);?>
          <?php $disabled = empty($allPlans) ? 'disabled' : ''?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->execution->linkStory, "id='toTaskButton'", 'btn btn-primary ' . $disabled);?></span>
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
        <form method='post' class='not-watch' action='<?php echo $this->createLink('story', 'batchToTask', "executionID=$execution->id&projectID=$execution->project");?>'>
          <table class='table table-form'>
            <tr>
              <th class="<?php echo strpos($this->app->getClientLang(), 'zh') === false ? 'w-140px' : 'w-80px';?>"><?php echo $lang->task->type?></th>
              <td><?php echo html::select('type', $lang->task->typeList, '', "class='form-control chosen' required");?></td>
              <td></td>
            </tr>
            <?php if($lang->hourCommon !== $lang->workingHour):?>
            <tr>
              <th><?php echo $lang->story->one . $lang->hourCommon?></th>
              <td><div class='input-group'><span class='input-group-addon'><?php echo "≈";?></span><?php echo html::input('hourPointValue', '', "class='form-control' required");?> <span class='input-group-addon'><?php echo $lang->workingHour;?></span></div></td>
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
                <?php echo html::submitButton($lang->execution->next, '', 'btn btn-primary');?>
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
<?php if(!empty($useDatatable)):?>
$(function(){$('#executionStoryForm').table();})
<?php endif;?>
</script>
<?php if(commonModel::isTutorialMode()): ?>
<style>
#storyList .c-count, #storyList .c-category {display: none!important;}
</style>
<?php endif; ?>
<?php include '../../common/view/footer.html.php';?>
