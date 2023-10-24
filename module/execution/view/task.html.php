<?php
/**
 * The task view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: task.html.php 4894 2013-06-25 01:28:39Z wyd621@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datatable.fix.html.php';
include '../../common/view/zui3dtable.html.php';
$cols     = $this->task->generateCol($orderBy);
$tasks    = $this->task->generateRow($tasks, $users, $executionID, $showBranch, $branchGroups, $modulePairs);
$sortLink = helper::createLink('execution', 'task', "executionID={$execution->id}&status={$status}&param={$param}&orderBy={orderBy}&recTotal={$recTotal}&recPerPage={$recPerPage}");
js::set('moduleID', $moduleID);
js::set('productID', $productID);
js::set('executionID', $executionID);
js::set('browseType', $browseType);
js::set('extra', ($execution->lifetime == 'ops' or in_array($execution->attribute, array('request', 'review'))) ? 'unsetStory' : '');

$task = reset($tasks);
$canBatchEdit         = common::hasPriv('task', 'batchEdit', !empty($task) ? $task : null);
$canBatchClose        = (common::hasPriv('task', 'batchClose', !empty($task) ? $task : null) and strtolower($browseType) != 'closed');
$canBatchCancel       = (common::hasPriv('task', 'batchCancel', !empty($task) ? $task : null) and strtolower($browseType) != 'cancel');
$canBatchChangeModule = common::hasPriv('task', 'batchChangeModule', !empty($task) ? $task : null);
$canBatchAssignTo     = common::hasPriv('task', 'batchAssignTo', !empty($task) ? $task : null);

$canBatchAction = ($canBatchEdit or $canBatchClose or $canBatchCancel or $canBatchChangeModule or $canBatchAssignTo);

if(!$canBatchAction) unset($cols[0]->checkbox);
/* Set unfold parent taskID. */
js::set('orderBy', $orderBy);
js::set('sortLink', $sortLink);
js::set('cols', json_encode($cols));
js::set('data', json_encode($tasks));
?>
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
    <form class="main-table table-task skip-iframe-modal not-watch" method="post" id='executionTaskForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right setting"></nav>
      </div>
      <div id="taskList" class="table"></div>
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
<?php js::set('pageSummary', $summary);?>
<?php js::set('checkedSummary', $lang->execution->checkedSummary);?>
<?php if(isonlybody()) js::set('modalWidthReset', 1200) ?>
<?php if($this->app->getViewType() == 'xhtml'):?>
<script>
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
</script>
<?php endif; ?>
<?php include '../../common/view/footer.html.php';?>
