<?php
/**
 * The browse view file of bug module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     bug
 * @version     $Id: browse.html.php 5102 2013-07-12 00:59:54Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php
include '../../common/view/datatable.fix.html.php';
js::set('browseType',    $browseType);
js::set('moduleID',      $currentModuleID);
js::set('bugBrowseType', ($browseType == 'bymodule' and $this->session->bugBrowseType == 'bysearch') ? 'all' : $this->session->bugBrowseType);
js::set('flow',          $config->global->flow);
js::set('productID',     $product->id);
js::set('branch',        $branch);
$currentBrowseType = isset($lang->bug->mySelects[$browseType]) && in_array($browseType, array_keys($lang->bug->mySelects)) ? $browseType : '';
?>
<div id="mainMenu" class="clearfix">
  <div id="sidebarHeader">
    <div class="title">
      <?php
      echo $moduleName;
      if($currentModuleID)
      {
          $removeLink = $browseType == 'bymodule' ? inlink('browse', "productID={$product->id}&branch=$branch&browseType=$browseType&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("bugModule")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class="btn-toolbar pull-left">
    <?php
    common::sortFeatureMenu();
    $menus = customModel::getFeatureMenu($this->moduleName, $this->methodName);
    foreach($menus as $menuItem)
    {
        if(isset($menuItem->hidden)) continue;
        $menuBrowseType = strpos($menuItem->name, 'QUERY') === 0 ? 'bySearch' : $menuItem->name;
        $label  = "<span class='text'>{$menuItem->text}</span>";
        $label .= $menuBrowseType == $this->session->bugBrowseType ? " <span class='label label-light label-badge'>{$pager->recTotal}</span>" : '';
        $active = $menuBrowseType == $this->session->bugBrowseType ? 'btn-active-text' : '';

        if($menuItem->name == 'my')
        {
            echo "<li id='statusTab' class='dropdown " . (!empty($currentBrowseType) ? 'active' : '') . "'>";
            echo html::a('javascript:;', $menuItem->text . " <span class='caret'></span>", '', "data-toggle='dropdown' class='btn btn-link'");
            echo "<ul class='dropdown-menu'>";
            foreach($lang->bug->mySelects as $key => $value)
            {
                echo '<li' . ($key == $currentBrowseType ? " class='active'" : '') . '>';
                echo html::a($this->createLink('bug', 'browse', "productid={$product->id}&branch=$branch&browseType=$key"), $value);
            }
            echo '</ul></li>';
        }
        elseif($menuItem->name == 'QUERY')
        {
            $searchBrowseLink = inlink('browse', "productID={$product->id}&branch=$branch&browseType=bySearch&param=%s");
            $isBySearch       = $browseType == 'bysearch';
            include '../../common/view/querymenu.html.php';
        }
        elseif($menuItem->name == 'more')
        {
            $moreSelects = isset($lang->bug->moreSelects[$app->rawMethod]['more']) ? $lang->bug->moreSelects[$app->rawMethod]['more'] : array();
            if(!empty($moreSelects))
            {
                $moreLabel       = $lang->more;
                $moreLabelActive = '';
                if(isset($moreSelects[$this->session->bugBrowseType]))
                {
                    $moreLabel       = "<span class='text'>{$moreSelects[$this->session->bugBrowseType]}</span> <span class='label label-light label-badge'>{$pager->recTotal}</span>";
                    $moreLabelActive = 'btn-active-text';
                }
                echo "<div class='btn-group'><a href='javascript:;' data-toggle='dropdown' class='btn btn-link {$moreLabelActive}'>{$moreLabel} <span class='caret'></span></a>";
                echo "<ul class='dropdown-menu'>";
                foreach($moreSelects as $menuBrowseType => $label)
                {
                    $active = $menuBrowseType == $this->session->bugBrowseType ? 'btn-active-text' : '';
                    echo '<li>' . html::a($this->createLink('bug', 'browse', "productid={$product->id}&branch=$branch&browseType=$menuBrowseType"), "<span class='text'>{$label}</span>", '', "class='btn btn-link $active'") . '</li>';
                }
                echo '</ul></div>';
            }
        }
        else
        {
            echo html::a($this->createLink('bug', 'browse', "productid={$product->id}&branch=$branch&browseType=$menuBrowseType"), $label, '', "class='btn btn-link $active'");
        }
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->bug->byQuery;?></a>
  </div>
  <?php if(!isonlybody()):?>
  <div class="btn-toolbar pull-right">
    <?php common::printIcon('bug', 'report', "productID={$product->id}&browseType=$browseType&branchID=$branch&moduleID=$currentModuleID", '', 'button', 'bar-chart muted');?>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown'>
        <i class="icon icon-export muted"></i> <span class="text"> <?php echo $lang->export;?></span> <span class="caret"></span></button>
      </button>
      <ul class='dropdown-menu' id='exportActionMenu'>
        <?php
        $class = common::hasPriv('bug', 'export') ? "" : "class='disabled'";
        $misc  = common::hasPriv('bug', 'export') ? "class='export'" : "class='disabled'";
        $link  = common::hasPriv('bug', 'export') ? $this->createLink('bug', 'export', "productID={$product->id}&orderBy=$orderBy&browseType=$browseType") : '#';
        echo "<li $class>" . html::a($link, $lang->bug->export, '', $misc) . "</li>";
        ?>
      </ul>
    </div>
    <?php if(common::canModify('product', $product)):?>
    <?php
    $createBugLink   = '';
    $batchCreateLink = '';
    if(commonModel::isTutorialMode())
    {
        $wizardParams  = helper::safe64Encode("productID={$product->id}&branch=$branch&extra=moduleID=$currentModuleID");
        $createBugLink = $this->createLink('tutorial', 'wizard', "module=bug&method=create&params=$wizardParams");
    }
    else
    {
        $selectedBranch = $branch != 'all' ? $branch : 0;
        $createBugLink  = $this->createLink('bug', 'create', "productID={$product->id}&branch=$selectedBranch&extra=moduleID=$currentModuleID");
    }
    $batchCreateLink = $this->createLink('bug', 'batchCreate', "productID={$product->id}&branch=$branch&executionID=0&moduleID=$currentModuleID");

    $buttonLink  = '';
    $buttonTitle = '';
    if(common::hasPriv('bug', 'batchCreate'))
    {
        $buttonLink = $batchCreateLink;
        $buttonTitle = $lang->bug->batchCreate;
    }
    if(common::hasPriv('bug', 'create'))
    {
        $buttonLink = $createBugLink;
        $buttonTitle = $lang->bug->create;
    }
    $hidden = empty($buttonLink) ? 'hidden' : '';
    ?>
    <div class='btn-group dropdown'>
      <?php echo html::a($buttonLink, "<i class='icon-plus'></i> $buttonTitle", '', "class='btn btn-primary create-bug-btn $hidden'");?>
      <?php if(common::hasPriv('bug', 'batchCreate') and common::hasPriv('bug', 'create')):?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu'>
        <li><?php echo html::a($createBugLink, $lang->bug->create);?></li>
        <li><?php echo html::a($batchCreateLink, $lang->bug->batchCreate);?></li>
      </ul>
      <?php endif;?>
    </div>
    <?php endif;?>
  </div>
  <?php endif;?>
</div>
<?php if($this->app->getViewType() == 'xhtml'):?>
<div id="xx-title">
  <strong><?php echo $product->name;?></strong>
</div>
<?php endif;?>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted"><?php echo $lang->bug->notice->noModule;?></div>
      <hr class="space">
      <?php endif;?>
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browse', "productID={$product->id}&view=bug&currentModuleID=0&branch=0&from={$this->lang->navGroup->bug}", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
    <div class="cell<?php if($browseType == 'bysearch') echo ' show';?>" id="queryBox" data-module='bug'></div>
    <?php if(empty($bugs)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->bug->notice->noBug;?></span>
        <?php if(common::canModify('product', $product) and common::hasPriv('bug', 'create')):?>
        <?php echo html::a($this->createLink('bug', 'create', "productID={$product->id}&branch=$branch&extra=moduleID=$currentModuleID"), "<i class='icon icon-plus'></i> " . $lang->bug->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <?php
    $datatableId  = $this->moduleName . ucfirst($this->methodName);
    $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
    ?>
    <?php if($this->app->getViewType() == 'xhtml'):?>
    <form class='main-table table-bug' method='post' id='bugForm'>
    <?php else:?>
    <form class='main-table table-bug' method='post' id='bugForm' <?php if(!$useDatatable) echo "data-ride='table'";?>>
    <?php endif;?>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right setting"></nav>
      </div>
      <?php
      $vars = "productID={$product->id}&branch=$branch&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
      if($useDatatable) include '../../common/view/datatable.html.php';

      $setting = $this->loadModel('datatable')->getSetting('bug');
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      $columns = 0;

      $canBeChanged         = common::canModify('product', $product);
      $canBatchEdit         = ($canBeChanged and common::hasPriv('bug', 'batchEdit'));
      $canBatchConfirm      = ($canBeChanged and common::hasPriv('bug', 'batchConfirm'));
      $canBatchClose        = common::hasPriv('bug', 'batchClose');
      $canBatchActivate     = ($canBeChanged and common::hasPriv('bug', 'batchActivate'));
      $canBatchChangeBranch = ($canBeChanged and common::hasPriv('bug', 'batchChangeBranch'));
      $canBatchChangeModule = ($canBeChanged and common::hasPriv('bug', 'batchChangeModule'));
      $canBatchResolve      = ($canBeChanged and common::hasPriv('bug', 'batchResolve'));
      $canBatchAssignTo     = ($canBeChanged and common::hasPriv('bug', 'batchAssignTo'));

      $canBatchAction       = ($canBatchEdit or $canBatchConfirm or $canBatchClose or $canBatchActivate or $canBatchChangeBranch or $canBatchChangeModule or $canBatchResolve or $canBatchAssignTo);
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='bugList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>'>
        <thead>
          <tr>
          <?php if($this->app->getViewType() == 'xhtml'):?>
          <?php
          foreach($setting as $value)
          {
              if($value->id == 'title' || $value->id == 'id' || $value->id == 'pri' || $value->id == 'status')
              {
                  if($storyType == 'requirement' and (in_array($value->id, array('plan', 'stage')))) $value->show = false;

                  $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
                  $columns ++;
              }
          }
          ?>
          <?php else:?>
          <?php
          foreach($setting as $value)
          {
              if($value->show)
              {
                  if(common::checkNotCN() and $value->id == 'severity')  $value->name = $lang->bug->severity;
                  if(common::checkNotCN() and $value->id == 'pri')       $value->name = $lang->bug->pri;
                  if(common::checkNotCN() and $value->id == 'confirmed') $value->name = $lang->bug->confirmed;
                  $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
                  $columns ++;
              }
          }
          ?>
          <?php endif;?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($bugs as $bug):?>
          <tr data-id='<?php echo $bug->id?>'>
            <?php if($this->app->getViewType() == 'xhtml'):?>
            <?php
              foreach($setting as $value)
              {
                  if($value->id == 'title' || $value->id == 'id' || $value->id == 'pri' || $value->id == 'status')
                  {
                    $this->bug->printCell($value, $bug, $users, $builds, $branchOption, $modulePairs, $executions, $plans, $stories, $tasks, $useDatatable ? 'datatable' : 'table');
                  }
              }?>
            <?php else:?>
            <?php foreach($setting as $value) $this->bug->printCell($value, $bug, $users, $builds, $branchOption, $modulePairs, $executions, $plans, $stories, $tasks, $useDatatable ? 'datatable' : 'table', $projectPairs);?>
            <?php endif;?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!$useDatatable) echo '</div>';?>
      <div class='table-footer'>
        <?php if($canBatchAction):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
        <div class="table-actions btn-toolbar">
          <div class='btn-group dropup'>
            <?php
            $actionLink = $this->createLink('bug', 'batchEdit', "productID={$product->id}&branch=$branch");
            $misc       = $canBatchEdit ? "onclick=\"setFormAction('$actionLink', '', '#bugList')\"" : "disabled='disabled'";
            echo html::commonButton($lang->edit, $misc);
            ?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu'>
              <?php
              $class      = $canBatchConfirm ? '' : "class='disabled'";
              $actionLink = $this->createLink('bug', 'batchConfirm');
              $misc       = $canBatchConfirm ? "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#bugList')\"" : '';
              echo "<li $class>" . html::a('javascript:;', $lang->bug->confirm, '', $misc) . "</li>";

              $class      = $canBatchClose ? '' : "class='disabled'";
              $actionLink = $this->createLink('bug', 'batchClose');
              $misc       = $canBatchClose ? "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#bugList')\"" : '';
              echo "<li $class>" . html::a('javascript:;', $lang->bug->close, '', $misc) . "</li>";

              $class      = $canBatchActivate ? '' : "class='disabled'";
              $actionLink = $this->createLink('bug', 'batchActivate', "productID={$product->id}&branch=$branch");
              $misc       = $canBatchActivate ? "onclick=\"setFormAction('$actionLink', '', '#bugList')\"" : '';
              echo "<li $class>" . html::a('javascript:;', $lang->bug->activate, '', $misc) . "</li>";

              $misc = $canBatchResolve ? "id='resolveItem'" : '';
              if($misc)
              {
                  echo "<li class='dropdown-submenu'>" . html::a('javascript:;', $lang->bug->resolve,  '', $misc);
                  echo "<ul class='dropdown-menu'>";
                  unset($lang->bug->resolutionList['']);
                  unset($lang->bug->resolutionList['duplicate']);
                  unset($lang->bug->resolutionList['tostory']);
                  foreach($lang->bug->resolutionList as $key => $resolution)
                  {
                      $actionLink = $this->createLink('bug', 'batchResolve', "resolution=$key");
                      if($key == 'fixed')
                      {
                          $withSearch = count($builds) > 4;
                          echo "<li class='dropdown-submenu'>";
                          echo html::a('javascript:;', $resolution, '', "id='fixedItem'");
                          echo "<div class='dropdown-menu" . ($withSearch ? ' with-search':'') . "'>";
                          echo '<ul class="dropdown-list">';
                          unset($builds['']);
                          foreach($builds as $key => $build)
                          {
                              $actionLink = $this->createLink('bug', 'batchResolve', "resolution=fixed&resolvedBuild=$key");
                              echo "<li class='option' data-key='$key'>";
                              echo html::a('javascript:;', $build . (in_array($key, $releasedBuilds) ? " <span class='label label-primary label-outline'>{$lang->build->released}</span> " : ''), '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#bugList')\"");
                              echo "</li>";
                          }
                          echo "</ul>";
                          if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                          echo '</div></li>';
                      }
                      else
                      {
                          echo '<li>' . html::a('javascript:;', $resolution, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#bugList')\"") . '</li>';
                      }
                  }
                  echo '</ul></li>';
              }
              ?>
            </ul>
          </div>
          <?php if($canBatchChangeBranch and $this->session->currentProductType != 'normal'):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->product->branchName[$this->session->currentProductType];?> <span class="caret"></span></button>
            <?php $withSearch = count($branchTagOption) > 6;?>
            <?php if($withSearch):?>
            <div class="dropdown-menu search-list search-box-sink" data-ride="searchList">
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
            <?php $branchsPinYin = common::convert2Pinyin($branchTagOption);?>
            <?php else:?>
            <div class="dropdown-menu search-list">
            <?php endif;?>
              <div class="list-group">
                <?php
                foreach($branchTagOption as $branchID => $branchName)
                {
                    $searchKey = $withSearch ? ('data-key="' . zget($branchsPinYin, $branchName, '') . '"') : '';
                    $actionLink = $this->createLink('bug', 'batchChangeBranch', "branchID=$branchID");
                    echo html::a('#', $branchName, '', "$searchKey onclick=\"setFormAction('$actionLink', 'hiddenwin', '#bugList')\" data-key='$branchID'");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php if($canBatchChangeModule and ($product->type == 'normal' or $branch !== 'all')):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->bug->abbr->module;?> <span class="caret"></span></button>
            <?php $withSearch = count($modules) > 6;?>
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
                foreach($modules as $moduleID => $module)
                {
                    $searchKey = $withSearch ? ('data-key="' . zget($modulesPinYin, $module, '') . '"') : '';
                    $actionLink = $this->createLink('bug', 'batchChangeModule', "moduleID=$moduleID");
                    echo html::a('#', $module, '', "$searchKey onclick=\"setFormAction('$actionLink', 'hiddenwin', '#bugList')\" data-key='$currentModuleID'");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php if($canBatchAssignTo):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn" id="mulAssigned"><?php echo $lang->bug->assignedTo;?> <span class="caret"></span></button>
            <?php $withSearch = count($memberPairs) > 6;?>
            <?php if($withSearch):?>
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
                $actionLink = $this->createLink('bug', 'batchAssignTo', "productID={$product->id}&type=product");
                echo html::select('assignedTo', $memberPairs, '', 'class="hidden"');
                foreach ($memberPairs as $key => $value)
                {
                    if(empty($key)) continue;
                    $searchKey = $withSearch ? ('data-key="' . zget($membersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
                    echo html::a("javascript:$(\"#assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\", \"#bugList\")", $value, '', $searchKey);
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
<script>
$('#module' + moduleID).closest('li').addClass('active');
var branchID = $.cookie('bugBranch');
$('#branch' + branchID).closest('li').addClass('active');
<?php if($browseType == 'bysearch'):?>
if($('#query li.active').size() == 0) $.toggleQueryBox(true);
<?php endif;?>
<?php if(!empty($useDatatable)):?>
$(function(){$('#bugForm').table();})
<?php endif;?>
<?php $this->app->loadConfig('qa', '', false);?>
<?php if(isset($config->qa->homepage) and $config->qa->homepage != 'browse' and $config->global->flow == 'full'):?>
$(function(){$('#modulemenu .nav li:last').after("<li class='right'><a style='font-size:12px' href='javascript:setHomepage(\"qa\", \"browse\")'><i class='icon icon-cog'></i> <?php echo $lang->homepage?></a></li>")});
<?php endif;?>
<?php if(count($bugs) <= 2):?>
$('#bugForm .table-footer .table-actions #assignedTo').closest('.btn-group.dropup').removeClass('dropup').addClass('dropdown');
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
