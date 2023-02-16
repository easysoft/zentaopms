<?php
/**
 * The browse view file of product module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     product
 * @version     $Id: browse.html.php 4909 2013-06-26 07:23:50Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php include $this->app->getModuleRoot() . '/common/view/header.html.php';?>
<?php include $this->app->getModuleRoot() . '/common/view/datatable.fix.html.php';?>
<style>
body {margin-bottom: 25px;}
#mainMenu .btn-toolbar .btn-group .dropdown-menu .btn-active-text:hover .text {color: #fff;}
#mainMenu .btn-toolbar .btn-group .dropdown-menu .btn-active-text:hover .text:after {border-bottom: unset;}
.body-modal #mainMenu>.btn-toolbar {width: auto;}
.assignedTo{border-radius: 4px !important;}
#productStoryForm table tbody tr td.c-actions .dividing-line {width: 1px; height: 16px; display: inline-block; vertical-align: middle; background: #F4F5F7; margin: 0 4px 0 0;}
</style>
<?php
$lang->story->createCommon = $storyType == 'story' ? $lang->story->createStory : $lang->story->createRequirement;
$unfoldStories     = isset($config->product->browse->unfoldStories) ? json_decode($config->product->browse->unfoldStories, true) : array();
$unfoldStories     = zget($unfoldStories, $productID, array());
$isProjectStory    = $this->app->rawModule == 'projectstory';
$projectHasProduct = $isProjectStory && !empty($project->hasProduct);
$projectIDParam    = $isProjectStory ? "projectID=$projectID&" : '';
js::set('unfoldStories', $unfoldStories);
js::set('unfoldAll',     $lang->execution->treeLevel['all']);
js::set('foldAll',       $lang->execution->treeLevel['root']);
js::set('storyType',     $storyType);
js::set('browseType', $browseType);
js::set('productID', $productID);
js::set('projectID', $projectID);
js::set('branch', $branch);
js::set('rawModule', $this->app->rawModule);
js::set('productType', $this->app->tab == 'product' ? $product->type : '');
js::set('projectHasProduct', $projectHasProduct);
js::set('URAndSR', $this->config->URAndSR);
js::set('vision',  $this->config->vision);
?>
<style>
.btn-group .icon-close:before {font-size: 5px; vertical-align: 25%;}
.btn-group a i.icon-plus, .btn-group a i.icon-link {font-size: 16px;}
.btn-group a.btn-secondary, .btn-group a.btn-primary {border-right: 1px solid rgba(255,255,255,0.2);}
.btn-group button.dropdown-toggle.btn-secondary, .btn-group button.dropdown-toggle.btn-primary {padding:6px;}
</style>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title" title="<?php echo $moduleName;?>">
      <?php
      echo $moduleName;
      if($moduleID and $moduleID !== 'all')
      {
          $removeLink = $browseType == 'bymodule' ? $this->createLink($this->app->rawModule, $this->app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&storyType=$storyType&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("storyModule")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php
    if(!commonModel::isTutorialMode())
    {
        foreach(customModel::getFeatureMenu($this->app->rawModule, $this->app->rawMethod) as $menuItem)
        {
            if(isset($menuItem->hidden)) continue;
            if($menuItem->name == 'emptysr' && $storyType == 'story') continue;
            $menuBrowseType = strpos($menuItem->name, 'QUERY') === 0 ? 'bySearch' : $menuItem->name;
            if($menuItem->name == 'more')
            {
                if(!empty($lang->product->moreSelects))
                {
                    $moreLabel       = $lang->more;
                    $moreLabelActive = '';
                    $storyBrowseType = $this->session->storyBrowseType;
                    if(isset($lang->product->moreSelects[$storyBrowseType]))
                    {
                        $moreLabel       = "<span class='text'>{$lang->product->moreSelects[$storyBrowseType]}</span> <span class='label label-light label-badge'>{$pager->recTotal}</span>";
                        $moreLabelActive = 'btn-active-text';
                    }
                    echo '<div class="btn-group" id="more">';
                    echo html::a('javascript:;', $moreLabel . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $moreLabelActive'");
                    echo "<ul class='dropdown-menu'>";
                    foreach($lang->product->moreSelects as $key => $value)
                    {
                        $active = $key == $storyBrowseType ? 'btn-active-text' : '';
                        echo '<li>' . html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=$key&param=0&storyType=$storyType"), "<span class='text'>{$value}</span>", '', "class='btn btn-link $active'") . '</li>';
                    }
                    echo '</ul></div>';
                }
            }
            elseif($menuItem->name == 'QUERY')
            {
                $searchBrowseLink = $this->createLink($this->app->rawModule, $this->app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=$menuBrowseType&param=%s&storyType=$storyType");
                $isBySearch       = $this->session->storyBrowseType == 'bysearch';
                include $this->app->getModuleRoot() . '/common/view/querymenu.html.php';
            }
            else
            {
                $menuItemName = strtolower($menuItem->name);
                echo html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=$menuBrowseType&param=0&storyType=$storyType"), "<span class='text'>$menuItem->text</span>" . ($menuItemName == $this->session->storyBrowseType ? ' <span class="label label-light label-badge">' . $pager->recTotal . '</span>' : ''), '', "id='{$menuItem->name}Tab' class='btn btn-link" . ($this->session->storyBrowseType == $menuItemName ? ' btn-active-text' : '') . "'");
            }
        }
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->product->searchStory;?></a>
  </div>
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-right">
    <?php if($this->app->rawModule != 'projectstory') common::printIcon('story', 'report', "productID=$productID&branchID=$branch&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=pie", '', 'button', 'bar-chart muted'); ?>
    <div class="btn-group">
      <button class="btn pull-right btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu" id='exportActionMenu'>
        <?php
        $tab   = $isProjectStory ? 'project' : 'product';
        $class = common::hasPriv('story', 'export') ? '' : "class=disabled";
        $misc  = common::hasPriv('story', 'export') ? "data-toggle='modal' data-type='iframe' class='export' data-app='$tab'" : "class=disabled";
        $link  = common::hasPriv('story', 'export') ?  $this->createLink('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType&type=$storyType") : '#';
        echo "<li $class>" . html::a($link, $lang->story->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php if(common::canModify('product', $product)):?>
    <div class='btn-group dropdown'>
      <?php
      $createStoryLink = $this->createLink('story', 'create', "product=$productID&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&type=$storyType");
      $batchCreateLink = $this->createLink('story', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=0&project=$projectID&plan=0&type=$storyType");

      $buttonLink  = '';
      $buttonTitle = '';
      $buttonType  = $from == 'project' ? 'btn-secondary' : 'btn-primary';
      if(common::hasPriv('story', 'batchCreate'))
      {
          $buttonLink  = empty($productID) ? '' : $batchCreateLink;
          $buttonTitle = $lang->story->batchCreate;
      }
      if(common::hasPriv('story', 'create'))
      {
          $buttonLink  = $createStoryLink;
          $buttonTitle = $lang->story->create;
      }

      $hidden = empty($buttonLink) ? 'hidden' : '';
      echo html::a($buttonLink, "<i class='icon icon-plus'></i> $buttonTitle", '', "class='btn $buttonType $hidden create-story-btn' data-app='$tab'");
      ?>
      <?php if(!empty($productID) and common::hasPriv('story', 'batchCreate') and common::hasPriv('story', 'create')): ?>
      <button type='button' class="btn <?php echo $buttonType?> dropdown-toggle" data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu pull-right'>
        <li>
        <?php
        if(commonModel::isTutorialMode())
        {
            $wizardParams = helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID");
            if($isProjectStory) $wizardParams = helper::safe64Encode("productID=$productID&branch=$branch&moduleID=$moduleID&storyID=&projectID=$projectID");
            $link = $this->createLink('tutorial', 'wizard', "module=story&method=create&params=$wizardParams");
            echo html::a($link, $lang->story->createCommon, '', "data-app='$tab'");
        }
        else
        {
            echo html::a($createStoryLink, $lang->story->create, '', "data-group='$tab'");
        }
        ?>
        </li>
        <li><?php echo html::a($batchCreateLink, $lang->story->batchCreate, '', "data-group='$tab'");?></li>
      </ul>
      <?php endif;?>
    </div>
    <?php endif;?>
  </div>
  <?php endif;?>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
<div id="xx-title">
  <strong>
  <?php echo $this->product->getByID($productID)->name ?>
  </strong>
</div>
<?php endif;?>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted">
        <?php echo $lang->product->noModule;?>
      </div>
      <hr class="space">
      <?php endif;?>
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php if($productID) common::printLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=$branchID&from=&projectID=$projectID", $lang->tree->manageMenu, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='story'></div>
    <?php if(empty($stories)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $storyType == 'story' ? $lang->story->noStory : $lang->story->noRequirement;?></span>
        <?php if(common::canModify('product', $product) and common::hasPriv('story', 'create')):?>
        <?php echo html::a($this->createLink('story', 'create', "productID={$productID}&branch={$branch}&moduleID={$moduleID}&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&type=$storyType"), "<i class='icon icon-plus'></i> " . $lang->story->create, '', "class='btn btn-info' data-app='$from'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class="main-table table-story skip-iframe-modal" method="post" id='productStoryForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
      $vars = "productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
      if($from == 'project') $vars = "projectID=$projectID&productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";

      if($useDatatable) include $this->app->getModuleRoot() . '/common/view/datatable.html.php';
      $setting = $this->datatable->getSetting('product');
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      $columns = 0;

      $canBeChanged         = common::canModify('product', $product);
      $canBatchEdit         = ($canBeChanged and common::hasPriv('story', 'batchEdit'));
      $canBatchClose        = (common::hasPriv('story', 'batchClose') and strtolower($browseType) != 'closedbyme' and strtolower($browseType) != 'closedstory');
      $canBatchReview       = ($canBeChanged and common::hasPriv('story', 'batchReview'));
      $canBatchChangeStage  = ($canBeChanged and common::hasPriv('story', 'batchChangeStage'));
      $canBatchChangeBranch = ($canBeChanged and common::hasPriv('story', 'batchChangeBranch'));
      $canBatchChangeModule = ($canBeChanged and common::hasPriv('story', 'batchChangeModule'));
      $canBatchChangePlan   = ($canBeChanged and common::hasPriv('story', 'batchChangePlan'));
      $canBatchAssignTo     = ($canBeChanged and common::hasPriv('story', 'batchAssignTo'));
      $canBatchUnlink       = ($canBeChanged and $this->app->tab == 'project' and common::hasPriv('projectstory', 'batchUnlinkStory'));
      $canBatchImportToLib  = ($canBeChanged and $this->app->tab == 'project' and isset($this->config->maxVersion) and common::hasPriv('story', 'batchImportToLib'));

      $canBatchAction       = ($canBatchEdit or $canBatchClose or $canBatchReview or $canBatchChangeStage or $canBatchChangeModule or $canBatchChangePlan or $canBatchAssignTo or $canBatchUnlink or $canBatchImportToLib);
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='storyList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
        <thead>
          <tr>
          <?php if($this->app->getViewType() == 'xhtml'):?>
          <?php
          foreach($setting as $key => $value)
          {
              if($value->id == 'title' || $value->id == 'id' || $value->id == 'pri' || $value->id == 'status')
              {
                  if($storyType == 'requirement' and (in_array($value->id, array('plan', 'stage')))) $value->show = false;

                  if($value->show)
                  {
                      $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
                      $columns ++;
                  }
              }
          }?>
          <?php else:?>
          <?php
          foreach($setting as $key => $value)
          {
              if($storyType == 'requirement' and (in_array($value->id, array('plan', 'stage')))) $value->show = false;

              if($value->show)
              {
                  $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
                  $columns ++;
              }
          }
          ?>
          <?php endif;?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($stories as $story):?>
          <tr data-id='<?php echo $story->id?>' data-estimate='<?php echo $story->estimate?>' data-cases='<?php echo zget($storyCases, $story->id, 0);?>'>
            <?php $story->from = $from;?>
            <?php if($this->app->getViewType() == 'xhtml'):?>
            <?php
            foreach($setting as $key => $value)
            {
                if($value->id == 'title' || $value->id == 'id' || $value->id == 'pri' || $value->id == 'status')
                {
                  $this->story->printCell($value, $story, $users, $branchOption, $storyStages, $modulePairs, $storyTasks, $storyBugs, $storyCases, $useDatatable ? 'datatable' : 'table');
                }
            }?>
            <?php else:?>
            <?php foreach($setting as $key => $value) $this->story->printCell($value, $story, $users, $branchOption, $storyStages, $modulePairs, $storyTasks, $storyBugs, $storyCases, $useDatatable ? 'datatable' : 'table');?>
            <?php endif;?>
          </tr>
          <?php if(!empty($story->children)):?>
          <?php $i = 0;?>
          <?php foreach($story->children as $key => $child):?>
          <?php $child->from = $from;?>
          <?php $class  = $i == 0 ? ' table-child-top' : '';?>
          <?php $class .= ($i + 1 == count($story->children)) ? ' table-child-bottom' : '';?>
          <tr class='table-children<?php echo $class;?> parent-<?php echo $story->id;?>' data-id='<?php echo $child->id?>' data-status='<?php echo $child->status?>' data-estimate='<?php echo $child->estimate?>'>
            <?php if($this->app->getViewType() == 'xhtml'):?>
            <?php
            foreach($setting as $key => $value)
            {
                if($value->id == 'title' || $value->id == 'id' || $value->id == 'pri' || $value->id == 'status')
                {
                  $this->story->printCell($value, $child, $users, $branchOption, $storyStages, $modulePairs, $storyTasks, $storyBugs, $storyCases, $useDatatable ? 'datatable' : 'table', $storyType);
                }
            }?>
            <?php else:?>
            <?php foreach($setting as $key => $value) $this->story->printCell($value, $child, $users, $branchOption, $storyStages, $modulePairs, $storyTasks, $storyBugs, $storyCases, $useDatatable ? 'datatable' : 'table', $storyType);?>
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
            $disabled   = $canBatchEdit ? '' : "disabled='disabled'";
            $actionLink = $this->createLink('story', 'batchEdit', "productID=$productID&projectID=$projectID&branch=$branch&storyType=$storyType");
            ?>
            <?php echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu'>
              <?php
              $class      = $canBatchClose ? '' : "class='disabled'";
              $actionLink = $this->createLink('story', 'batchClose', "productID=$productID&projectID=0&storyType=$storyType");
              $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink', '', '#productStoryForm')\"" : '';
              echo "<li $class>" . html::a('#', $lang->close, '', $misc) . "</li>";

              if($canBatchReview)
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
                              echo html::a('#', $reason, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#productStoryForm')\"");
                              echo "</li>";
                          }
                          echo '</ul></li>';
                      }
                      else
                      {
                        echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#productStoryForm')\"") . '</li>';
                      }
                  }
                  echo '</ul></li>';
              }
              else
              {
                  $class= "class='disabled'";
                  echo "<li $class>" . html::a('javascript:;', $lang->story->review,  '', $class) . '</li>';
              }

              ?>
            </ul>
          </div>

          <?php $isShowModuleBTN = ($this->app->tab == 'project' and $browseType != 'bybranch') ? false : true;?>
          <?php if($canBatchChangeModule and $productID and $branchID !== 'all' and $isShowModuleBTN):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->moduleAB;?> <span class="caret"></span></button>
            <?php $withSearch = count($modules) > 8;?>
            <div class="dropdown-menu search-list<?php if($withSearch) echo ' search-box-sink';?>" data-ride="searchList">
              <?php if($withSearch):?>
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="moduleSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="moduleSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
              <?php $modulesPinYin = common::convert2Pinyin($modules);
              ?>
              <?php endif;?>
              <div class="list-group">
                <?php
                foreach($modules as $moduleId => $module)
                {
                    $searchKey = $withSearch ? ('data-key="' . zget($modulesPinYin, $module, '') . '"') : '';
                    $actionLink = $this->createLink('story', 'batchChangeModule', "moduleID=$moduleId");
                    echo html::a('#', empty($module) ? '/' : $module, '', "$searchKey onclick=\"setFormAction('$actionLink', 'hiddenwin', '#productStoryForm')\"");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>

          <?php if($canBatchAssignTo):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn assignedTo"><?php echo $lang->story->assignedTo;?> <span class="caret"></span></button>
            <?php
            $withSearch = count($users) > 10;
            $actionLink = $this->createLink('story', 'batchAssignTo', "productID=$productID");
            echo html::select('assignedTo', $teamMembers, '', 'class="hidden"');
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
              <?php foreach ($teamMembers as $key => $value):?>
              <?php
              if(empty($key) or $key == 'closed') continue;
              $searchKey = $withSearch ? ('data-key="' . zget($usersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
              echo html::a("javascript:$(\"#assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#productStoryForm\")", $value, '', $searchKey);
              ?>
              <?php endforeach;?>
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
<div class="modal fade" id="linkStoryByPlan">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->execution->linkStoryByPlan;?></h4><?php echo '(' . $lang->project->linkStoryByPlanTips . ')';?>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('plan', zget($productPlans, $productID, ''), '', "class='form-control chosen' id='plan'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->execution->linkStory, "id='toTaskButton'", 'btn btn-primary');?></span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="batchImportToLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->story->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax' action='<?php echo $this->createLink('story', 'batchImportToLib');?>'>
          <table class='table table-form'>
            <tr>
              <th><?php echo $lang->story->lib;?></th>
              <td>
                <?php echo html::select('lib', $libs, '', "class='form-control chosen' required");?>
              </td>
            </tr>
            <?php if(!common::hasPriv('assetlib', 'approveStory') and !common::hasPriv('assetlib', 'batchApproveStory')):?>
            <tr>
              <th><?php echo $lang->story->approver;?></th>
              <td>
                <?php echo html::select('assignedTo', $approvers, '', "class='form-control chosen'");?>
              </td>
            </tr>
            <?php endif;?>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::hidden('storyIdList', '');?>
                <?php echo html::submitButton($lang->import, '', 'btn btn-primary');?>
              </td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="batchUnlinkStoryTip">
  <div class="modal-dialog mw-700px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><strong><?php echo $lang->projectstory->batchUnlinkTip;?></strong></h4>
      </div>
      <div class="modal-body">
        <table class='table'>
          <thead>
            <tr>
              <th><?php echo $lang->story->title;?></th>
              <th class='w-200px'><?php echo $lang->story->link . $lang->execution->common;?></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
          <tfoot>
            <tr>
              <td colspan='2' class='text-center'>
                <?php echo html::commonButton($lang->projectstory->confirm, 'data-dismiss="modal" id="confirmBtn"', 'btn btn-primary');?>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>
<script>
var moduleID = <?php echo $moduleID?>;
var branchID = $.cookie('storyBranch');
$('#module<?php echo $moduleID;?>').closest('li').addClass('active');
$('#branch' + branchID).closest('li').addClass('active');

$(function()
{
    // Update table summary text.
    <?php $storyCommon = $storyType == 'requirement' ? $lang->URCommon : $lang->SRCommon;?>
    var checkedSummary = '<?php echo str_replace('%storyCommon%', $storyCommon, $lang->product->checkedSummary)?>';
    $('#productStoryForm').table(
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
        }
    });
});

/**
 * Set the color of the badge to white.
 *
 * @param  object  obj
 * @param  bool    isShow
 * @access public
 * @return void
 */
function setBadgeStyle(obj, isShow)
{
    var $label = $(obj);
    if(isShow == true)
    {
        $label.find('.label-badge').css({"color":"#fff", "border-color":"#fff"});
    }
    else
    {
        $label.find('.label-badge').css({"color":"#838a9d", "border-color":"#838a9d"});
    }
}
</script>
<?php include $this->app->getModuleRoot() . '/common/view/footer.html.php';?>
