<?php
/**
 * The view file of case module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     case
 * @version     $Id: view.html.php 594 2010-03-27 13:44:07Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datatable.fix.html.php';?>
<?php include './caseheader.html.php';?>
<?php js::set('confirmUnlink', $lang->testtask->confirmUnlinkCase)?>
<?php js::set('taskCaseBrowseType', ($browseType == 'bymodule' and $this->session->taskCaseBrowseType == 'bysearch') ? 'all' : $this->session->taskCaseBrowseType);?>
<?php js::set('browseType', $browseType);?>
<?php js::set('moduleID', $moduleID);?>
<?php js::set('automation',     !empty($automation) ? $automation->id : 0);?>
<?php $this->app->loadLang('zanode');?>
<?php js::set('runCaseConfirm', $lang->zanode->runCaseConfirm);?>
<?php js::set('confirmURL',     $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=id_desc&from=testtask&taskID=$taskID&confirm=yes"));?>
<?php js::set('cancelURL',      $this->createLink('testtask', 'batchRun', "productID=$productID&orderBy=id_desc&from=testtask&taskID=$taskID&confirm=no"));?>
<div id='mainContent' class='main-row fade'>
  <div class='side-col' id='sidebar'>
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class='cell'><?php echo $moduleTree;?></div>
  </div>
  <div class='main-col'>
    <div class="cell" id="queryBox" data-module='testtask'></div>
    <?php
    $datatableId  = $this->moduleName . ucfirst($this->methodName);
    $useDatatable = (isset($config->datatable->$datatableId->mode) and $config->datatable->$datatableId->mode == 'datatable');
    ?>
    <form class='main-table table-cases' data-hot='true' method='post' name='casesform' id='casesForm' <?php if(!$useDatatable) echo "data-ride='table'";?>>
      <div class="table-header fixed-right">
        <nav class="btn-toolbar pull-right"></nav>
      </div>
      <?php
      $vars = "taskID=$task->id&browseType=$browseType&param=$param&orderBy=%s&recToal={$pager->recTotal}&recPerPage={$pager->recPerPage}";

      $canBatchEdit   = common::hasPriv('testcase', 'batchEdit');
      $canBatchUnlink = common::hasPriv('testtask', 'batchUnlinkCases');
      $canBatchAssign = common::hasPriv('testtask', 'batchAssign');
      $canBatchRun    = common::hasPriv('testtask', 'batchRun');

      $canBatchAction = ($canBeChanged and ($canBatchEdit or $canBatchUnlink or $canBatchAssign or $canBatchRun));

      if($useDatatable) include '../../common/view/datatable.html.php';
      if(!$useDatatable) include '../../common/view/tablesorter.html.php';

      $config->testcase->datatable->defaultField = $config->testtask->datatable->defaultField;
      $config->testcase->datatable->fieldList['actions']['width'] = '120';

      $setting = $this->datatable->getSetting('testtask');
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
                    $this->datatable->printHead($value, $orderBy, $vars, $canBatchAction);
                    $columns ++;
                }
            }
            ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach($runs as $run):?>
            <tr data-id='<?php echo $run->id?>' data-auto='<?php echo $run->auto;?>'>
              <?php foreach($setting as $key => $value) $this->testtask->printCell($value, $run, $users, $task, $branches, $useDatatable ? 'datatable' : 'table');?>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      <?php if(!$useDatatable) echo '</div>';?>
      <?php if($runs):?>
      <div class='table-footer'>
        <?php if($canBatchAction):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class='table-actions btn-toolbar'>
          <div class='btn-group dropup'>
            <?php
            $actionLink = $this->createLink('testcase', 'batchEdit', "productID=$productID&branch=all");
            $misc       = $canBatchEdit ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";
            echo html::commonButton($lang->edit, $misc);
            ?>
            <?php if($canBatchUnlink):?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu'>
              <?php
              $actionLink = $this->createLink('testtask', 'batchUnlinkCases', "taskID=$task->id");
              $misc       = "onclick=\"setFormAction('$actionLink')\"";
              echo "<li>" . html::a('javascript:;', $lang->testtask->unlinkCase, '', $misc) . "</li>";
              ?>
            </ul>
            <?php endif;?>
          </div>
          <?php if($canBatchAssign):?>
          <div class="btn-group dropup">
            <button data-toggle="dropdown" type="button" class="btn"><?php echo $lang->testtask->assign;?> <span class="caret"></span></button>
            <?php
            $withSearch = count($assignedToList) > 10;
            $actionLink = inLink('batchAssign', "taskID=$task->id");
            echo html::select('assignedTo', $assignedToList, '', 'class="hidden"');
            ?>
            <div class="dropdown-menu search-list<?php if($withSearch) echo ' search-box-sink';?>" data-ride="searchList">
              <?php if($withSearch):?>
              <?php $membersPinYin = common::convert2Pinyin($assignedToList);?>
              <div class="input-control search-box has-icon-left has-icon-right search-example">
                <input id="userSearchBox" type="search" autocomplete="off" class="form-control search-input">
                <label for="userSearchBox" class="input-control-icon-left search-icon"><i class="icon icon-search"></i></label>
                <a class="input-control-icon-right search-clear-btn"><i class="icon icon-close icon-sm"></i></a>
              </div>
              <?php endif;?>
              <div class="list-group">
              <?php foreach ($assignedToList as $key => $value):?>
                  <?php
                  if(empty($key) or $key == 'closed') continue;
                  $searchKey = $withSearch ? ('data-key="' . zget($membersPinYin, $value, '') . " @$key\"") : "data-key='@$key'";
                  echo html::a("javascript:$(\"#assignedTo\").val(\"$key\");setFormAction(\"$actionLink\", \"hiddenwin\")", $value, '', $searchKey);
                  ?>
              <?php endforeach;?>
              </div>
            </div>
          </div>
          <?php endif;?>
          <?php
          if($canBatchRun)
          {
              echo html::commonButton($lang->testtask->runCase, "onclick=\"confirmAction()\"");
          }
          ?>
        </div>
        <?php endif;?>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php else:?>
      <div class="table-empty-tip">
        <p><span class="text-muted"><?php echo $lang->testcase->noCase;?></span> <?php if($canBeChanged) common::printLink('testtask', 'linkCase', "taskID={$taskID}", "<i class='icon icon-link'></i> " . $lang->testtask->linkCase, '', "class='btn btn-info'");?></p>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<script>
$('#module' + moduleID).addClass('active');
$('#' + taskCaseBrowseType + 'Tab').addClass('btn-active-text');
<?php if($browseType == 'bysearch'):?>
$shortcut = $('#QUERY<?php echo (int)$param;?>Tab');
if($shortcut.size() > 0)
{
    $shortcut.addClass('active');
    $('#bysearchTab').removeClass('active');
    $('#querybox').removeClass('show');
}
<?php endif;?>
<?php if($useDatatable):?>
$(function(){$('#casesForm').table();})
<?php endif;?>
$("thead").find('.c-assignedTo').attr('class', '');
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
</script>
<?php include '../../common/view/footer.html.php';?>
