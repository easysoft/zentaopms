<?php
/**
 * The task view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: task.html.php 4894 2013-06-25 01:28:39Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/chart.html.php';
include '../../common/view/datepicker.html.php';
include '../../common/view/datatable.fix.html.php';
js::set('moduleID', $moduleID);
js::set('productID', $productID);
js::set('executionID', $executionID);
js::set('browseType', $browseType);
js::set('extra', ($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review'))) ? 'unsetStory' : '');

/* Set unfold parent taskID. */
$unfoldTasks = isset($config->execution->task->unfoldTasks) ? json_decode($config->execution->task->unfoldTasks, true) : array();
$unfoldTasks = zget($unfoldTasks, $executionID, array());
js::set('unfoldTasks', $unfoldTasks);
js::set('unfoldAll',   $lang->execution->treeLevel['all']);
js::set('foldAll',     $lang->execution->treeLevel['root']);
?>
<style>
body {margin-bottom: 25px;}
.btn-group a i.icon-plus {font-size: 16px;}
.btn-group a.btn-primary {border-right: 1px solid rgba(255,255,255,0.2);}
.btn-group button.dropdown-toggle.btn-primary {padding:6px;}
</style>
<div id="mainMenu" class="clearfix">
  <?php
  if(!empty($productID))
  {
      $removeLink = $browseType == 'byproduct' ? inlink('task', "executionID=$executionID&browseType=$status&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("productBrowseParam")';
      $moduleName = $product->name;
      $html       = $moduleName . html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
  }
  elseif(!empty($moduleID))
  {
      $module     = $this->tree->getById($moduleID);
      $removeLink = $browseType == 'bymodule' ? inlink('task', "executionID=$executionID&browseType=$status&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("moduleBrowseParam")';
      $moduleName = $module->name;
      $html       = $moduleName . html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
  }
  else
  {
      $this->app->loadLang('tree');
      $html = $moduleName = $this->lang->tree->all;
  }
  ?>
  <div id="sidebarHeader">
    <div class="title" title="<?php echo $moduleName?>"><?php echo $html;?></div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php
    common::sortFeatureMenu();
    foreach(customModel::getFeatureMenu('execution', 'task') as $menuItem)
    {
        if(($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review'))) and $menuItem->name == 'needconfirm') continue;
        if(isset($menuItem->hidden)) continue;
        $menuType = $menuItem->name;
        if($menuType == 'QUERY')
        {
            $searchBrowseLink = $this->createLink('execution', 'task', "execution=$executionID&type=bySearch&param=%s");
            $isBySearch       = $this->session->taskBrowseType == 'bysearch';
            include '../../common/view/querymenu.html.php';
        }
        elseif($menuType != 'status' and $menuType != 'QUERY')
        {
            $label   = "<span class='text'>{$menuItem->text}</span>";
            $label  .= $menuType == $this->session->taskBrowseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
            $active  = $menuType == $this->session->taskBrowseType ? 'btn-active-text' : '';
            $title   = $menuType == 'needconfirm' ? "title='{$lang->task->storyChange}'" : '';
            echo html::a(inlink('task', "execution=$executionID&type=$menuType"), $label, '', "id='{$menuType}' class='btn btn-link $active' $title");
        }
        elseif($menuType == 'status')
        {
            echo "<div class='btn-group' id='more'>";
            $taskBrowseType = isset($status) ? $this->session->taskBrowseType : '';
            $current        = $menuItem->text;
            $active         = '';
            $statusSelects  = isset($lang->execution->moreSelects['task']['status']) ? $lang->execution->moreSelects['task']['status'] : array();
            if(isset($statusSelects[$taskBrowseType]))
            {
                $current = "<span class='text'>{$statusSelects[$taskBrowseType]}</span> <span class='label label-light label-badge'>{$pager->recTotal}</span>";
                $active  = 'btn-active-text';
            }
            echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $active'");
            echo "<ul class='dropdown-menu'>";
            foreach($statusSelects as $key => $value)
            {
                if($key == '') continue;
                echo '<li' . ($key == $taskBrowseType ? " class='active'" : '') . '>';
                echo html::a($this->createLink('execution', 'task', "execution=$executionID&type=$key"), $value);
            }
            echo '</ul></div>';
        }
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <?php $taskCreateLink = $this->createLink('task', 'create', "executionID=$executionID" . (isset($moduleID) ? "&storyID=0&moduleID=$moduleID" : ""));?>
  <?php if(!isonlybody()): ?>
  <div class="btn-toolbar pull-right">
    <?php
    if(!isset($browseType)) $browseType = '';
    if(!isset($orderBy))    $orderBy = '';
    common::printIcon('task', 'report', "execution=$executionID&browseType=$browseType", '', 'button', 'bar-chart muted');
    ?>

    <div class="btn-group dropdown-hover">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export;?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu pull-right" id='exportActionMenu'>
        <?php
        $class = common::hasPriv('task', 'export') ? '' : "class=disabled";
        $misc  = common::hasPriv('task', 'export') ? "class='export'" : "class=disabled";
        $link  = common::hasPriv('task', 'export') ? $this->createLink('task', 'export', "execution=$executionID&orderBy=$orderBy&type=$browseType") : '#';
        echo "<li $class>" . html::a($link, $lang->task->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>

    <?php if(common::canModify('execution', $execution)):?>
    <div class="btn-group dropdown-hover">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-import muted"></i> <span class="text"><?php echo $lang->import;?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu pull-right" id='importActionMenu'>
        <?php
        if($execution->multiple)
        {
            $class = common::hasPriv('execution', 'importTask') ? '' : "class=disabled";
            $misc  = common::hasPriv('execution', 'importTask') ? "class='import'" : "class=disabled";
            $link  = common::hasPriv('execution', 'importTask') ? $this->createLink('execution', 'importTask', "execution=$execution->id") : '#';
            echo "<li $class>" . html::a($link, $lang->execution->importTask, '', $misc) . "</li>";
        }

        if($execution->lifetime != 'ops' and !in_array($execution->attribute, array('request', 'review')))
        {
            $class = common::hasPriv('execution', 'importBug') ? '' : "class=disabled";
            $misc  = common::hasPriv('execution', 'importBug') ? "class='import'" : "class=disabled";
            $link  = common::hasPriv('execution', 'importBug') ? $this->createLink('execution', 'importBug', "execution=$execution->id") : '#';
            echo "<li $class>" . html::a($link, $lang->execution->importBug, '', $misc) . "</li>";
        }
        ?>
      </ul>
    </div>
    <?php endif;?>
    <?php
    $checkObject = new stdclass();
    $checkObject->execution = $executionID;
    ?>
    <?php if($canBeChanged and (common::hasPriv('task', 'batchCreate', $checkObject) or common::hasPriv('task', 'create', $checkObject))):?>
    <div class='btn-group dropdown'>
      <?php
      if(commonModel::isTutorialMode())
      {
          $wizardParams   = helper::safe64Encode("executionID=$executionID" . (isset($moduleID) ? "&storyID=0&moduleID=$moduleID" : ""));
          $taskCreateLink = $this->createLink('tutorial', 'wizard', "module=task&method=create&params=$wizardParams");
      }
      echo html::a($taskCreateLink, "<i class='icon icon-plus'></i> {$lang->task->create}", '', "class='btn btn-primary'");
      ?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li><?php echo html::a($taskCreateLink, $lang->task->create);?></li>
        <li><?php echo html::a($this->createLink('task', 'batchCreate', "executionID=$executionID" . (isset($moduleID) ? "&storyID=0&moduleID=$moduleID" : "")), $lang->task->batchCreate);?></li>
      </ul>
    </div>
    <?php endif;?>
  </div>
  <?php endif;?>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
<div id="xx-title">
  <strong>
  <?php echo $projectName;?>
  </strong>
</div>
<?php endif;?>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browsetask', "rootID=$executionID&productID=0", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='task'></div>
    <?php if(empty($tasks)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->task->noTask;?></span>
        <?php if($canBeChanged and common::hasPriv('task', 'create') and empty($allTasks)):?>
        <?php echo html::a($taskCreateLink, "<i class='icon icon-plus'></i> " . $lang->task->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class="main-table table-task skip-iframe-modal" method="post" id='executionTaskForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
      $vars         = "executionID=$execution->id&status=$status&parma=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage";

      if($useDatatable) include '../../common/view/datatable.html.php';

      $customFields = $this->datatable->getSetting('execution');
      if($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review')))
      {
          foreach($customFields as $id => $customField)
          {
              if($customField->id == 'story') unset($customFields[$id]);
          }
      }
      $widths  = $this->datatable->setFixedFieldWidth($customFields);
      $columns = 0;

      $task = reset($tasks);
      $canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($task) ? $task : null);
      $canBatchClose        = (common::hasPriv('task', 'batchClose', !empty($task) ? $task : null) and strtolower($browseType) != 'closed');
      $canBatchCancel       = (common::hasPriv('task', 'batchCancel', !empty($task) ? $task : null) and strtolower($browseType) != 'cancel');
      $canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($task) ? $task : null);
      $canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($task) ? $task : null);

      $canBatchAction = ($canBatchEdit or $canBatchClose or $canBatchCancel or $canBatchChangeModule or $canBatchAssignTo);
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='taskList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
        <thead>
          <tr>
          <?php if($this->app->getViewType() == 'xhtml'):?>
          <?php
          foreach($customFields as $field)
          {
              if($field->id == 'name' || $field->id == 'id' || $field->id == 'pri' || $field->id == 'status')
              {
                  if($field->show)
                  {
                      $this->datatable->printHead($field, $orderBy, $vars, $canBatchAction);
                      $columns++;
                  }
              }
          }?>
          <?php else:?>
          <?php
          foreach($customFields as $field)
          {
              if($field->show)
              {
                  $this->datatable->printHead($field, $orderBy, $vars, $canBatchAction);
                  $columns++;
              }
          }
          ?>
          <?php endif;?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tasks as $task):?>
          <tr <?php if(!empty($task->children)) echo 'class="table-parent" '; ?>data-id='<?php echo $task->id;?>' data-status='<?php echo $task->status?>' data-estimate='<?php echo $task->estimate?>' data-consumed='<?php echo $task->consumed?>' data-left='<?php echo $task->left?>'>
            <?php if($this->app->getViewType() == 'xhtml'):?>
            <?php
            foreach($customFields as $field)
            {
                if($field->id == 'name' || $field->id == 'id' || $field->id == 'pri' || $field->id == 'status')
                {
                  $this->task->printCell($field, $task, $users, $browseType, $branchGroups, $modulePairs, $useDatatable ? 'datatable' : 'table');
                }
            }?>
            <?php else:?>
            <?php foreach($customFields as $field) $this->task->printCell($field, $task, $users, $browseType, $branchGroups, $modulePairs, $useDatatable ? 'datatable' : 'table', false, $showBranch);?>
            <?php endif;?>
          </tr>
          <?php if(!empty($task->children)):?>
          <?php $i = 0;?>
          <?php foreach($task->children as $key => $child):?>
          <?php $class  = $i == 0 ? ' table-child-top' : '';?>
          <?php $class .= ($i + 1 == count($task->children)) ? ' table-child-bottom' : '';?>
          <tr class='table-children<?php echo $class;?> parent-<?php echo $task->id;?>' data-id='<?php echo $child->id?>' data-status='<?php echo $child->status?>' data-estimate='<?php echo $child->estimate?>' data-consumed='<?php echo $child->consumed?>' data-left='<?php echo $child->left?>'>
            <?php if($this->app->getViewType() == 'xhtml'):?>
            <?php
            foreach($customFields as $field)
            {
                if($field->id == 'name' || $field->id == 'id' || $field->id == 'pri' || $field->id == 'status')
                $this->task->printCell($field, $child, $users, $browseType, $branchGroups, $modulePairs, $useDatatable ? 'datatable' : 'table', true);
            }?>
            <?php else:?>
            <?php foreach($customFields as $field) $this->task->printCell($field, $child, $users, $browseType, $branchGroups, $modulePairs, $useDatatable ? 'datatable' : 'table', true, $showBranch);?>
            <?php endif;?>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
          <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!$useDatatable) echo '</div>';?>

      <div class="table-footer">
        <?php if($canBatchAction):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class="table-actions btn-toolbar">
          <div class='btn-group dropup'>
            <?php
            $actionLink = $this->createLink('task', 'batchEdit', "executionID=$executionID");
            $disabled   = $canBatchEdit ? '' : "disabled='disabled'";

            echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");
            echo "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
            echo "<ul class='dropdown-menu'>";

            $class      = $canBatchClose ? '' : "class=disabled";
            $actionLink = $this->createLink('task', 'batchClose');
            $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#taskList')\"" : '';
            echo "<li $class>" . html::a('#', $lang->close, '', $misc) . "</li>";

            $class      = $canBatchCancel ? '' : "class=disabled";
            $actionLink = $this->createLink('task', 'batchCancel');
            $misc = $canBatchCancel ? "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#taskList')\"" : '';
            echo "<li $class>" . html::a('#', $lang->task->cancel, '', $misc) . "</li>";
            echo "</ul>";
            ?>
          </div>
          <?php if($canBatchChangeModule):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->moduleAB;?> <span class="caret"></span></button>
            <?php $withSearch = count($modules) > 10;?>
            <?php if($withSearch):?>
            <div class="dropdown-menu search-list search-box-sink" data-ride="searchList">
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
            <?php $modulesPinYin = common::convert2Pinyin($modules);?>
            <?php else:?>
            <div class="dropdown-menu search-list">
            <?php endif;?>
              <div class="list-group">
                <?php
                foreach($modules as $moduleId => $module)
                {
                    $searchKey = $withSearch ? ('data-key="' . zget($modulesPinYin, $module, '') . '"') : '';
                    $actionLink = $this->createLink('task', 'batchChangeModule', "moduleID=$moduleId");
                    echo html::a('#', $module, '', "$searchKey onclick=\"setFormAction('$actionLink', 'hiddenwin', '#taskList')\"");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>

          <?php if($canBatchAssignTo):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->assignedTo;?> <span class="caret"></span></button>
            <?php
            $withSearch = count($memberPairs) > 10;
            $actionLink = $this->createLink('task', 'batchAssignTo', "executionID=$executionID");
            echo html::select('assignedTo', $memberPairs, '', 'class="hidden"');
            if($withSearch):
            ?>
            <div class="dropdown-menu search-list search-box-sink" data-ride="searchList">
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
            <?php $membersPinYin = common::convert2Pinyin($memberPairs);?>
            <?php else:?>
            <div class="dropdown-menu search-list">
            <?php endif;?>
              <div class="list-group">
                <?php
                foreach($memberPairs as $key => $value)
                {
                    if(empty($key)) continue;
                    $searchKey = $withSearch ? ('data-key="' . zget($membersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
                    echo html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#taskList\")", $value, '', $searchKey);
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>
        </div>
        <div class="table-statistic"><?php echo $summary;?></div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<?php js::set('replaceID', 'taskList')?>
<?php if(isonlybody()) js::set('modalWidthReset', 1200) ?>
<script>
$(function()
{
    /* Update table summary text. */
    var checkedSummary = '<?php echo $lang->execution->checkedSummary?>';
    var pageSummary    = '<?php echo $lang->execution->pageSummary?>';
    $('#executionTaskForm').table(
    {
        statisticCreator: function(table)
        {
            var $table = table.getTable();
            var $checkedRows = $table.find(table.isDataTable ? '.datatable-row-left.checked' : 'tbody>tr.checked');
            var $originTable = table.isDataTable ? table.$.find('.datatable-origin') : null;
            var checkedTotal = $checkedRows.length;
            var $rows = checkedTotal ? $checkedRows : $table.find(table.isDataTable ? '.datatable-rows .datatable-row-left' : 'tbody>tr');

            var checkedWait     = 0;
            var checkedDoing    = 0;
            var checkedEstimate = 0;
            var checkedConsumed = 0;
            var checkedLeft     = 0;
            var taskIdList      = [];
            $rows.each(function()
            {
                var $row = $(this);
                if($originTable)
                {
                    $row = $originTable.find('tbody>tr[data-id="' + $row.data('id') + '"]');
                }
                var data = $row.data();
                taskIdList.push(data.id);

                var status = data.status;
                if(status === 'wait') checkedWait++;
                if(status === 'doing') checkedDoing++;

                var canStatistics = false;
                if(!$row.hasClass('table-children'))
                {
                    canStatistics = true;
                }
                else
                {
                    /* Fix bug #2579. When only child task is checked then statistics it. */
                    var parentID = 0;
                    var classes  = $row.attr('class').split(' ');
                    for(i in classes)
                    {
                        if(classes[i].indexOf('parent-') >= 0) parentID = classes[i].replace('parent-', '');
                    }

                    if(parentID && taskIdList.indexOf(parseInt(parentID)) < 0) canStatistics = true;
                }

                if(canStatistics)
                {
                    checkedEstimate += Number(data.estimate);
                    checkedConsumed += Number(data.consumed);
                    if(status != 'cancel' && status != 'closed') checkedLeft += Number(data.left);
                }
            });
            return (checkedTotal ? checkedSummary : pageSummary).replace('%total%', $rows.length).replace('%wait%', checkedWait)
              .replace('%doing%', checkedDoing)
              .replace('%estimate%', checkedEstimate.toFixed(1))
              .replace('%consumed%', checkedConsumed.toFixed(1))
              .replace('%left%', checkedLeft.toFixed(1));
        }
    })
});

<?php if($this->app->getViewType() == 'xhtml'):?>
$(function()
{
    function handleClientReady()
    {
        if(!window.adjustXXCViewHeight) return;
        window.adjustXXCViewHeight(null, true);
    }
    if(window.xuanReady) handleClientReady();
    else $(window).on('xuan-ready', handleClientReady);
});
<?php endif; ?>
</script>
<?php include '../../common/view/footer.html.php';?>
