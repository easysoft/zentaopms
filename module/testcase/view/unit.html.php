<?php
/**
 * The unit view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php
include '../../common/view/header.html.php';
include '../../common/view/treetable.html.php';
js::set('flow', $config->global->flow);
if(!isset($branch)) $branch = 0;
?>
<?php if($config->global->flow == 'full'):?>
<div id='mainMenu' class='clearfix'>
  <div id="sidebarHeader">
    <div class="title">
      <?php
      echo !empty($moduleID) ? $moduleName : $this->lang->tree->all;
      if(!empty($moduleID))
      {
          $removeLink = $browseType == 'bymodule' ? inlink('unit', "productID=$productID&branch=$branch&browseType=$browseType&param=0&orderBy=$orderBy&recTotal=0&recPerPage={$pager->recPerPage}") : 'javascript:removeCookieByKey("caseModule")';
          echo html::a($removeLink, "<i class='icon icon-sm icon-close'></i>", '', "class='text-muted'");
      }
      ?>
    </div>
  </div>
  <div class='btn-toolbar pull-left'></div>
  <div class='btn-toolbar pull-right'>
    <div class='btn-group'>
      <button type='button' class='btn btn-link dropdown-toggle' data-toggle='dropdown'>
        <i class='icon icon-export muted'></i> <?php echo $lang->export ?>
        <span class='caret'></span>
      </button>
      <ul class='dropdown-menu' id='exportActionMenu'>
      <?php
      $class = common::hasPriv('testcase', 'export') ? '' : "class=disabled";
      $misc  = common::hasPriv('testcase', 'export') ? "class='export'" : "class=disabled";
      $link  = common::hasPriv('testcase', 'export') ?  $this->createLink('testcase', 'export', "productID=$productID&orderBy=suite&taskID=0&browseType=") : '#';
      echo "<li $class>" . html::a($link, $lang->testcase->export, '', $misc) . "</li>";
      ?>
      </ul>
    </div>
    <?php $initModule = isset($moduleID) ? (int)$moduleID : 0;?>
    <?php if(!common::checkNotCN()):?>
    <?php
    if(common::hasPriv('testcase', 'batchCreate'))
    {
        $link = $this->createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule");
        echo html::a($link, "<i class='icon-plus'></i> " . $lang->testcase->batchCreate, '', "class='btn btn-secondary'");
    }

    if(common::hasPriv('testcase', 'create'))
    {
        $link = $this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule");
        echo html::a($link, "<i class='icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-primary'");
    }
    ?>
    <?php else:?>
    <div class='btn-group dropdown-hover'>
      <?php
      $link = common::hasPriv('testcase', 'create') ? $this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule") : '###';
      $disabled = common::hasPriv('testcase', 'create') ? '' : "disabled";
      echo html::a($link, "<i class='icon icon-plus'></i> {$lang->testcase->create} </span><span class='caret'>", '', "class='btn btn-primary $disabled'");
      ?>
      <ul class='dropdown-menu'>
        <?php $disabled = common::hasPriv('testcase', 'batchCreate') ? '' : "class='disabled'";?>
        <li <?php echo $disabled?>>
        <?php
        $batchLink = $this->createLink('testcase', 'batchCreate', "productID=$productID&branch=$branch&moduleID=$initModule");
        echo "<li>" . html::a($batchLink, "<i class='icon icon-plus'></i>" . $lang->testcase->batchCreate) . "</li>";
        ?>
        </li>
      </ul>
    </div>
    <?php endif;?>
  </div>
</div>
<?php endif;?>
<div class="main-table" data-ride="table" data-checkable="false" data-group="true">
  <?php if(empty($groupCases)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testcase->noCase;?></span>
      <?php if(common::hasPriv('testcase', 'create')):?>
      <?php echo html::a($this->createLink('testcase', 'create', "productID=$productID&branch=$branch&moduleID=$initModule"), "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-info'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <table class="table table-grouped text-center">
    <thead>
      <tr class="divider">
        <th class="c-side text-left has-btn group-menu">
          <div class="table-group-btns">
            <button type="button" class="btn btn-block btn-link group-collapse-all"><?php echo $lang->project->treeLevel['root'];?> <i class="icon-caret-down"></i></button>
            <button type="button" class="btn btn-block btn-link group-expand-all"><?php echo $lang->project->treeLevel['all'];?> <i class="icon-caret-up"></i></button>
          </div>
        </th>
        <th class='c-id-sm'><?php echo $lang->idAB;?></th>
        <th class='w-pri'>  <?php echo $lang->priAB;?></th>
        <th><?php echo $lang->testcase->title;?></th>
        <th class='w-80px'> <?php echo $lang->typeAB;?></th>
        <th class='w-80px'> <?php echo $lang->testtask->lastRunAccount;?></th>
        <th class='w-120px'><?php echo $lang->testtask->lastRunTime;?></th>
        <th class='w-80px'> <?php echo $lang->testtask->lastRunResult;?></th>
        <th class='w-80px'> <?php echo $lang->testcase->status;?></th>
        <th class='w-30px' title='<?php echo $lang->testcase->bugs?>'><?php echo $lang->testcase->bugsAB;?></th>
        <th class='w-30px' title='<?php echo $lang->testcase->results?>'><?php echo $lang->testcase->resultsAB;?></th>
        <th class='w-30px' title='<?php echo $lang->testcase->stepNumber?>'><?php echo $lang->testcase->stepNumberAB;?></th>
        <th class='c-actions-3'> <?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($groupCases as $suiteID => $cases):?>
      <?php
      $i = 0;
      $suite = zget($suites, $suiteID, '');
      $groupName = $suite ? $suite->name : '';
      ?>
      <?php foreach($cases as $case):?>
      <tr data-id='<?php echo $suiteID;?>' <?php if($i == 0) echo "class='divider-top'";?>>
        <?php if($i == 0):?>
        <td rowspan='<?php echo count($cases);?>' class='c-side text-left group-toggle text-top'>
          <div class='group-header'><?php echo html::a('###', "<i class='icon-caret-down'></i> $groupName", '', "class='text-primary'");?></div>
        </td>
        <?php endif;?>
        <td class='c-id-sm'><?php echo sprintf('%03d', $case->id);?></td>
        <td><span class='label-pri <?php echo 'label-pri-' . $case->pri;?>' title='<?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?></span></td>
        <td class='text-left title' title='<?php echo $case->title?>'><?php if(!common::printLink('testcase', 'view', "case=$case->id", $case->title)) echo $case->title;?></td>
        <td><?php echo zget($lang->case->typeList, $case->type, '');?></td>
        <td><?php echo zget($users, $case->lastRunner);?></td>
        <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
        <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
        <td class='<?php if(isset($run)) echo $run->status;?>'>
          <?php
          if($case->needconfirm)
          {
              echo "(<span class='warning'>{$lang->story->changed}</span> ";
              echo html::a(helper::createLink('testcase', 'confirmStoryChange', "caseID=$case->id"), $lang->confirm, 'hiddenwin');
              echo ")";
          }
          else
          {
              echo "<span class='status-case status-{$case->status}'>";
              echo $this->processStatus('testcase', $case);
              echo '</span>';
          }
          ?>
        </td>
        <td><?php echo (common::hasPriv('testcase', 'bugs') and $case->bugs) ? html::a(inlink('bugs', "runID=0&caseID={$case->id}"), $case->bugs, '', "class='iframe'") : $case->bugs;?></td>
        <td><?php echo (common::hasPriv('testtask', 'results') and $case->results) ? html::a($this->createLink('testtask', 'results', "runID=0&caseID={$case->id}"), $case->results, '', "class='iframe'") : $case->results;?></td>
        <td><?php echo $case->stepNumber;?></td>
        <td class='c-actions'>
          <?php common::printIcon('testtask', 'results', "runID=0&caseID=$case->id", $case, 'list', '', '', 'iframe', true, "data-width='95%'");?>
          <?php common::printIcon('testcase', 'edit', "caseID=$case->id", '', 'list');?>
          <?php common::printIcon('testcase', 'delete', "caseID=$case->id", '', 'list', '', 'hiddenwin');?>
        </td>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
      <tr data-id='<?php echo $suiteID;?>' class='group-toggle group-summary hidden divider-top'>
        <td class='c-side text-left'><?php echo html::a('###', "<i class='icon-caret-right'></i> $groupName");?></td>
        <td colspan='12' class="text-left">
          <div class="small with-padding"><span class="text-muted"><?php echo $lang->testcase->allTestcases;?></span> <?php echo $i;?></div>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
