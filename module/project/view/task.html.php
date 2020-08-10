<?php
/**
 * The task view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
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
js::set('projectID', $projectID);
js::set('browseType', $browseType);

/* Set unfold parent taskID. */
$unfoldTasks = isset($config->project->task->unfoldTasks) ? json_decode($config->project->task->unfoldTasks, true) : array();
$unfoldTasks = zget($unfoldTasks, $projectID, array());
js::set('unfoldTasks', $unfoldTasks);
js::set('unfoldAll',   $lang->project->treeLevel['all']);
js::set('foldAll',     $lang->project->treeLevel['root']);
?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php
      if(!empty($productID))
      {
          $product    = $this->product->getById($productID);
          $removeLink = $browseType == 'byproduct' ? inlink('task', "projectID=$projectID&browseType=$status&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("productBrowseParam")';
          echo $product->name;
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      elseif(!empty($moduleID))
      {
          $module     = $this->tree->getById($moduleID);
          $removeLink = $browseType == 'bymodule' ? inlink('task', "projectID=$projectID&browseType=$status&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("moduleBrowseParam")';
          echo $module->name;
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      else
      {
          $this->app->loadLang('tree');
          echo $this->lang->tree->all;
      }
      ?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php
    foreach(customModel::getFeatureMenu('project', 'task') as $menuItem)
    {
        if($project->type == 'ops' && $menuItem->name == 'needconfirm') continue;
        if(isset($menuItem->hidden)) continue;
        $menuType = $menuItem->name;
        if($menuType == 'QUERY')
        {
            $searchBrowseLink = $this->createLink('project', 'task', "project=$projectID&type=bySearch&param=%s");
            $isBySearch       = $this->session->taskBrowseType == 'bysearch';
            include '../../common/view/querymenu.html.php';
        }
        elseif($menuType != 'status' and $menuType != 'QUERY')
        {
            $label   = "<span class='text'>{$menuItem->text}</span>";
            $label  .= $menuType == $this->session->taskBrowseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
            $active  = $menuType == $this->session->taskBrowseType ? 'btn-active-text' : '';
            $title   = $menuType == 'needconfirm' ? "title='{$lang->task->storyChange}'" : '';
            echo html::a(inlink('task', "project=$projectID&type=$menuType"), $label, '', "id='{$menuType}' class='btn btn-link $active' $title");
        }
        elseif($menuType == 'status')
        {
            echo "<div class='btn-group' id='more'>";
            $taskBrowseType = isset($status) ? $this->session->taskBrowseType : '';
            $current        = $menuItem->text;
            $active         = '';
            if(isset($lang->project->statusSelects[$taskBrowseType]))
            {
                $current = "<span class='text'>{$lang->project->statusSelects[$taskBrowseType]}</span> <span class='label label-light label-badge'>{$pager->recTotal}</span>";
                $active  = 'btn-active-text';
            }
            echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $active'");
            echo "<ul class='dropdown-menu'>";
            foreach($lang->project->statusSelects as $key => $value)
            {
                if($key == '') continue;
                echo '<li' . ($key == $taskBrowseType ? " class='active'" : '') . '>';
                echo html::a($this->createLink('project', 'task', "project=$projectID&type=$key"), $value);
            }
            echo '</ul></div>';
        }
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php
    if(!isset($browseType)) $browseType = '';
    if(!isset($orderBy))    $orderBy = '';
    common::printIcon('task', 'report', "project=$projectID&browseType=$browseType", '', 'button', 'bar-chart muted');
    ?>

    <div class="btn-group dropdown-hover">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export;?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu pull-right" id='exportActionMenu'>
        <?php
        $class = common::hasPriv('task', 'export') ? '' : "class=disabled";
        $misc  = common::hasPriv('task', 'export') ? "class='export'" : "class=disabled";
        $link  = common::hasPriv('task', 'export') ? $this->createLink('task', 'export', "project=$projectID&orderBy=$orderBy&type=$browseType") : '#';
        echo "<li $class>" . html::a($link, $lang->task->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>

    <div class="btn-group dropdown-hover">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-import muted"></i> <span class="text"><?php echo $lang->import;?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu pull-right" id='importActionMenu'>
        <?php
        $class = common::hasPriv('project', 'importTask') ? '' : "class=disabled";
        $misc  = common::hasPriv('project', 'importTask') ? "class='import'" : "class=disabled";
        $link  = common::hasPriv('project', 'importTask') ? $this->createLink('project', 'importTask', "project=$project->id") : '#';
        echo "<li $class>" . html::a($link, $lang->project->importTask, '', $misc) . "</li>";

        $class = common::hasPriv('project', 'importBug') ? '' : "class=disabled";
        $misc  = common::hasPriv('project', 'importBug') ? "class='import'" : "class=disabled";
        $link  = common::hasPriv('project', 'importBug') ? $this->createLink('project', 'importBug', "project=$project->id") : '#';
        echo "<li $class>" . html::a($link, $lang->project->importBug, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php
    $checkObject = new stdclass();
    $checkObject->project = $projectID;
    ?>
    <?php if(!common::checkNotCN()):?>
    <?php
    $link = $this->createLink('task', 'batchCreate', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''));
    if(common::hasPriv('task', 'batchCreate', $checkObject)) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->task->batchCreate}", '', "class='btn btn btn-secondary'");

    $link = $this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=0&moduleID=$moduleID" : ""));
    if(common::hasPriv('task', 'create', $checkObject)) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->task->create}", '', "class='btn btn-primary'");
    ?>
    <?php else:?>
    <?php
    echo "<div class='btn-group dropdown-hover'>";
    $link = $this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=0&moduleID=$moduleID" : ""));
    if(common::hasPriv('task', 'create', $checkObject)) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->task->create} </span><span class='caret'>", '', "class='btn btn-primary'");
    ?>
    <ul class='dropdown-menu'>
      <?php $disabled = common::hasPriv('task', 'batchCreate') ? '' : "class='disabled'";?>
      <li <?php echo $disabled?>>
      <?php
        $batchLink = $this->createLink('task', 'batchCreate', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''));
        echo "<li>" . html::a($batchLink, "<i class='icon icon-plus'></i>" . $lang->task->batchCreate) . "</li>";
      ?>
      </li>
    </ul>
    <?php echo "</div>";?>
    <?php endif;?>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browsetask', "rootID=$projectID&productID=0", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
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
        <?php if(common::hasPriv('task', 'create', $checkObject)):?>
        <?php echo html::a($this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=0&moduleID=$moduleID" : "")), "<i class='icon icon-plus'></i> " . $lang->task->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class="main-table table-task skip-iframe-modal" method="post" id='projectTaskForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
      $vars         = "projectID=$project->id&status=$status&parma=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage";

      if($useDatatable) include '../../common/view/datatable.html.php';

      $customFields = $this->datatable->getSetting('project');
      if($project->type == 'ops')
      {
          foreach($customFields as $id => $customField)
          {
              if($customField->id == 'story') unset($customFields[$id]);
          }
      }
      $widths  = $this->datatable->setFixedFieldWidth($customFields);
      $columns = 0;
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='taskList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
        <thead>
          <tr>
          <?php
          foreach($customFields as $field)
          {
              if($field->show)
              {
                  $this->datatable->printHead($field, $orderBy, $vars);
                  $columns++;
              }
          }
          ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($tasks as $task):?>
          <tr data-id='<?php echo $task->id;?>' data-status='<?php echo $task->status?>' data-estimate='<?php echo $task->estimate?>' data-consumed='<?php echo $task->consumed?>' data-left='<?php echo $task->left?>'>
            <?php foreach($customFields as $field) $this->task->printCell($field, $task, $users, $browseType, $branchGroups, $modulePairs, $useDatatable ? 'datatable' : 'table');?>
          </tr>
          <?php if(!empty($task->children)):?>
          <?php $i = 0;?>
          <?php foreach($task->children as $key => $child):?>
          <?php $class  = $i == 0 ? ' table-child-top' : '';?>
          <?php $class .= ($i + 1 == count($task->children)) ? ' table-child-bottom' : '';?>
          <tr class='table-children<?php echo $class;?> parent-<?php echo $task->id;?>' data-id='<?php echo $child->id?>' data-status='<?php echo $child->status?>' data-estimate='<?php echo $child->estimate?>' data-consumed='<?php echo $child->consumed?>' data-left='<?php echo $child->left?>'>
            <?php foreach($customFields as $field) $this->task->printCell($field, $child, $users, $browseType, $branchGroups, $modulePairs, $useDatatable ? 'datatable' : 'table', true);?>
          </tr>
          <?php $i ++;?>
          <?php endforeach;?>
          <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!$useDatatable) echo '</div>';?>

      <div class="table-footer">
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <?php
          $canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($task) ? $task : null);
          $canBatchClose        = (common::hasPriv('task', 'batchClose', !empty($task) ? $task : null) && strtolower($browseType) != 'closedBy');
          $canBatchCancel       = common::hasPriv('task', 'batchCancel', !empty($task) ? $task : null);
          $canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($task) ? $task : null);
          $canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($task) ? $task : null);
          ?>
          <div class='btn-group dropup'>
            <?php
            $actionLink = $this->createLink('task', 'batchEdit', "projectID=$projectID");
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
            $actionLink = $this->createLink('task', 'batchAssignTo', "projectID=$projectID");
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
<script>
$(function()
{
    // Update table summary text
    var checkedSummary = '<?php echo $lang->project->checkedSummary?>';
    var pageSummary    = '<?php echo $lang->project->pageSummary?>';
    $('#projectTaskForm').table(
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
                if ($originTable)
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
</script>
<?php include '../../common/view/footer.html.php';?>
