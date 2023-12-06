<?php
/**
 * The library view file of caselib module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     caselib
 * @version     $Id: library.html.php 5108 2013-07-12 01:59:04Z chencongzhi520@gmail.com $
 * @link        https://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/datatable.fix.html.php';
js::set('browseType',    $browseType);
js::set('moduleID',      $moduleID);
js::set('confirmDelete', $lang->testcase->confirmDelete);
js::set('batchDelete',   $lang->testcase->confirmBatchDelete);
js::set('flow',          $config->global->flow);
?>
<style>
.btn-group a i.icon-plus {font-size: 16px;}
.btn-group a.btn-primary {border-right: 1px solid rgba(255,255,255,0.2);}
.btn-group button.dropdown-toggle.btn-primary {padding:6px;}
</style>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title">
      <?php
      $this->app->loadLang('tree');
      echo isset($moduleID) ? $moduleName : $this->lang->tree->all;
      if(!empty($moduleID))
      {
          $removeLink = $browseType == 'bymodule' ? inlink('browse', "libID=$libID&browseType=$browseType&param=0&orderBy=$orderBy&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("libCaseModule")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class='btn-toolbar pull-left'>
    <?php
    common::sortFeatureMenu();
    if(!$config->testcase->needReview && empty($config->testcase->forceReview)) unset($lang->caselib->featureBar['browse']['wait']);
    foreach($lang->caselib->featureBar['browse'] as $featureType => $label)
    {
        $activeClass = $browseType == $featureType ? 'btn-active-text' : '';
        echo html::a(inlink('browse', "libID=$libID&browseType=$featureType"), "<span class='text'>$label</span>", '',"class='btn btn-link $activeClass' data-app={$app->tab} id=" . $featureType .'Tab');
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->testcase->bySearch;?></a>
  </div>
  <div class='btn-toolbar pull-right'>
    <?php
    if(common::hasPriv('caselib', 'view'))
    {
        $link = helper::createLink('caselib', 'view', "libID=$libID");
        echo html::a($link, "<i class='icon icon-list-alt muted'> </i> " . $this->lang->caselib->view, '', "class='btn btn-link'");
    }
    ?>
    <div class='btn-group'>
     <?php common::printLink('caselib', 'exportTemplate', "libID=$libID", "<i class='icon icon-export muted'> </i> " . $lang->caselib->exportTemplate, '', "class='btn btn-link export' data-width='40%'");?>
     <?php common::printLink('caselib', 'import', "libID=$libID", "<i class='icon muted icon-import'> </i> " . $lang->testcase->fileImport, '', "class='btn btn-link export'");?>
    </div>
    <?php echo html::a($this->createLink('caselib', 'create'), "<i class='icon icon-plus'> </i> " . $lang->caselib->create, '', 'class="btn btn-secondary"');?>
    <div class='btn-group dropdown'>
      <?php
      $params = "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0);
      $actionLink = $this->createLink('caselib', 'createCase', $params);
      echo html::a($actionLink, "<i class='icon icon-plus'></i> {$lang->testcase->create}", '', "class='btn btn-primary'");
      ?>
      <button type='button' class='btn btn-primary dropdown-toggle' data-toggle='dropdown'><span class='caret'></span></button>
      <ul class='dropdown-menu'>
        <li><?php echo html::a($actionLink, $lang->testcase->create);?></li>
        <li><?php echo html::a($this->createLink('caselib', 'batchCreateCase', $params), $lang->testcase->batchCreate);?></li>
      </ul>
    </div>
  </div>
</div>
<div id="mainContent" class="main-row fade">
  <div class="side-col" id="sidebar">
    <div class="sidebar-toggle"><i class="icon icon-angle-left"></i></div>
    <div class="cell">
      <?php if(!$moduleTree):?>
      <hr class="space">
      <div class="text-center text-muted">
        <?php echo $lang->caselib->noModule;?>
      </div>
      <hr class="space">
      <?php endif;?>
      <?php echo $moduleTree;?>
      <div class="text-center">
        <?php common::printLink('tree', 'browse', "libID=$libID&view=caselib&currentModuleID=0&branch=0&from={$this->lang->navGroup->caselib}", $lang->tree->manage, '', "class='btn btn-info btn-wide'");?>
        <hr class="space-sm" />
      </div>
    </div>
  </div>
  <div class="main-col">
    <div id='queryBox' data-module='caselib' class='cell <?php if($browseType =='bysearch') echo 'show';?>'></div>
    <?php if(empty($cases)):?>
    <div class="table-empty-tip">
      <p>
        <span class="text-muted"><?php echo $lang->testcase->noCase;?></span>
        <?php if(common::hasPriv('caselib', 'createCase')):?>
        <?php echo html::a($this->createLink('caselib', 'createCase', "libID=$libID&moduleID=" . (isset($moduleID) ? $moduleID : 0)), "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-info'");?>
        <?php endif;?>
      </p>
    </div>
    <?php else:?>
    <form class="main-table table-testcase" data-ride="table" method="post" id='testcaseForm'>
      <?php $canBatchEdit         = common::hasPriv('testcase', 'batchEdit');?>
      <?php $canBatchDelete       = common::hasPriv('testcase', 'batchDelete');?>
      <?php $canBatchReview       = common::hasPriv('testcase', 'batchReview') and ($config->testcase->needReview or !empty($config->testcase->forceReview));?>
      <?php $canBatchChangeModule = common::hasPriv('testcase', 'batchChangeModule');?>
      <?php $canBatchAction       = ($canBatchEdit or $canBatchDelete or $canBatchReview or $canBatchChangeModule);?>
      <?php $vars = "libID=$libID&browseType=$browseType&param=$param&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}";?>
      <div class="table-responsive">
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
              <th class='text-left'><?php common::printOrderLink('title',   $orderBy, $vars, $lang->testcase->title);?></th>
              <th class='c-pri' title=<?php echo $lang->pri;?>><?php common::printOrderLink('pri', $orderBy, $vars, $lang->priAB);?></th>
              <th class='c-type'>  <?php common::printOrderLink('type',     $orderBy, $vars, $lang->typeAB);?></th>
              <th class='c-status'><?php common::printOrderLink('status',   $orderBy, $vars, $lang->statusAB);?></th>
              <th class='c-user'>  <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
              <?php
              $extendFields = $this->caselib->getFlowExtendFields();
              foreach($extendFields as $extendField) echo "<th>{$extendField->name}</th>";
              ?>
              <th class='c-actions-3 text-center'><?php echo $lang->actions;?></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($cases as $case):?>
            <tr>
              <td class='c-id'>
                <?php if($canBatchAction):?>
                <?php echo html::checkbox('caseIDList', array($case->id => '')) . html::a($this->createLink('testcase', 'view', "caseID=$case->id&version=$case->version"), sprintf('%03d', $case->id));?>
                <?php else:?>
                <?php echo sprintf('%03d', $case->id);?>
                <?php endif;?>
              </td>
              <td class='text-left' title="<?php echo $case->title?>">
                <?php if($modulePairs and $case->module) echo "<span title='{$lang->testcase->module}' class='label label-info label-badge'>{$modulePairs[$case->module]}</span> ";?>
                <?php $viewLink = $this->createLink('testcase', 'view', "caseID=$case->id&version=$case->version");?>
                <?php echo html::a($viewLink, $case->title, null, "style='color: $case->color'");?>
              </td>
              <td><span class='label-pri label-pri-<?php echo $case->pri;?>' title='<?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?></span></td>
              <td><?php echo $lang->testcase->typeList[$case->type];?></td>
              <td class='<?php if(isset($run)) echo $run->status;?> testcase-<?php echo $case->status?>'> <?php echo $this->processStatus('testcase', $case);?></td>
              <td title="<?php echo zget($users, $case->openedBy);?>"><?php echo zget($users, $case->openedBy);?></td>
              <?php foreach($extendFields as $extendField) echo "<td>" . $this->loadModel('flow')->getFieldValue($extendField, $case) . "</td>";?>
              <td class='c-actions'>
                <?php echo $this->caselib->buildOperateMenu($case, 'browse');?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <div class='table-footer'>
        <?php if($canBatchAction):?>
        <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
        <?php endif;?>
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
                      echo '<li>' . html::a('#', $result, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"") . '</li>';
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
                      echo "<li class='option' data-key='$moduleID'>" . html::a('#', $module, '', "onclick=\"setFormAction('$actionLink', 'hiddenwin')\"") . "</li>";
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
        <div class='table-statistic'></div>
        <?php $pager->show('right', 'pagerjs');?>
      </div>
    </form>
    <?php endif;?>
  </div>
</div>
<script>
$('#module' + moduleID).addClass('active');
$('#<?php echo $this->session->libBrowseType?>Tab').addClass('btn-active-text').append(" <span class='label label-light label-badge'><?php echo $pager->recTotal;?></span>");
</script>
<?php include '../../common/view/footer.html.php';?>
