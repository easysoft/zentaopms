<?php
/**
 * The library view file of testsuite module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testsuite
 * @version     $Id: library.html.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datatable.fix.html.php';
js::set('browseType',    $browseType);
js::set('moduleID',      $moduleID);
js::set('confirmDelete', $lang->testsuite->confirmDelete);
js::set('batchDelete',   $lang->testcase->confirmBatchDelete);
js::set('flow',   $this->config->global->flow);
?>
<?php if($this->config->global->flow == 'onlyTest'):?>
<style>
.nav > li > .btn-group > a, .nav > li > .btn-group > a:hover, .nav > li > .btn-group > a:focus{background: #1a4f85; border-color: #164270;}
#querybox #searchform{border-bottom: 1px solid #ddd; margin-bottom: 20px;}
</style>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
  </div>
  <ul class='submenu hidden'>
    <?php echo "<li id='bysearchTab'><a href='#'><i class='icon-search icon'></i>&nbsp;{$lang->testcase->bySearch}</a></li> ";?>

    <li class='right'>
      <div class='btn-group' id='createActionMenu'>
        <?php
        $misc = common::hasPriv('testsuite', 'createCase') ? "class='btn btn-primary'" : "class='btn btn-primary disabled'";
        $link = common::hasPriv('testsuite', 'createCase') ?  $this->createLink('testsuite', 'createCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)) : '#';
        echo html::a($link, "<i class='icon-plus'></i>" . $lang->testcase->create, '', $misc);
        ?>
        <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'>
          <span class='caret'></span>
        </button>
        <ul class='dropdown-menu pull-right'>
        <?php 
        $misc = common::hasPriv('testsuite', 'batchCreateCase') ? '' : "class=disabled";
        $link = common::hasPriv('testsuite', 'batchCreateCase') ?  $this->createLink('testsuite', 'batchCreateCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)) : '#';
        echo "<li>" . html::a($link, $lang->testcase->batchCreate, '', $misc) . "</li>";
        ?>
        </ul>
      </div>
    </li>

    <li class='right'>
      <?php
      $link = common::hasPriv('testsuite', 'import') ?  $this->createLink('testsuite', 'import', "libID=$libID") : '#';
      if(common::hasPriv('testsuite', 'import')) echo html::a($link, "<i class='icon-upload-alt'></i> " . $lang->testcase->importFile, '', "class='export'");
      ?>
    </li>

    <li class='right'>
      <?php
      $link = common::hasPriv('testsuite', 'exportTemplet') ?  $this->createLink('testsuite', 'exportTemplet', "libID=$libID") : '#';
      if(common::hasPriv('testsuite', 'exportTemplet')) echo html::a($link, "<i class='icon-download-alt'></i> " . $lang->testsuite->exportTemplet, '', "class='export'");
      ?>
    </li>
  </ul>

  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
<?php else:?>
<div id='pageActions'>
  <?php common::printLink('testsuite', 'libView', "libID=$libID", "<i class='icon icon-file-text'> </i>" . $lang->testsuite->view, '', "class='btn'");?>
</div>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title">
      <?php
      $this->app->loadLang('tree');
      echo isset($moduleID) ? $moduleName : $this->lang->tree->all;
      if(!empty($moduleID))
      {
          $removeLink = $browseType == 'bymodule' ? inlink('library', "libID=$libID&browseType=$browseType&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("libCaseModule")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class='btn-toolbar pull-left'>
    <?php
    if(common::hasPriv('testsuite', 'library'))
    {
        echo html::a($this->inlink('library', "libID=$libID&browseType=all"), "<span class='text'>{$lang->testcase->allCases}</span>", '', "id='allTab' class='btn btn-link'");
        if($config->testcase->needReview or !empty($config->testcase->forceReview)) echo html::a($this->inlink('library', "libID=$libID&browseType=wait"), "<span class='text'>" . $lang->testcase->statusList['wait'] . "</span>", '', "id='waitTab' class='btn btn-link'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->testcase->bySearch;?></a>
  </div>
  <div class='btn-toolbar pull-right'>
    <div class='btn-group'>
     <?php common::printLink('testsuite', 'exportTemplet', "libID=$libID", "<i class='icon icon-export muted'> </i>" . $lang->testsuite->exportTemplet, '', "class='btn btn-link export'");?>
     <?php common::printLink('testsuite', 'import', "libID=$libID", "<i class='icon muted icon-import'> </i>" . $lang->testcase->importFile, '', "class='btn btn-link export'");?>
    </div>
    <?php $params = "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0);?>
    <?php common::printLink('testsuite', 'batchCreateCase', $params, "<i class='icon-plus'></i>" . $lang->testcase->batchCreate, '', "class='btn btn-secondary'");?>
    <?php common::printLink('testsuite', 'createCase', $params, "<i class='icon-plus'></i>" . $lang->testcase->create, '', "class='btn btn-primary'");?>
  </div>
  <div id='querybox' class='<?php if($browseType =='bysearch') echo 'show';?>'></div>
</div>
<?php endif;?>
<div id="mainContent" class="main-row">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted">
        <?php echo $lang->testsuite->noModule;?>
      </div>
      <hr class="space">
      <?php endif;?>
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browse', "libID=$libID&view=caselib", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
    <div class="cell" id="queryBox"></div>
    <form class="main-table table-testcase" data-ride="table" method="post" id='testcaseForm'>
      <?php $canBatchEdit         = common::hasPriv('testcase', 'batchEdit');?>
      <?php $canBatchDelete       = common::hasPriv('testcase', 'batchDelete');?>
      <?php $canBatchReview       = common::hasPriv('testcase', 'batchReview') and ($config->testcase->needReview or !empty($config->testcase->forceReview));?>
      <?php $canBatchChangeModule = common::hasPriv('testcase', 'batchChangeModule');?>
      <?php $canBatchAction       = $canBatchEdit or $canBatchDelete or $canBatchReview or $canBatchChangeModule;?>
      <?php $vars = "libID=$libID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
      <table class='table has-sort-head' id='caseList'>
        <thead>
          <tr>
            <th class='c-id'>
              <?php if($canBatchAction):?>
              <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
                <label></label>
              </div>
              <?php endif;?>
              <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
            </th>
            <th class='c-pri'>   <?php common::printOrderLink('pri',      $orderBy, $vars, $lang->priAB);?></th>
            <th class='text-left'><?php common::printOrderLink('title',   $orderBy, $vars, $lang->testcase->title);?></th>
            <th class='c-type'>  <?php common::printOrderLink('type',     $orderBy, $vars, $lang->typeAB);?></th>
            <th class='c-user'>  <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
            <th class='c-status'><?php common::printOrderLink('status',   $orderBy, $vars, $lang->statusAB);?></th>
            <th class='w-130px text-center'><?php echo $lang->actions;?></th>
          </tr>
        </thead>
        <tbody>
          <?php if($cases):?>
          <?php foreach($cases as $case):?>
          <tr>
            <td class='c-id'>
              <?php if($canBatchAction):?>
              <?php echo html::checkbox('caseIDList', array($case->id => sprintf('%03d', $case->id)));?>
              <?php else:?>
              <?php echo sprintf('%03d', $case->id);?>
              <?php endif;?>
            </td>
            <td><span class='label-pri label-pri-<?php echo $case->pri;?>' title='<?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?></span></td>
            <td class='text-left' title="<?php echo $case->title?>">
              <?php if($modulePairs and $case->module) echo "<span title='{$lang->testcase->module}' class='label label-info label-badge'>{$modulePairs[$case->module]}</span> ";?>
              <?php $viewLink = $this->createLink('testcase', 'view', "caseID=$case->id&version=$case->version");?>
              <?php echo html::a($viewLink, $case->title, null, "style='color: $case->color'");?>
            </td>
            <td><?php echo $lang->testcase->typeList[$case->type];?></td>
            <td><?php echo $users[$case->openedBy];?></td>
            <td class='<?php if(isset($run)) echo $run->status;?> testcase-<?php echo $case->status?>'> <?php echo $lang->testcase->statusList[$case->status];?></td>
            <td class='c-actions'>
              <?php
              if($config->testcase->needReview or !empty($config->testcase->forceReview)) common::printIcon('testcase', 'review',  "caseID=$case->id", $case, 'list', 'glasses', '', 'iframe');
              common::printIcon('testcase',  'edit', "caseID=$case->id", $case, 'list');
              if(common::hasPriv('testcase', 'delete'))
              {
                  $deleteURL = $this->createLink('testcase', 'delete', "caseID=$case->id&confirm=yes");
                  echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"caseList\",confirmDelete)", '<i class="icon icon-trash"></i>', '', "title='{$lang->testcase->delete}' class='btn'");
              }
              ?>
            </td>
          </tr>
          <?php endforeach;?>
          <?php endif;?>
        </tbody>
      </table>
      <?php if($cases):?>
      <div class='table-footer'>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <div class="table-actions btn-toolbar">
          <div class='btn-group dropup'>
            <?php $actionLink = $this->createLink('testcase', 'batchEdit', "libID=$libID&branch=0&type=lib");?>
            <?php $misc       = $canBatchEdit ? "onclick=\"setFormAction('$actionLink')\"" : "disabled='disabled'";?>
            <?php echo html::commonButton($lang->edit, $misc);?>
            <?php if($canBatchDelete or $canBatchReview or $canBatchChangeModule):?>
            <button type='button' class='btn dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
            <ul class='dropdown-menu' id='moreActionMenu'>
              <?php
              if($canBatchDelete)
              {
                  $actionLink = $this->createLink('testcase', 'batchDelete', "libID=$libID");
                  $misc       = "onclick=\"confirmBatchDelete('$actionLink')\"";
                  echo "<li>" . html::a('#', $lang->delete, '', $misc) . "</li>";
              }

              if($canBatchReview)
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

              if($canBatchChangeModule)
              {
                  $withSearch = count($modules) > 8;
                  echo "<li class='dropdown-submenu'>";
                  echo html::a('javascript:;', $lang->testcase->moduleAB, '', "id='moduleItem'");
                  echo "<div class='dropdown-menu" . ($withSearch ? ' with-search' : '') . "'>";
                  echo '<ul class="dropdown-list">';
                  foreach($modules as $moduleId => $module)
                  {
                      $actionLink = $this->createLink('testcase', 'batchChangeModule', "moduleID=$moduleId");
                      echo "<li class='option' data-key='$moduleID'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink','hiddenwin')\"") . "</li>";
                  }
                  echo '</ul>';
                  if($withSearch) echo "<div class='menu-search'><div class='input-group input-group-sm'><input type='text' class='form-control' placeholder=''><span class='input-group-addon'><i class='icon-search'></i></span></div></div>";
                  echo '</div></li>';
              }
              ?>
            </ul>
            <?php endif;?>
          </div>
        </div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
      <?php endif;?>
    </form>
  </div>
</div>
<script>
$('#module' + moduleID).addClass('active'); 
$('#<?php echo $this->session->libBrowseType?>Tab').addClass('btn-active-text').append("<span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>");
if(flow == 'onlyTest')
{
    toggleSearch();

    $('#subNavbar > .nav > li').removeClass('active');
    $('#subNavbar > .nav > li[data-id=' + browseType + ']').addClass('active');
}
</script>
<?php include '../../common/view/footer.html.php';?>
