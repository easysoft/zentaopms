<?php
/**
 * The browse view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
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
include './datatable.fix.html.php';
include './caseheader.html.php';
js::set('browseType',       $browseType);
js::set('caseBrowseType',   ($browseType == 'bymodule' and $this->session->caseBrowseType == 'bysearch') ? 'all' : $this->session->caseBrowseType);
js::set('moduleID'  ,       $moduleID);
js::set('confirmDelete',    $lang->testcase->confirmDelete);
js::set('batchDelete',      $lang->testcase->confirmBatchDeleteSceneCase);
js::set('productID',        $productID);
js::set('branch',           $branch);
js::set('suiteID',          $suiteID);
js::set('automation',       !empty($automation) ? $automation->id : 0);
js::set('runCaseConfirm',   $lang->zanode->runCaseConfirm);
js::set('confirmURL',       $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy&from=testcase&taskID=0&confirm=yes"));
js::set('cancelURL',        $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=$orderBy&from=testcase&taskID=0&confirm=no"));
js::set('orderBy',          $orderBy);
js::set('differentProduct', $lang->testcase->differentProduct);
js::set('langRowIndex',     $lang->testcase->rowIndex);
js::set('langNestTotal',    $lang->testcase->nestTotal);
js::set('langNormal',       $lang->testcase->normal);
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
    <?php $useDatatable = false;?>
    <div class="table-empty-tip">
      <p>
        <?php
        if($this->cookie->onlyScene)
        {
            echo "<span class='text-muted'>{$lang->testcase->noScene}</span>";
            if((empty($productID) || common::canModify('product', $product)) && common::hasPriv('testcase', 'createScene') && $browseType != 'bysuite')
            {
                $initModule = isset($moduleID) ? (int)$moduleID : 0;
                if($productID) echo html::a($this->createLink('testcase', 'createScene', "productID=$productID&branch=$branch&moduleID=$initModule"), "<i class='icon icon-plus'></i> " . $lang->testcase->newScene, '', "class='btn btn-info' data-app='{$this->app->tab}'");
            }
        }
        else
        {
            echo "<span class='text-muted'>{$lang->testcase->noCase}</span>";
            if((empty($productID) || common::canModify('product', $product)) && common::hasPriv('testcase', 'create') && $browseType != 'bysuite')
            {
                $initModule = isset($moduleID) ? (int)$moduleID : 0;
                echo html::a($this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule"), "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-info' data-app='{$this->app->tab}'");
            }

            if(common::hasPriv('testsuite', 'linkCase') && $browseType == 'bysuite')
            {
                echo html::a($this->createLink('testsuite', 'linkCase', "suiteID=$param"), "<i class='icon icon-plus'></i> " . $lang->testsuite->linkCase, '', "class='btn btn-info' data-app='{$this->app->tab}'");
            }
        }
        ?>
      </p>
    </div>
    <?php else:?>
    <?php $datatableId  = $this->moduleName . ucfirst($this->methodName);?>
    <?php $useDatatable = (!empty($config->testcase->useDatatable) && isset($config->datatable->$datatableId->mode) && $config->datatable->$datatableId->mode == 'datatable');?>
    <form class='main-table table-case' data-nested='true' data-expand-nest-child='false' data-checkable='true' data-enable-empty-nested-row='true' data-replace-id='caseTableList' data-preserve-nested='true'
    id='caseForm' method='post' <?php if(!$useDatatable) echo "data-ride='table'";?>>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right setting"></nav>
      </div>
      <?php
      $vars = $projectParam . "productID=$productID&branch=$branch&browseType=$browseType&param=$param&caseType=$caseType&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";

      if($useDatatable)  include '../../common/view/datatable.html.php';
      else               include '../../common/view/tablesorter.html.php';

      if($config->testcase->needReview || !empty($config->testcase->forceReview)) $config->testcase->datatable->fieldList['actions']['width'] = '190';
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
      $canBatchAction             = ($canBatchRun || $canBatchEdit || $canBatchDelete || $canBatchCaseTypeChange || $canBatchConfirmStoryChange || $canBatchChangeModule || $canImportToLib);
      ?>
      <?php if(!$useDatatable) echo '<div class="table-responsive">';?>
      <table class='table has-sort-head table-fixed table-nested table has-sort-head<?php if($useDatatable) echo ' datatable';?>' id='caseList' data-fixed-left-width='<?php echo $widths['leftWidth']?>' data-fixed-right-width='<?php echo $widths['rightWidth']?>' data-checkbox-name='caseIDList[]'>
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
        <tbody id='caseTableList'>
          <?php $this->testcase->printRow($cases, $setting, $users, $branchOption, $modulePairs, $browseType, $useDatatable ? 'datatable' : 'table');?>
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
            $misc = $canBatchRun ? "onclick=\"confirmAction('$actionLink', '', '#caseList')\"" : "disabled='disabled'";
            echo html::commonButton($lang->testtask->runCase, $misc);

            foreach($cases as $case) $caseProductIds[$case->product] = $case->product;
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
                      $actionLink = $this->createLink('testcase', 'batchCaseTypeChange', "type=$key");
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
          <?php if(common::hasPriv('testcase', 'batchChangeBranch') and $this->session->currentProductType and $this->session->currentProductType != 'normal'):?>
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

          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->testcase->sceneb;?> <span class="caret"></span></button>
            <?php $withSearch = count($scenes) > 6;?>
            <?php if($withSearch):?>
            <div class="dropdown-menu search-list search-box-sink" data-ride="searchList">
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="userSearchBox2" type="search" autocomplete="off" class="form-control search-input">
                <label for="userSearchBox2" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
              <?php $scenesPinYin = common::convert2Pinyin($scenes);?>
            <?php else:?>
            <div class="dropdown-menu search-list">
            <?php endif;?>
              <div class="list-group">
                <?php
                foreach($scenes as $sceneID => $scene)
                {
                    $searchKey = $withSearch ? ('data-key="' . zget($scenesPinYin, $scene, '') . '"') : '';
                    $actionLink = $this->createLink('testcase', 'batchChangeScene', "sceneID=$sceneID");
                    echo html::a('#', $scene, '', "title='$scene' $searchKey onclick=\"setFormAction('$actionLink', 'hiddenwin')\"");
                }
                ?>
              </div>
            </div>
          </div>
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
<div id="sceneDragModal" class="modal fade">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">x<?php echo $lang->close;?></span></button>
        <h4 class="modal-title"><?php echo $lang->testcase->dragModalTitle;?></h4>
      </div>
      <div class="modal-body">
        <?php echo $lang->testcase->dragModalMessage;?>
      </div>
      <div class="modal-footer">
        <button onclick="runToChange()" type="button" class="btn btn-primary"><?php echo $lang->testcase->dragModalChangeScene;?></button>
        <button onclick="runToOrder()" type="button" class="btn btn-primary"><?php echo $lang->testcase->dragModalChangeOrder;?></button>
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $lang->close;?></button>
      </div>
    </div>
  </div>
</div>
<script>
$('#module' + moduleID).closest('li').addClass('active');
$('#' + caseBrowseType + 'Tab').addClass('btn-active-text').find('.text').after(" <span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>");
function runAutocase()
{
    var caseIDList = [];
    $.each($('input[name^=caseIDList]:checked'),function(){
        caseIDList.push($(this).val());
    });

    var url = createLink('zanode', 'ajaxRunZTFScript', 'scriptID=' + automation)

    var postData = {'caseIDList' : caseIDList.join(',')};

    var response = true;
    $.post(url, postData, function(result)
    {
        if(result.result == 'fail')
        {
            alert(result.message);
            response = false;
        }
    }, 'json');

    return response;
}

function confirmAction(obj)
{
    var autoRun = 'no';
    $.each($('input[name^=caseIDList]:checked'),function(){
       var dataAuto = $(this).parents('tr').attr('data-auto');
       if(dataAuto == 'auto') autoRun = dataAuto;
    });

    if(autoRun == 'no' || !automation)
    {
        setFormAction(cancelURL, '', '#caseList');
        return false;
    }

    if(confirm(runCaseConfirm))
    {
        var result = runAutocase();
        if(result) setFormAction(confirmURL, '', '#caseList');
    }
    else
    {
        setFormAction(cancelURL, '', '#caseList');
    }
    return false;
}
<?php if($useDatatable):?>
$(function(){$('#caseForm').table();})
<?php endif;?>
</script>

<script>
function toChange(source, target)
{
    if(!checkProduct(source, target)) return;
    $.post(createLink('testcase', 'changeScene'), {'sourceID' : source.id, 'targetID' : target.id}, function(data){
        window.location.reload();
    });
}

function toOrder(source,target)
{
    if(!checkProduct(source, target)) return;

    let dataList = [];
    $('#caseTableList > tr').each(function(i, elem){
        const rowData = $(elem).data();
        if(rowData.product == target.product) {
            const rowParent = rowData.parent.id || rowData.parent;
            if(rowData.isScene != target.isScene || (rowData.id != source.id && rowParent != target.parent)) return true;

            const id = rowData.isScene ? rowData.id : rowData.id.replace('case_', '');
            const data = {id, order: rowData.order};
            dataList.push(data);
        }
    });

    const type     = source.isScene == 1 ? 'scene' : 'case';
    const sourceID = source.isScene == 1 ? source.id : source.id.replace('case_', '');
    const targetID = target.isScene == 1 ? target.id : target.id.replace('case_', '');
    const postData = {type, sourceID, targetID, dataList};
    $.post(createLink('testcase', 'updateOrder'), postData, function(){
        window.location.reload();
    });
}

function runToChange()
{
    var source = $("#sceneDragModal").data("source");
    var target = $("#sceneDragModal").data("target");

    $("#sceneDragModal").modal("hide");

    toChange(source, target);
}

function runToOrder()
{
    var source = $("#sceneDragModal").data("source");
    var target = $("#sceneDragModal").data("target");

    $("#sceneDragModal").modal("hide");

    toOrder(source, target);
}

function checkProduct(source, target)
{
    if(source.id == target.id) return false;

    if(source.product !== target.product)
    {
        bootbox.alert(differentProduct);
        return false;
    }

    return true;
}

$(function()
{
    //下面是 source 和 target 的数据结构 $dom 是行tr 对应的Jquery 对象
    // id: id,
    // index: i,
    // parent: parent,
    // dataNested: dataNested,
    // nestPath: nestPath,
    // isScene: isScene,
    // boundary: {x:pos.x, y:pos.y, w:size.w, h:size.h},
    // $dom: $row,
    var xtable = $('#caseForm').data('zui.table');
    var trList = $("#caseTableList").find("tr");
    for(var i=0; i<trList.length; i++){
        const data = $(trList[i]).data();
        if(data.isScene == 0) continue;

        xtable.toggleNestedRows(data.id, true, true);
    }

    DtSort.sort({
        container: "#caseTableList",
        canAccept: function(source, target, sameLevel, sourceMgr, targetMgr){
            if(sameLevel) return source.isScene == 0 || target.isScene == 1; // 同级别不能场景拖动到用例，其他都允许。
            return target.isScene == 1; // 跨级别只能拖动到场景，其他都不允许。
        },
        finish: function(source, target, sameLevel , sourceMgr, targetMgr){
            if(sameLevel) { // 同级别拖动
                if(source.isScene == 1 && target.isScene == 1) {
                    /* 场景拖动到场景，弹窗询问是更改所属场景还是调整顺序。*/
                    $("#sceneDragModal").data("source", source);
                    $("#sceneDragModal").data("target", target);
                    $("#sceneDragModal").modal("show");
                } else if(source.isScene == 0 && target.isScene == 0) {
                    /* 用例拖动到用例，调整顺序。*/
                    toOrder(source, target);
                } else if(source.isScene == 0 && target.isScene == 1) {
                    /* 用例拖动到场景，更改所属场景。*/
                    toChange(source, target);
                }
            } else { // 跨级别拖动
                const sourceParent = source.parent.id || source.parent;
                if(target.isScene == 1 && source.parent != target.id) toChange(source, target); // 拖动到非父场景的场景，更改所属场景。
            }
        }
    });
});
</script>

<?php include '../../common/view/footer.html.php';?>
