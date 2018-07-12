<?php
/**
 * The browse view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
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
        <?php common::printLink('tree', 'browse', "productID=$productID&view=case", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class='main-col'>
    <div id='queryBox' class='cell<?php if($browseType == 'bysearch') echo ' show';?>'></div>
    <?php if(empty($cases)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->testcase->noCase;?></span>
        <?php if(common::hasPriv('testcase', 'create')):?>
        <span class="text-muted"><?php echo $lang->youCould;?></span>
        <?php $initModule = isset($moduleID) ? (int)$moduleID : 0;?>
        <?php echo html::a($this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule"), "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class='main-table table-case' data-ride='table' id='batchForm' method='post'>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $datatableId  = $this->moduleName . ucfirst($this->methodName);
      $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
      $vars         = "productID=$productID&branch=$branch&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";
  
      if($useDatatable)  include '../../common/view/datatable.html.php';
      else               include '../../common/view/tablesorter.html.php';
      $setting = $this->datatable->getSetting('testcase');
      $widths  = $this->datatable->setFixedFieldWidth($setting);
      $columns = 0;
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
                  $this->datatable->printHead($value, $orderBy, $vars);
                  $columns ++;
              }
          }
          ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach($cases as $case):?>
          <tr data-id='<?php echo $case->id?>'>
            <?php foreach($setting as $key => $value) $this->testcase->printCell($value, $case, $users, $branches, $modulePairs, $browseType, $useDatatable ? 'datatable' : 'table');?>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php if(!$useDatatable) echo '</div>';?>
      <div class='table-footer'>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class='table-actions btn-toolbar'>
          <div class='btn-group dropup'>
            <?php
            $class = "class='disabled'";
  
            $actionLink = $this->createLink('testcase', 'batchEdit', "productID=$productID&branch=$branch");
            $misc       = common::hasPriv('testcase', 'batchEdit') ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
            echo html::commonButton($lang->edit, $misc);
            ?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu' id='moreActionMenu'>
              <?php 
              $actionLink = $this->createLink('testcase', 'batchDelete', "productID=$productID");
              $misc = common::hasPriv('testcase', 'batchDelete') ? "onclick=\"confirmBatchDelete('$actionLink')\"" : $class;
              echo "<li>" . html::a('#', $lang->delete, '', $misc) . "</li>";
  
              if(common::hasPriv('testcase', 'batchReview') and ($config->testcase->needReview or !empty($config->testcase->forceReview)))
              {
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->testcase->review, '', "id='reviewItem'");
                  echo "<ul class='dropdown-menu'>";
                  unset($lang->testcase->reviewResultList['']);
                  foreach($lang->testcase->reviewResultList as $key => $result)
                  {
                      $actionLink = $this->createLink('testcase', 'batchReview', "result=$key");
                      echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . '</li>';
                  }
                  echo '</ul></li>';
              }
  
              if(common::hasPriv('testcase', 'batchConfirmStoryChange'))
              {
                  $actionLink = $this->createLink('testcase', 'batchConfirmStoryChange', "productID=$productID");
                  $misc = common::hasPriv('testcase', 'batchConfirmStoryChange') ? "onclick=\"setFormAction('$actionLink')\"" : $class;
                  echo "<li>" . html::a('#', $lang->testcase->confirmStoryChange, '', $misc) . "</li>";
              }
  
  
              $actionLink = $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy");
              $misc = common::hasPriv('testtask', 'batchRun') ? "onclick=\"setFormAction('$actionLink')\"" : $class;
              echo "<li>" . html::a('#', $lang->testtask->runCase, '', $misc) . "</li>";
  
              if(common::hasPriv('testcase', 'batchCaseTypeChange'))
              {
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->testcase->type, '', "id='typeChangeItem'");
                  echo "<ul class='dropdown-menu'>";
                  unset($lang->testcase->typeList['']);
                  foreach($lang->testcase->typeList as $key => $result)
                  {
                      $actionLink = $this->createLink('testcase', 'batchCaseTypeChange', "result=$key");
                      echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . '</li>';
                  }
                  echo '</ul></li>';
              }
              ?>
            </ul>
          </div>
          <?php if(common::hasPriv('testcase', 'batchChangeBranch') and $this->session->currentProductType != 'normal'):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->product->branchName[$this->session->currentProductType];?> <span class="caret"></span></button>
            <?php $withSearch = count($branches) > 10;?>
            <?php if($withSearch):?>
            <div class="dropdown-menu search-list" data-ride="searchList">
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
            <?php else:?>
            <div class="dropdown-menu">
            <?php endif;?>
              <div class="list-group">
                <?php
                foreach($branches as $branchID => $branchName)
                {
                    $actionLink = $this->createLink('testcase', 'batchChangeBranch', "branchID=$branchID");
                    echo html::a('#', $branchName, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\" data-key='$branchID'");
                }
                ?>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php if(common::hasPriv('testcase', 'batchChangeModule')):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->story->moduleAB;?> <span class="caret"></span></button>
            <?php $withSearch = count($modules) > 10;?>
            <?php if($withSearch):?>
            <div class="dropdown-menu search-list" data-ride="searchList">
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
            <?php else:?>
            <div class="dropdown-menu">
            <?php endif;?>
              <div class="list-group">
                <?php
                foreach($modules as $moduleId => $module)
                {
                    $actionLink = $this->createLink('testcase', 'batchChangeModule', "moduleID=$moduleId");
                    echo html::a('#', $module, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\" data-key='$moduleID'");
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
$('#' + caseBrowseType + 'Tab').addClass('btn-active-text').find('.text').after("<span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>");
</script>
<?php include '../../common/view/footer.html.php';?>
