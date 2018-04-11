<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datatable.fix.html.php';?>
<?php js::set('browseType', $browseType);?>
<?php js::set('productID', $productID);?>
<?php js::set('branch', $branch);?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <?php echo html::commonButton('<i class="icon icon-caret-left"></i>', '', 'btn btn-icon btn-sm btn-info sidebar-toggle');?>
    <div class="title">
      <?php
      echo $moduleName;
      if($moduleID)
      {
          $removeLink = $browseType == 'bymodule' ? inlink('browse', "productID=$productID&branch=$branch&browseType=$browseType&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("storyModule")';
          echo html::a($removeLink, "<i class='icon icon-remove'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php foreach(customModel::getFeatureMenu($this->moduleName, $this->methodName) as $menuItem):?>
    <?php if(isset($menuItem->hidden)) continue;?>
    <?php $menuBrowseType = strpos($menuItem->name, 'QUERY') === 0 ? 'bySearch' : $menuItem->name;?>
    <?php $param = strpos($menuItem->name, 'QUERY') === 0 ? '&param=' . (int)substr($menuItem->name, 5) : '';?>
    <?php if($menuItem->name == 'more'):?>
    <?php
        echo '<div class="btn-group">';
        echo html::a('javascript:;', $menuItem->text . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link'");
        echo "<ul class='dropdown-menu'>";
        foreach ($lang->product->moreSelects as $key => $value)
        {
            echo '<li' . ($key == $this->session->storyBrowseType ? " class='active'" : '') . '>';
            echo html::a($this->inlink('browse', "productID=$productID&branch=$branch&browseType=$key" . $param), $value);
        }
        echo '</ul></div>';
    ?>
    <?php else:?>
    <?php echo html::a($this->inlink('browse', "productID=$productID&branch=$branch&browseType=$menuBrowseType" . $param), "<span class='text'>$menuItem->text</span>" . ($menuItem->name == 'allstory' ? ' <span class="label label-light label-badge">' . $allCount . '</span>' : ''), '', "id='{$menuItem->name}Tab' class='btn btn-link" . ($this->session->storyBrowseType == $menuItem->name ? ' btn-active-text' : '') . "'");?>
    <?php endif;?>
    <?php endforeach;?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <div class="btn-toolbar pull-right">
    <?php common::printIcon('story', 'report', "productID=$productID&browseType=$browseType&branchID=$branch&moduleID=$moduleID", '', 'button', 'bar-chart'); ?>
    <div class="btn-group">
      <button class="btn btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu">
        <?php 
        $misc = common::hasPriv('story', 'export') ? "class='export'" : "class=disabled";
        $link = common::hasPriv('story', 'export') ?  $this->createLink('story', 'export', "productID=$productID&orderBy=$orderBy") : '#';
        echo "<li>" . html::a($link, $lang->story->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php if(common::hasPriv('story', 'batchCreate')) echo html::a($this->createLink('story', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID"), "<i class='icon icon-plus'></i> {$lang->story->batchCreate}", '', "class='btn btn btn-secondary'");?>
    <?php
    if(commonModel::isTutorialMode())
    {
        $wizardParams = helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID");
        $link = $this->createLink('tutorial', 'wizard', "module=story&method=create&params=$wizardParams");
        echo html::a($link, "<i class='icon icon-plus'></i> {$lang->story->create}", '', "class='btn btn-primary'");
    }
    else
    {
        $link = $this->createLink('story', 'create', "productID=$productID&branch=$branch&moduleID=$moduleID");
        if(common::hasPriv('story', 'create')) echo html::a($link, "<i class='icon icon-plus'></i> {$lang->story->create}", '', "class='btn btn-primary'");
    }
    ?>
  </div>
</div>
<div id="mainContent" class="main-row">
  <div class="side-col" id="sidebar">
    <div class="cell">
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browse', "rootID=$productID&view=story", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
    <div class="cell" id="queryBox"></div>
    <form class="main-table table-story" data-ride="table" method="post" id='productStoryForm'>
      <div class="table-header">
        <div class="table-statistic"><?php echo $summary;?></div>
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($this->config->datatable->$datatableId->mode) and $this->config->datatable->$datatableId->mode == 'datatable');
      $vars         = "productID=$productID&branch=$branch&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";

      if($useDatatable) include '../../common/view/datatable.html.php';
      if(!$useDatatable) include '../../common/view/tablesorter.html.php';
      $setting = $this->datatable->getSetting('product');
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      $columns = 0;
      ?>
      <table class="table has-sort-head" id='storyList'>
        <thead>
          <tr>
          <?php
          foreach($setting as $key => $value)
          {
              if($value->show)
              {
                  $this->datatable->printHead($value, $orderBy, $vars);
                  $columns ++;
              }
          }
          ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($stories as $story):?>
          <tr data-id='<?php echo $story->id?>' data-estimate='<?php echo $story->estimate?>' data-cases='<?php echo zget($storyCases, $story->id, 0);?>'>
            <?php foreach($setting as $key => $value) $this->story->printCell($value, $story, $users, $branches, $storyStages, $modulePairs, $storyTasks, $storyBugs, $storyCases, $useDatatable ? 'datatable' : 'table');?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if($stories):?>
      <div class="table-footer">
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <div class='btn-group dropup'>
            <?php
            $canBatchEdit  = common::hasPriv('story', 'batchEdit');
            $disabled   = $canBatchEdit ? '' : "disabled='disabled'";
            $actionLink = $this->createLink('story', 'batchEdit', "productID=$productID&projectID=0&branch=$branch");
            ?>
            <?php echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu'>
              <?php 
              $class = "class='disabled'";
              $canBatchClose = common::hasPriv('story', 'batchClose') && strtolower($browseType) != 'closedbyme' && strtolower($browseType) != 'closedstory';
              $actionLink    = $this->createLink('story', 'batchClose', "productID=$productID&projectID=0");
              $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink')\"" : $class;
              echo "<li>" . html::a('#', $lang->close, '', $misc) . "</li>";

              if(common::hasPriv('story', 'batchReview'))
              {
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->story->review, '', "id='reviewItem'");
                  echo "<ul class='dropdown-menu'>";
                  unset($lang->story->reviewResultList['']);
                  unset($lang->story->reviewResultList['revert']);
                  foreach($lang->story->reviewResultList as $key => $result)
                  {
                      $actionLink = $this->createLink('story', 'batchReview', "result=$key");
                      if($key == 'reject')
                      {
                          echo "<li class='dropdown-submenu'>";
                          echo html::a('#', $result, '', "id='rejectItem'");
                          echo "<ul class='dropdown-menu'>";
                          unset($lang->story->reasonList['']);
                          unset($lang->story->reasonList['subdivided']);
                          unset($lang->story->reasonList['duplicate']);

                          foreach($lang->story->reasonList as $key => $reason)
                          {
                              $actionLink = $this->createLink('story', 'batchReview', "result=reject&reason=$key");
                              echo "<li>";
                              echo html::a('#', $reason, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"");
                              echo "</li>";
                          }
                          echo '</ul></li>';
                      }
                      else
                      {
                        echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . '</li>';
                      }
                  }
                  echo '</ul></li>';
              }
              else
              {
                  echo '<li>' . html::a('javascript:;', $lang->story->review,  '', $class) . '</li>';
              }

              if(common::hasPriv('story', 'batchChangeBranch') and $this->session->currentProductType != 'normal')
              {
                  $withSearch = count($branches) > 8;
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->product->branchName[$this->session->currentProductType], '', "id='branchItem'");
                  echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                  echo "<ul class='dropdown-list'>";
                  foreach($branches as $branchID => $branchName)
                  {
                      $actionLink = $this->createLink('story', 'batchChangeBranch', "branchID=$branchID");
                      echo "<li class='option' data-key='$branchID'>" . html::a('#', $branchName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"") . "</li>";
                  }
                  echo '</ul>';
                  if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                  echo '</div></li>';
              }

              if(common::hasPriv('story', 'batchChangeModule'))
              {
                  $withSearch = count($modules) > 8;
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->story->moduleAB, '', "id='moduleItem'");
                  echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                  echo "<ul class='dropdown-list'>";
                  foreach($modules as $moduleId => $module)
                  {
                      $actionLink = $this->createLink('story', 'batchChangeModule', "moduleID=$moduleId");
                      echo "<li class='option' data-key='$moduleID'>" . html::a('#', empty($module) ? '/' : $module, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"") . "</li>";
                  }
                  echo '</ul>';
                  if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                  echo '</div></li>';
              }
              else
              {
                  echo '<li>' . html::a('javascript:;', $lang->story->moduleAB, '', $class) . '</li>';
              }

              if(common::hasPriv('story', 'batchChangePlan'))
              {
                  unset($plans['']);
                  $plans      = array(0 => $lang->null) + $plans;
                  $withSearch = count($plans) > 8;
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->story->planAB, '', "id='planItem'");
                  echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                  echo "<ul class='dropdown-list'>";
                  foreach($plans as $planID => $plan)
                  {
                      $actionLink = $this->createLink('story', 'batchChangePlan', "planID=$planID");
                      echo "<li class='option' data-key='$planID'>" . html::a('#', $plan, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
                  }
                  echo '</ul>';
                  if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                  echo '</div></li>';
              }
              else
              {
                  echo '<li>' . html::a('javascript:;', $lang->story->planAB, '', $class) . '</li>';
              }

              if(common::hasPriv('story', 'batchChangeStage'))
              {
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->story->stageAB, '', "id='stageItem'");
                  echo "<ul class='dropdown-menu'>";
                  $lang->story->stageList[''] = $lang->null;
                  foreach($lang->story->stageList as $key => $stage)
                  {
                      $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                      echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
                  }
                  echo '</ul></li>';
              }
              else
              {
                  echo '<li>' . html::a('javascript:;', $lang->story->stageAB, '', $class) . '</li>';
              }
              ?>
            </ul>
          </div>

          <?php if(common::hasPriv('story', 'batchAssignTo')):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->assignedTo;?> <span class="caret"></span></button>
            <div class="dropdown-menu search-list" data-ride="searchList">
              <?php
              $withSearch = count($users) > 10;
              $actionLink = $this->createLink('story', 'batchAssignTo', "productID=$productID");
              echo html::select('assignedTo', $users, '', 'class="hidden"');
              if(!$withSearch):
              ?>
              <div class="input-control search-box search-box-circle has-icon-left has-icon-right search-example">
                <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
              <?php endif;?>
              <div class="list-group">
              <?php foreach ($users as $key => $value):?>
              <?php
              if(empty($key) or $key == 'closed') continue;
              echo html::a("javascript:$(\"#assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\")", $value, '', '');
              ?>
              <?php endforeach;?>
              </div>
            </div>
          </div>
          <?php endif;?>
        </div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<?php js::set('checkedSummary', $lang->product->checkedSummary);?>
<script language='javascript'>
var moduleID = <?php echo $moduleID?>;
$('#module<?php echo $moduleID;?>').addClass('active');
<?php if($browseType == 'bysearch'):?>
$shortcut = $('#QUERY<?php echo (int)$param;?>Tab');
if($shortcut.size() > 0)
{
    $shortcut.addClass('btn-active-text');
    $('#bysearchTab').removeClass('btn-active-text');
    $('#querybox').removeClass('show');
}
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
