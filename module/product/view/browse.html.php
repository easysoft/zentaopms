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
<?php
include '../../common/view/header.html.php';
include '../../common/view/datatable.fix.html.php';
include '../../common/view/zui3dtable.html.php';

$options = array();
$options['users']        = $users;
$options['branchOption'] = $branchOption;
$options['modulePairs']  = $modulePairs;
$options['storyStages']  = $storyStages;
$options['isShowBranch'] = '';
$options['products']     = $products;
if(!empty($branchOptions)) $options['branchOptions'] = $branchOptions;

$hasChildren = false;
array_map(function($story) use(&$hasChildren)
{
    if(!empty($story->children))
    {
        $hasChildren = true;
        if($story->parent == '0') $story->parent = -1;
        foreach($story->children as $subStory)
        {
            if($subStory->parent == 0) $subStory->parent = $story->id;
        }
    }
}, $stories);

$cols = $this->story->generateCol($orderBy, $storyType, $hasChildren);
$rows = $this->story->generateRow($stories, $cols, $options, $project, $storyType);
$vars = "productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
if($from == 'project' and !empty($projectID)) $vars = "projectID=$projectID&productID=$productID&branch=$branch&browseType=$browseType&param=$param&storyType=$storyType&orderBy={orderBy}&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
$sortLink = helper::createLink($this->app->rawModule, $this->app->rawMethod, $vars);
$projectHasProduct = $isProjectStory && !empty($project->hasProduct);

$canBeChanged          = common::canModify('product', $product);
$canBatchEdit          = ($canBeChanged and common::hasPriv($storyType, 'batchEdit'));
$canBatchClose         = (common::hasPriv($storyType, 'batchClose') and strtolower($browseType) != 'closedbyme' and strtolower($browseType) != 'closedstory');
$canBatchReview        = ($canBeChanged and common::hasPriv($storyType, 'batchReview'));
$canBatchChangeStage   = ($canBeChanged and common::hasPriv('story', 'batchChangeStage') and $storyType == 'story');
$canBatchChangeBranch  = ($canBeChanged and common::hasPriv($storyType, 'batchChangeBranch') and $this->session->currentProductType and $this->session->currentProductType != 'normal' and $productID);
$canBatchChangeModule  = ($canBeChanged and common::hasPriv($storyType, 'batchChangeModule'));
$canBatchChangePlan    = ($canBeChanged and common::hasPriv('story', 'batchChangePlan') and $storyType == 'story' and (!$isProjectStory or $projectHasProduct or ($isProjectStory and isset($project->model) and $project->model == 'scrum')));
$canBatchAssignTo      = ($canBeChanged and common::hasPriv($storyType, 'batchAssignTo'));
$canBatchUnlink        = ($canBeChanged and $projectHasProduct and common::hasPriv('projectstory', 'batchUnlinkStory'));
$canBatchImportToLib   = ($canBeChanged and $isProjectStory and isset($this->config->maxVersion) and common::hasPriv('story', 'batchImportToLib') and helper::hasFeature('storylib'));
$canBatchChangeRoadmap = common::hasPriv('story', 'batchChangeRoadmap');

$canBatchAction = ($canBatchEdit or $canBatchClose or $canBatchReview or $canBatchChangeStage or $canBatchChangeModule or $canBatchChangePlan or $canBatchAssignTo or $canBatchUnlink or $canBatchImportToLib or $canBatchChangeBranch or $canBatchChangeRoadmap);
if(!$canBatchAction) unset($cols[0]->checkbox);

/* Set unfold parent taskID. */
js::set('orderBy', $orderBy);
js::set('sortLink', $sortLink);
js::set('cols', json_encode($cols));
js::set('data', json_encode($rows));

$lang->story->createCommon = $storyType == 'story' ? $lang->story->createStory : $lang->story->createRequirement;
$projectIDParam    = $isProjectStory ? "projectID=$projectID&" : '';
$projectModel      = isset($project->model) ? $project->model : '';
js::set('browseType',        $browseType);
js::set('account',           $this->app->user->account);
js::set('reviewStory',       $lang->product->reviewStory);
js::set('productID',         $productID);
js::set('projectID',         $projectID);
js::set('branch',            $branch);
js::set('rawModule',         $this->app->rawModule);
js::set('productType',       $this->app->tab == 'product' ? $product->type : '');
js::set('projectHasProduct', $projectHasProduct);
js::set('projectModel',      $projectModel);
js::set('URAndSR',           $this->config->URAndSR);
js::set('storyType',         $storyType);
js::set('vision',            $this->config->vision);
js::set('pageSummary',       $summary);
?>
<?php if(isset($project->hasProduct) && empty($project->hasProduct) && $project->model != 'scrum'):?>
<style>
#productStoryForm th.c-plan {display: none !important;}
#productStoryForm td.c-plan {display: none !important;}
#customDatatable div.col[data-key=plan] {display: none !important;}
</style>
<?php endif;?>
<div id="mainMenu" class="clearfix">
  <?php if(!$isProjectStory):?>
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
  <?php endif;?>
  <div class="btn-toolbar pull-left">
    <?php if($isProjectStory):?>
    <?php if(!empty($project->hasProduct)):?>
    <div class='btn-group'>
      <a href='javascript:;' class='btn btn-link btn-limit text-ellipsis' data-toggle='dropdown' style="max-width: 120px;"><div class='text' style="overflow: hidden;" title='<?php echo $productName;?>'><?php echo $productName;?></div> <span class='caret'></span></a>
      <ul class='dropdown-menu' style='max-height:240px; max-width: 300px; overflow-y:auto'>
        <?php
        echo '<li ' . (empty($productID) ? "class='active'" : '') . '>' . html::a($this->createLink('projectstory', 'story', "projectID=$projectID&productID=0&branch=all&browseType=&param=0&storyType=$storyType"), $lang->product->all)  . "</li>";
        foreach($projectProducts as $projectProduct)
        {
            $active = $projectProduct->id == $productID ? "class='active'" : '';
            echo "<li $active>" . html::a($this->createLink('projectstory', 'story', "projectID=$projectID&productID=$projectProduct->id&branch=all&browseType=&param=0&storyType=$storyType"), $projectProduct->name, '', "title='{$projectProduct->name}' class='text-ellipsis'") . "</li>";
        }
        ?>
      </ul>
    </div>
    <?php endif;?>
    <div class="btn-group">
      <a href="javascript:;" class="btn btn-link" style="padding-right: 0;"> <?php echo $moduleName;?> </a>
      <?php
      if($moduleID)
      {
          $removeLink = $browseType == 'bymodule' ? $this->createLink($this->app->rawModule, $this->app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=$browseType&param=0&storyType=$storyType&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("storyModuleParam")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted btn btn-link' style='padding-left: 0;'");
      }
      ?>
    </div>
    <?php endif;?>
    <?php
    if(!commonModel::isTutorialMode())
    {
        if($isProjectStory and $storyType == 'requirement')
        {
            unset($lang->projectstory->featureBar['story']['linkedExecution']);
            unset($lang->projectstory->featureBar['story']['unlinkedExecution']);
        }

        foreach(customModel::getFeatureMenu($this->app->rawModule, $this->app->rawMethod) as $menuItem)
        {
            if(isset($menuItem->hidden)) continue;
            if($menuItem->name == 'emptysr' && $storyType == 'story') continue;
            $menuBrowseType = strpos($menuItem->name, 'QUERY') === 0 ? 'bySearch' : $menuItem->name;
            $moreSelects = empty($lang->product->moreSelects[$app->rawMethod][$menuItem->name]) ? '' : $lang->product->moreSelects[$app->rawMethod][$menuItem->name];
            if($moreSelects)
            {
                $moreLabel       = $lang->more;
                $moreLabelActive = '';
                $storyBrowseType = $this->session->storyBrowseType;
                if(isset($moreSelects[$storyBrowseType]))
                {
                    $moreLabel       = "<span class='text'>{$moreSelects[$storyBrowseType]}</span> <span class='label label-light label-badge'>{$pager->recTotal}</span>";
                    $moreLabelActive = 'btn-active-text';
                }
                echo '<div class="btn-group" id="more">';
                echo html::a('javascript:;', $moreLabel . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link $moreLabelActive'");
                echo "<ul class='dropdown-menu'>";
                foreach($moreSelects as $key => $value)
                {
                    $active = $key == $storyBrowseType ? 'btn-active-text' : '';
                    echo '<li>' . html::a($this->createLink($this->app->rawModule, $this->app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=$key&param=0&storyType=$storyType"), "<span class='text'>{$value}</span>", '', "class='btn btn-link $active'") . '</li>';
                }
                echo '</ul></div>';
            }
            elseif($menuItem->name == 'QUERY')
            {
                $searchBrowseLink = $this->createLink($this->app->rawModule, $this->app->rawMethod, $projectIDParam . "productID=$productID&branch=$branch&browseType=$menuBrowseType&param=%s&storyType=$storyType");
                $isBySearch       = $this->session->storyBrowseType == 'bysearch';
                include '../../common/view/querymenu.html.php';
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
    <?php if($productID) common::printIcon('story', 'report', "productID=$productID&branchID=$branch&storyType=$storyType&browseType=$browseType&moduleID=$moduleID&chartType=pie&projectID=$projectID", '', 'button', 'bar-chart muted'); ?>
    <div class="btn-group">
      <button class="btn pull-right btn-link" data-toggle="dropdown"><i class="icon icon-export muted"></i> <span class="text"><?php echo $lang->export ?></span> <span class="caret"></span></button>
      <ul class="dropdown-menu" id='exportActionMenu'>
        <?php
        $tab   = $isProjectStory ? 'project' : 'product';
        $class = common::hasPriv($storyType, 'export') ? '' : "class=disabled";
        $misc  = common::hasPriv($storyType, 'export') ? "data-toggle='modal' data-type='iframe' class='export' data-app='$tab'" : "class=disabled";
        $link  = common::hasPriv($storyType, 'export') ?  $this->createLink('story', 'export', "productID=$productID&orderBy=$orderBy&executionID=$projectID&browseType=$browseType&storyType=$storyType") : '#';
        echo "<li $class>" . html::a($link, $lang->story->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php if(common::canModify('product', $product)):?>
    <div class='btn-group dropdown'>
      <?php
      $createStoryLink = $this->createLink('story', 'create', "product=$productID&branch=$branch&moduleID=$moduleID&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType");
      $batchCreateLink = $this->createLink('story', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$moduleID&storyID=0&project=$projectID&plan=0&storyType=$storyType");

      $buttonLink  = '';
      $buttonTitle = '';
      $buttonType  = $from == 'project' ? 'btn-secondary' : 'btn-primary';
      if(common::hasPriv($storyType, 'batchCreate'))
      {
          $buttonLink  = empty($productID) ? '' : $batchCreateLink;
          $buttonTitle = $lang->story->batchCreate;
      }
      if(common::hasPriv($storyType, 'create'))
      {
          $buttonLink  = $createStoryLink;
          $buttonTitle = $lang->story->create;
      }

      $hidden = empty($buttonLink) ? 'hidden' : '';
      echo html::a($buttonLink, "<i class='icon icon-plus'></i> $buttonTitle", '', "class='btn $buttonType $hidden create-story-btn' data-app='$tab'");
      ?>
      <?php if(!empty($productID) and common::hasPriv($storyType, 'batchCreate') and common::hasPriv($storyType, 'create')): ?>
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
    <?php if($projectHasProduct):?>
    <div class='btn-group dropdown'>
    <?php
    if(commonModel::isTutorialMode())
    {
        $wizardParams = helper::safe64Encode("project=$project->id");
        echo html::a($this->createLink('tutorial', 'wizard', "module=project&method=linkStory&params=$wizardParams"), "<i class='icon-link'></i> {$lang->project->linkStory}",'', "class='btn btn-link link-story-btn'");
    }
    else
    {
        $buttonLink  = '';
        $buttonTitle = '';
        $dataToggle  = '';
        if(common::hasPriv('projectstory', 'importPlanStories'))
        {
            $buttonLink  = empty($productID) ? '' : '#linkStoryByPlan';
            $buttonTitle = $lang->execution->linkStoryByPlan;
            $dataToggle  = 'data-toggle="modal"';
        }
        if(common::hasPriv('projectstory', 'importRoadmapStories') and $storyType == 'requirement' and $this->config->edition == 'ipd')
        {
            $buttonLink  = empty($productID) ? '' : '#linkStoryByRoadmap';
            $buttonTitle = $lang->product->linkStoryByRoadmap;;
            $dataToggle  = 'data-toggle="modal"';
        }
        if(common::hasPriv('projectstory', 'linkStory'))
        {
            if($storyType == 'requirement') $lang->execution->linkStory = str_replace($lang->SRCommon, $lang->URCommon, $lang->execution->linkStory);

            $buttonLink  = $this->createLink('projectstory', 'linkStory', "project=$projectID&browseType=&param=0&recTotal=0&recPerPage=50&pageID=1&storyType=$storyType");
            $buttonTitle = $lang->execution->linkStory;
            $dataToggle  = '';
        }

        $hidden = empty($buttonLink) ? 'hidden' : '';
        echo html::a($buttonLink, "<i class='icon-link'></i> $buttonTitle", '', "class='btn btn-primary $hidden' $dataToggle");

        if(!empty($productID) and common::hasPriv('projectstory', 'linkStory') and common::hasPriv('projectstory', 'importRoadmapStories') and $storyType == 'requirement' and $this->config->edition == 'ipd')
        {
            echo "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
            echo "<ul class='dropdown-menu pull-right'>";
            echo '<li>' . html::a($this->createLink('projectstory', 'linkStory', "project=$projectID"), $lang->execution->linkStory). "</li>";
            echo '<li>' . html::a('#linkStoryByRoadmap', $lang->product->linkStoryByRoadmap . $lang->URCommon, '', 'data-toggle="modal"') . "</li>";
            echo '</ul>';
        }
        if(!empty($productID) and common::hasPriv('projectstory', 'linkStory') and common::hasPriv('projectstory', 'importPlanStories') and $projectModel != 'ipd' and $storyType == 'story')
        {
            echo "<button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>";
            echo "<ul class='dropdown-menu pull-right'>";
            echo '<li>' . html::a($this->createLink('projectstory', 'linkStory', "project=$projectID"), $lang->execution->linkStory). "</li>";
            echo '<li>' . html::a('#linkStoryByPlan', $lang->execution->linkStoryByPlan, '', 'data-toggle="modal"') . "</li>";
            echo '</ul>';
        }
    }
    ?>
    </div>
    <?php endif;?>
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
        <?php if($productID) common::printLink('tree', 'browse', "rootID=$productID&view=story&currentModuleID=0&branch=$branchID", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
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
        <?php if(common::canModify('product', $product) and common::hasPriv('story', 'create') and $browseType == 'allstory'):?>
        <?php echo html::a($this->createLink('story', 'create', "productID={$productID}&branch={$branch}&moduleID={$moduleID}&storyID=0&projectID=$projectID&bugID=0&planID=0&todoID=0&extra=&storyType=$storyType"), "<i class='icon icon-plus'></i> " . $lang->story->create, '', "class='btn btn-info' data-app='$from'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class="main-table table-story skip-iframe-modal" method="post" id='productStoryForm'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right setting"></nav>
      </div>
      <div id="storyList" class="table"></div>
      <div class="table-footer">
        <?php if($canBatchAction):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class="table-actions btn-toolbar">
          <div class='btn-group dropup'>
            <?php
            foreach($stories as $story) $storyProductIds[$story->product] = $story->product;
            $storyProductID  = count($storyProductIds) > 1 ? 0 : $productID;
            $disabled        = $canBatchEdit ? '' : "disabled='disabled'";
            $actionLink      = $this->createLink('story', 'batchEdit', "productID=$storyProductID&projectID=$projectID&branch=$branch&storyType=$storyType");
            ?>
            <?php if($canBatchEdit or $canBatchClose or $canBatchUnlink or $canBatchReview or $canBatchChangeStage or $canBatchChangeBranch) echo html::commonButton($lang->edit, "data-form-action='$actionLink' $disabled");?>
            <?php if($canBatchEdit or $canBatchClose or $canBatchUnlink or $canBatchReview or $canBatchChangeStage or $canBatchChangeBranch):?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <?php endif;?>
            <ul class='dropdown-menu'>
              <?php
              $class      = $canBatchClose ? '' : "class='disabled'";
              $actionLink = $this->createLink('story', 'batchClose', "productID=$productID&projectID=0&storyType=$storyType");
              $misc = $canBatchClose ? "onclick=\"setFormAction('$actionLink', '', '#productStoryForm')\"" : '';
              echo "<li $class>" . html::a('#', $lang->close, '', $misc) . "</li>";

              if($canBatchUnlink) echo '<li>' . html::a('#', $lang->story->unlink, '', "id='batchUnlinkStory'") . "</li>";

              if($canBatchReview)
              {
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->story->review, '', "id='reviewItem'");
                  echo "<ul class='dropdown-menu'>";
                  unset($lang->story->reviewResultList['']);
                  unset($lang->story->reviewResultList['revert']);
                  foreach($lang->story->reviewResultList as $key => $result)
                  {
                      $actionLink = $this->createLink('story', 'batchReview', "result=$key&from=product&storyType=$storyType");
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
                              $actionLink = $this->createLink('story', 'batchReview', "result=reject&reason=$key&storyType=$storyType");
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

              if($canBatchChangeBranch)
              {
                  $withSearch = count($branchTagOption) > 8;
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->product->branchName[$this->session->currentProductType], '', "id='branchItem'");
                  echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                  echo "<ul class='dropdown-list'>";
                  foreach($branchTagOption as $id => $branchName)
                  {
                      $actionLink = $this->createLink('story', 'batchChangeBranch', "branchID=$id&confirm=&storyIdList=&storyType=$storyType");
                      echo "<li class='option' data-key='$id'>" . html::a('#', $branchName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#productStoryForm')\"") . "</li>";
                  }
                  echo '</ul>';
                  if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                  echo '</div></li>';
              }
              elseif($this->session->currentProductType and $this->session->currentProductType != 'normal' and $productID)
              {
                  $class= "class='disabled'";
                  echo "<li $class>" . html::a('javascript:;', $lang->product->branchName[$this->session->currentProductType],  '', $class) . '</li>';
              }

              if($storyType == 'story')
              {
                  if($canBatchChangeStage)
                  {
                      echo "<li class='dropdown-submenu'>";
                      echo html::a('javascript:;', $lang->story->stageAB, '', "id='stageItem'");
                      echo "<ul class='dropdown-menu'>";
                      foreach($lang->story->stageList as $key => $stage)
                      {
                          if(empty($key)) continue;
                          if(strpos('tested|verified|released|closed', $key) === false) continue;
                          $actionLink = $this->createLink('story', 'batchChangeStage', "stage=$key");
                          echo "<li>" . html::a('#', $stage, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#productStoryForm')\"") . "</li>";
                      }
                      echo '</ul></li>';
                  }
                  else
                  {
                      $class= "class='disabled'";
                      echo "<li $class>" . html::a('javascript:;', $lang->story->stageAB, '', $class) . '</li>';
                  }
              }
              ?>
            </ul>
          </div>

          <?php if($productID and (($product->type != 'normal' and $branchID != 'all') or $product->type == 'normal')):?>
          <?php $isShowModuleBTN = ($isProjectStory and $browseType != 'bybranch') ? false : true;?>
          <?php if($canBatchChangeModule and $isShowModuleBTN):?>
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
                    $actionLink = $this->createLink('story', 'batchChangeModule', "moduleID=$moduleId&storyType=$storyType");
                    echo html::a('#', empty($module) ? '/' : $module, '', "$searchKey title='{$module}' onclick=\"setFormAction('$actionLink', 'hiddenwin', '#productStoryForm')\"");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php if($canBatchChangePlan and $storyType == 'story'):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->planAB;?> <span class="caret"></span></button>
            <?php
            unset($plans['']);
            $plans      = array(0 => $lang->null) + $plans;
            $withSearch = count($plans) > 8;
            ?>
            <div class="dropdown-menu search-list<?php if($withSearch) echo ' search-box-sink';?>" data-ride="searchList">
              <?php if($withSearch):?>
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="planSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="planSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
              <?php $plansPinYin = common::convert2Pinyin($plans);?>
              <?php endif;?>
              <div class="list-group">
                <?php
                foreach($plans as $planID => $plan)
                {
                    $position   = stripos($plan, '/');
                    $searchKey  = $withSearch ? ('data-key="' . zget($plansPinYin, $plan, '') . '"') : '';
                    $actionLink = $this->createLink('story', 'batchChangePlan', "planID=$planID");
                    echo html::a('#', $plan, '', "$searchKey title='{$plan}' onclick=\"setFormAction('$actionLink', 'hiddenwin', '#productStoryForm')\" onmouseover=\"setBadgeStyle(this, true);\" onmouseout=\"setBadgeStyle(this, false)\"");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>

          <?php if($canBatchChangeRoadmap and $config->edition == 'ipd' and $config->vision == 'or'):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->roadmap->common;?> <span class="caret"></span></button>
            <?php
            unset($roadmaps['']);
            $roadmaps   = array(0 => $lang->null) + $roadmaps;
            $withSearch = count($roadmaps) > 8;
            ?>
            <div class="dropdown-menu search-list<?php if($withSearch) echo ' search-box-sink';?>" data-ride="searchList">
              <?php if($withSearch):?>
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="planSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="planSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
              <?php $roadmapsPinYin = common::convert2Pinyin($roadmaps);?>
              <?php endif;?>
              <div class="list-group">
                <?php
                foreach($roadmaps as $roadmapID => $roadmap)
                {
                    $position   = stripos($roadmap, '/');
                    $searchKey  = $withSearch ? ('data-key="' . zget($roadmapsPinYin, $roadmap, '') . '"') : '';
                    $actionLink = $this->createLink('story', 'batchChangeRoadmap', "roadmapID=$roadmapID");
                    echo html::a('#', $roadmap, '', "$searchKey title='{$roadmap}' onclick=\"setFormAction('$actionLink', 'hiddenwin', '#productStoryForm')\"");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>

          <?php endif;?>

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
              echo html::a("javascript:$(\"#assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#productStoryForm\")", $value, '', $searchKey);
              ?>
              <?php endforeach;?>
              </div>
            </div>
          </div>
          <?php endif;?>

          <?php if($canBatchImportToLib):?>
          <?php echo html::a('#batchImportToLib', $lang->story->importToLib, '', 'class="btn" data-toggle="modal" id="importToLib"');?>
          <?php endif;?>
        </div>
        <div class="table-statistic"><?php echo $summary;?></div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<?php if($this->config->edition == 'ipd'):?>
<div class="modal fade" id="linkStoryByRoadmap">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title">
          <?php echo $lang->product->linkStoryByRoadmap;?></h4>
      </div>
      <div class="modal-body">
        <div class='input-group'>
          <?php echo html::select('roadmap', $roadmaps, '', "class='form-control chosen' id='roadmap'");?>
          <span class='input-group-btn'><?php echo html::commonButton($lang->execution->linkStory, "id='linkRoadmapButton'", 'btn btn-primary');?></span>
        </div>
      </div>
    </div>
  </div>
</div>
<?php endif;?>
<div class="modal fade" id="linkStoryByPlan">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title">
          <?php
          $productType = !empty($product) ? $product->type : '';
          $branchLang  = $productType ? $lang->product->branchName[$productType] : '';
          $linkStoryByPlanTips = $productType == 'normal' ? $lang->project->linkNormalStoryByPlanTips : sprintf($lang->project->linkBranchStoryByPlanTips, $branchLang);
          echo $lang->execution->linkStoryByPlan;?></h4><?php echo '(' . $linkStoryByPlanTips . ')';
          ?>
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

<?php if(in_array($config->edition, array('max', 'ipd'))):?>
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
<?php endif;?>

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

<?php
$storyCommon = $storyType == 'requirement' ? $lang->URCommon : $lang->SRCommon;
js::set('checkedSummary', str_replace('%storyCommon%', $storyCommon, $lang->product->checkedSummary));
js::set('moduleID', $moduleID);
?>

<script>
var branchID = $.cookie('storyBranch');
$('#module<?php echo $moduleID;?>').closest('li').addClass('active');
$('#branch' + branchID).closest('li').addClass('active');

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
<?php include '../../common/view/footer.html.php';?>
