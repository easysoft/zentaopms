<?php
/**
 * The browse view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     testcase
 * @version     $Id: browse.html.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datepicker.html.php';
include '../../common/view/datatable.fix.html.php';
include './caseheader.html.php';
js::set('browseType',     $browseType);
js::set('caseBrowseType', ($browseType == 'bymodule' and $this->session->caseBrowseType == 'bysearch') ? 'all' : $this->session->caseBrowseType);
js::set('moduleID'  ,     $moduleID);
js::set('confirmDelete',  $lang->testcase->confirmDelete);
js::set('batchDelete',    $lang->testcase->confirmBatchDelete);
js::set('productID',      $productID);
js::set('branch',         $branch);
js::set('suiteID',        $suiteID);
?>
<?php if($this->app->tab == 'project'):?>
<style>
#subHeader #dropMenu .col-left .list-group {margin-bottom: 0px; padding-top: 10px;}
#subHeader #dropMenu .col-left {padding-bottom: 0px;}
</style>
<?php endif;?>

<div id="mainContent" class="main-row fade">
  <div class='side-col' id='sidebar'>
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class='cell'>
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted"><?php echo $lang->testcase->noModule;?></div>
      <hr class="space">
      <?php endif;?>
      <?php echo $moduleTree;?>
      <div class='text-center'>
        <?php if(!empty($productID)) common::printLink('tree', 'browse', "productID=$productID&view=case&currentModuleID=0&branch=0&from={$this->app->tab}", $lang->tree->manage, '', "class='btn btn-info btn-wide' data-app='{$this->app->tab}'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class='main-col'>
    <div id='queryBox' data-module='testcase' class='cell<?php if($browseType == 'bysearch') echo ' show';?>'></div>
    <?php if(empty($cases)):?>
    <?php $useDatatable = '';?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->testcase->noCase;?></span>
        <?php if((empty($productID) or common::canModify('product', $product)) and common::hasPriv('testcase', 'create') and $browseType != 'bysuite'):?>
        <?php $initModule = isset($moduleID) ? (int)$moduleID : 0;?>
        <?php echo html::a($this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule"), "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-info' data-app='{$this->app->tab}'");?>
        <?php endif;?>

        <?php if(common::hasPriv('testsuite', 'linkCase') and $browseType == 'bysuite'):?>
        <?php echo html::a($this->createLink('testsuite', 'linkCase', "suiteID=$param"), "<i class='icon icon-plus'></i> " . $lang->testsuite->linkCase, '', "class='btn btn-info' data-app='{$this->app->tab}'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <?php
    $datatableId  = $this->moduleName . ucfirst($this->methodName);
    $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
    ?>
    <form class='main-table table-case' id='caseForm' method='post' <?php if(!$useDatatable) echo "data-ride='table'";?>>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right setting"></nav>
      </div>
      <?php
      $vars = $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";

      if($useDatatable)  include '../../common/view/datatable.html.php';
      else               include '../../common/view/tablesorter.html.php';

      if($config->testcase->needReview or !empty($config->testcase->forceReview)) $config->testcase->datatable->fieldList['actions']['width'] = '180';
      $setting = $this->datatable->getSetting('testcase');
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      $columns = 0;

      $canBatchRun                = common::hasPriv('testtask', 'batchRun');
      $canBatchEdit               = common::hasPriv('testcase', 'batchEdit');
      $canBatchDelete             = common::hasPriv('testcase', 'batchDelete');
      $canBatchCaseTypeChange     = common::hasPriv('testcase', 'batchCaseTypeChange');
      $canBatchConfirmStoryChange = common::hasPriv('testcase', 'batchConfirmStoryChange');
      $canBatchChangeModule       = common::hasPriv('testcase', 'batchChangeModule');
      $canImportToLib             = common::hasPriv('testcase', 'importToLib');
      $canBatchAction             = ($canBatchRun or $canBatchEdit or $canBatchDelete or $canBatchCaseTypeChange or $canBatchConfirmStoryChange or $canBatchChangeModule or $canImportToLib);
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='caseList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>' data-checkbox-name='caseIDList[]'>
        <thead>
          <tr>
          <?php
          foreach($setting as $key => $value)
          {
              if($value->show)
              {
                  $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
                  $columns ++;
              }
          }
          ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($cases as $case):?>
          <tr data-id='<?php echo $case->id?>'>
            <?php foreach($setting as $key => $value) $this->testcase->printCell($value, $case, $users, $branchOption, $modulePairs, $browseType, $useDatatable ? 'datatable' : 'table');?>
          </tr>
          <?php $caseProductIds[$case->product] = $case->product;?>
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
            $actionLink = $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy");
            $misc = $canBatchRun ? "onclick=\"setFormAction('$actionLink', '', '#caseList')\"" : "disabled='disabled'";
            echo html::commonButton($lang->testtask->runCase, $misc);

            $caseProductID = count($caseProductIds) > 1 ? 0 : $productID;
            $actionLink    = $this->createLink('testcase', 'batchEdit', "productID=$caseProductID&branch=$branch");
            $misc          = $canBatchEdit ? "onclick=\"setFormAction('$actionLink', '', '#caseList')\"" : "disabled='disabled'";
            echo html::commonButton($lang->edit, $misc);
            ?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu' id='moreActionMenu'>
              <?php
              if(common::hasPriv('testcase', 'batchReview') and ($config->testcase->needReview or !empty($config->testcase->forceReview)))
              {
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->testcase->review, '', "id='reviewItem'");
                  echo "<ul class='dropdown-menu'>";
                  unset($lang->testcase->reviewResultList['']);
                  foreach($lang->testcase->reviewResultList as $key => $result)
                  {
                      $actionLink = $this->createLink('testcase', 'batchReview', "result=$key");
                      echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#caseList')\"") . '</li>';
                  }
                  echo '</ul></li>';
              }

              if($canBatchDelete)
              {
                  $actionLink = $this->createLink('testcase', 'batchDelete', "productID=$productID");
                  $misc       = "onclick=\"confirmBatchDelete('$actionLink')\"";
                  echo "<li>" . html::a('#', $lang->delete, '', $misc) . "</li>";
              }

              if($canBatchCaseTypeChange)
              {
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->testcase->type, '', "id='typeChangeItem'");
                  echo "<ul class='dropdown-menu'>";
                  unset($lang->testcase->typeList['']);
                  foreach($lang->testcase->typeList as $key => $result)
                  {
                      $actionLink = $this->createLink('testcase', 'batchCaseTypeChange', "result=$key");
                      echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin', '#caseList')\"") . '</li>';
                  }
                  echo '</ul></li>';
              }

              if($canBatchConfirmStoryChange)
              {
                  $actionLink = $this->createLink('testcase', 'batchConfirmStoryChange', "productID=$productID");
                  $misc       = "onclick=\"setFormAction('$actionLink', '', '#caseList')\"";
                  echo "<li>" . html::a('#', $lang->testcase->confirmStoryChange, '', $misc) . "</li>";
              }
              ?>
            </ul>
          </div>
          <?php if(common::hasPriv('testcase', 'batchChangeBranch') and $this->session->currentProductType != 'normal'):?>
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
                    $actionLink = $this->createLink('testcase', 'batchChangeBranch', "branchID=$branchID");
                    echo html::a('#', $branchName, '', "$searchKey onclick=\"setFormAction('$actionLink', 'hiddenwin', '#caseList')\"");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php if($canBatchChangeModule and !empty($productID)):?>
          <?php if($product->type == 'normal' or ($product->type != 'normal' and $branch !== 'all')):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->moduleAB;?> <span class="caret"></span></button>
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
                foreach($modules as $moduleId => $module)
                {
                    $searchKey = $withSearch ? ('data-key="' . zget($modulesPinYin, $module, '') . '"') : '';
                    $actionLink = $this->createLink('testcase', 'batchChangeModule', "moduleID=$moduleId");
                    echo html::a('#', $module, '', "title='$module' $searchKey onclick=\"setFormAction('$actionLink', 'hiddenwin', '#caseList')\"");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php endif;?>
          <?php
          if($canImportToLib)
          {
              $actionLink = '#importToLib';
              echo html::a($actionLink, $lang->testcase->importToLib, '', "class='btn btn-primary' data-toggle='modal'");
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
<div class="modal fade" id="importToLib">
  <div class="modal-dialog mw-500px">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="icon icon-close"></i></button>
        <h4 class="modal-title"><?php echo $lang->testcase->importToLib;?></h4>
      </div>
      <div class="modal-body">
        <form method='post' class='form-ajax not-watch' action='<?php echo $this->createLink('testcase', 'importToLib');?>'>
          <table class='table table-form'>
            <tr>
              <td class='select-lib'><?php echo $lang->testcase->selectLibAB;?></td>
              <td class='required'><?php echo html::select('lib', $libraries, '', "class='form-control chosen' id='lib'");?></td>
            </tr>
            <tr>
              <?php echo html::hidden('caseIdList', '');?>
              <td colspan='2' class='text-center'><?php echo html::submitButton($lang->testcase->import);?></td>
            </tr>
          </table>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
$('#module' + moduleID).closest('li').addClass('active');
$('#' + caseBrowseType + 'Tab').addClass('btn-active-text').find('.text').after(" <span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>");
<?php if($useDatatable):?>
$(function(){$('#caseForm').table();})
<?php endif;?>
</script>
<?php include '../../common/view/footer.html.php';?>
