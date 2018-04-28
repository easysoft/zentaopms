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
?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <?php echo html::commonButton('<i class="icon icon-caret-left"></i>', '', 'btn btn-icon btn-sm btn-info sidebar-toggle');?>
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
        if(strpos($menuType, 'QUERY') === 0)
        {
            $queryID = (int)substr($menuType, 5);
            echo html::a(inlink('task', "project=$projectID&type=bySearch&param=$queryID"), $menuItem->text, '', "id='{$menuType}Tab' class='btn btn-link'");
        }
        elseif($menuType != 'status')
        {
            $label   = "<span class='text'>{$menuItem->text}</span>";
            $label  .= $menuType == $browseType ? "<span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
            $active  = $menuType == $browseType ? 'btn-active-text' : '';
            echo html::a(inlink('task', "project=$projectID&type=$menuType"), $label, '', "id='{$menuType}' class='btn btn-link $active'");
        }
        elseif($menuType == 'status')
        {
            echo "<div class='btn-group'>";
            $taskBrowseType = isset($status) ? $this->session->taskBrowseType : '';
            $current        = zget($lang->project->statusSelects, $taskBrowseType, '');
            if(empty($current)) $current = $menuItem->text;
            echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link'");
            echo "<ul class='dropdown-menu'>";
            foreach ($lang->project->statusSelects as $key => $value)
            {
                if($key == '') continue;
                echo '<li' . ($key == $taskBrowseType ? " class='active'" : '') . '>';
                echo html::a($this->createLink('project', 'task', "project=$projectID&type=$key"), $value);
            }
            echo '</ul></div>';
        }
    }

    echo "<div class='btn-group'>";
    $groupBy = isset($groupBy) ? $groupBy : '';
    $current = zget($lang->project->groups, isset($groupBy) ? $groupBy : '', '');
    echo html::a('javascript:;', $current . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link'");
    echo "<ul class='dropdown-menu'>";
    foreach ($lang->project->groups as $key => $value)
    {
        if($key == '') continue;
        if($project->type == 'ops' && $key == 'story') continue;
        echo '<li' . ($key == $groupBy ? " class='active'" : '') . '>';
        common::printLink('project', 'groupTask', "project=$projectID&groupBy=$key", $value);
    }
    echo '</ul></div>';
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <div class='btn-group'>
      <?php
      if(!isset($browseType)) $browseType = '';
      if(!isset($orderBy))    $orderBy = '';
      common::printIcon('task', 'report', "project=$projectID&browseType=$browseType", '', 'button', 'bar-chart');
      ?>

      <div class="btn-group">
        <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export;?></span> <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <?php
          $misc = common::hasPriv('task', 'export') ? "class='export'" : "class=disabled";
          $link = common::hasPriv('task', 'export') ? $this->createLink('task', 'export', "project=$projectID&orderBy=$orderBy&type=$browseType") : '#';
          echo "<li>" . html::a($link, $lang->story->export, '', $misc) . "</li>";
          ?>
        </ul>
      </div>

      <div class="btn-group">
        <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-import muted"></i> <span class="text"><?php echo $lang->import;?></span> <span class="caret"></span></button>
        <ul class="dropdown-menu">
          <?php
          $misc = common::hasPriv('project', 'importTask') ? "class='import'" : "class=disabled";
          $link = common::hasPriv('project', 'importTask') ? $this->createLink('project', 'importTask', "project=$project->id") : '#';
          echo "<li>" . html::a($link, $lang->project->importTask, '', $misc) . "</li>";

          $misc = common::hasPriv('project', 'importBug') ? "class='import'" : "class=disabled";
          $link = common::hasPriv('project', 'importBug') ? $this->createLink('project', 'importBug', "project=$project->id") : '#';
          echo "<li>" . html::a($link, $lang->project->importBug, '', $misc) . "</li>";
          ?>
        </ul>
      </div>
    </div>
    <?php
    $link = $this->createLink('task', 'batchCreate', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''));
    if(common::hasPriv('task', 'batchCreate')) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->task->batchCreate}", '', "class='btn btn btn-secondary'");

    $link = $this->createLink('task', 'create', "project=$projectID" . (isset($moduleID) ? "&storyID=&moduleID=$moduleID" : ''));
    if(common::hasPriv('task', 'create')) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->task->create}", '', "class='btn btn-primary'");
    ?>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="side-col" id="sidebar">
    <div class="cell">
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php common::printLink('project', 'edit',    "projectID=$projectID", $lang->edit, '', "class='btn btn-info btn-wide'");?>
        <?php common::printLink('project', 'delete',  "projectID=$projectID&confirm=no", $lang->delete, 'hiddenwin', "class='btn btn-info btn-wide'");?>
        <?php common::printLink('tree', 'browsetask', "rootID=$projectID&productID=0", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
    <div class="cell" id="queryBox"></div>
    <form class="main-table table-task" data-ride="table" method="post" id='projectTaskForm'>
      <div class="table-header">
        <div class="table-statistic"><?php echo $summary;?></div>
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($this->config->datatable->$datatableId->mode) and $this->config->datatable->$datatableId->mode == 'datatable');
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
      <table class='table has-sort-head' id='taskList'>
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
          <?php foreach($task->children as $key => $child):?>
          <?php $class  = $key == 0 ? ' table-child-top' : '';?>
          <?php $class .= ($key + 1 == count($task->children)) ? ' table-child-bottom' : '';?>
          <tr class='text-center table-children<?php echo $class;?> parent-<?php echo $task->id;?>' data-id='<?php echo $child->id?>' data-status='<?php echo $child->status?>' data-estimate='<?php echo $child->estimate?>' data-consumed='<?php echo $child->consumed?>' data-left='<?php echo $child->left?>'>
            <?php foreach($customFields as $field) $this->task->printCell($field, $child, $users, $browseType, $branchGroups, $modulePairs, $useDatatable ? 'datatable' : 'table', true);?>
          </tr>
          <?php endforeach;?>
          <?php endif;?>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if($tasks):?>
      <div class="table-footer">
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <div class='btn-group dropup'>
            <?php 
            $canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($task) ? $task : null);
            $canBatchClose        = (common::hasPriv('task', 'batchClose', !empty($task) ? $task : null) && strtolower($browseType) != 'closedBy');
            $canBatchCancel       = common::hasPriv('task', 'batchCancel', !empty($task) ? $task : null);
            $canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($task) ? $task : null);
            $canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($task) ? $task : null);
            if(count($tasks))
            {
                $actionLink = $this->createLink('task', 'batchEdit', "projectID=$projectID");
                $disabled   = $canBatchEdit ? '' : "disabled='disabled'";

                echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");
                echo "<button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
                echo "<ul class='dropdown-menu'>";

                $actionLink = $this->createLink('task', 'batchClose');
                $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#moreAction')\"" : "class='disabled'";
                echo "<li>" . html::a('#', $lang->close, '', $misc) . "</li>";

                $actionLink = $this->createLink('task', 'batchCancel');
                $misc = $canBatchCancel ? "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#moreAction')\"" : "class='disabled'";
                echo "<li>" . html::a('#', $lang->task->cancel, '', $misc) . "</li>";

                if($canBatchChangeModule)
                {
                    $withSearch = count($modules) > 10;
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('javascript:;', $lang->task->moduleAB, '', "id='moduleItem'");
                    echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                    echo '<ul class="dropdown-list">';
                    foreach($modules as $moduleId => $module)
                    {
                        $actionLink = $this->createLink('task', 'batchChangeModule', "moduleID=$moduleId");
                        echo "<li class='option' data-key='$moduleID'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#moreAction')\"") . "</li>";
                    }
                    echo '</ul>';
                    if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                    echo '</div></li>';
                }
                else
                {
                    echo '<li>' . html::a('javascript:;', $lang->task->moduleAB, '', $misc) . '</li>';
                }

                /* Batch assign. */
                if($canBatchAssignTo)
                {
                    $withSearch = count($memberPairs) > 10;
                    $actionLink = $this->createLink('task', 'batchAssignTo', "projectID=$projectID");
                    echo html::select('assignedTo', $memberPairs, '', 'class="hidden"');
                    echo "<li class='dropdown-submenu'>";
                    echo html::a('javascript::', $lang->task->assignedTo, 'id="assignItem"');
                    echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                    echo '<ul class="dropdown-list">';
                    foreach ($memberPairs as $key => $value)
                    {
                        if(empty($key)) continue;
                        echo "<li class='option' data-key='$key'>" . html::a("javascript:$(\".table-actions #assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#moreAction\")", $value, '', '') . '</li>';
                    }
                    echo "</ul>";
                    if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                    echo "</div></li>";
                }
                echo "</ul>";
            }
            ?>
            </div>
          </div>
          <?php $pager->show('right', 'pagerjs');?>
        </div>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<?php js::set('checkedSummary', $lang->project->checkedSummary);?>
<?php js::set('replaceID', 'taskList')?>
<script>
$('#project<?php echo $projectID;?>').addClass('active')
$('#listTab').addClass('active')
$('#<?php echo ($browseType == 'bymodule' and $this->session->taskBrowseType == 'bysearch') ? 'all' : $this->session->taskBrowseType;?>Tab').addClass('active');
<?php if($browseType == 'bysearch'):?>
$shortcut = $('#QUERY<?php echo (int)$param;?>Tab');
if($shortcut.size() > 0)
{
    $shortcut.addClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').removeClass('show');
}
<?php endif;?>
statusActive = '<?php echo isset($lang->project->statusSelects[$this->session->taskBrowseType]);?>';
if(statusActive) $('#statusTab').addClass('active')
<?php if(isset($this->config->project->homepage) and $this->config->project->homepage != 'browse'):?>
$('#modulemenu .nav li.right:last').after("<li class='right'><a style='font-size:12px' href='javascript:setHomepage(\"project\", \"browse\")'><i class='icon icon-cog'></i> <?php echo $lang->homepage?></a></li>")
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
