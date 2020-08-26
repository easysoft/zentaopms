<?php
/**
 * The case group view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testcase
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/treetable.html.php';?>
<?php include './caseheader.html.php';?>
<?php js::set('browseType', $browseType);?>
<?php js::set('confirmUnlink', $lang->testtask->confirmUnlinkCase)?>
<div id='casesForm' class="main-table" data-ride="table" data-checkable="false" data-group="true" data-replace-id="treetable">
  <table class='table table-grouped text-center' id='treetable'>
    <thead>
      <tr class="divider">
        <th class="c-side text-left has-btn group-menu">
          <div class="table-group-btns">
            <button type="button" class="btn btn-block btn-link group-collapse-all"><?php echo $lang->project->treeLevel['root'];?> <i class="icon-caret-down"></i></button>
            <button type="button" class="btn btn-block btn-link group-expand-all"><?php echo $lang->project->treeLevel['all'];?> <i class="icon-caret-up"></i></button>
          </div>
        </th>
        <th class='c-id-sm'><?php echo $lang->idAB;?></th>
        <th class='w-80px'>  <?php echo $lang->priAB;?></th>
        <th><?php echo $lang->testcase->title;?></th>
        <th class='w-80px'> <?php echo $lang->typeAB;?></th>
        <th class='w-user'> <?php echo $lang->testtask->assignedTo;?></th>
        <th class='w-80px'> <?php echo $lang->testtask->lastRunAccount;?></th>
        <th class='w-120px'><?php echo $lang->testtask->lastRunTime;?></th>
        <th class='w-80px'> <?php echo $lang->testtask->lastRunResult;?></th>
        <th class='w-80px'> <?php echo $lang->testtask->status;?></th>
        <th class='w-30px' title='<?php echo $lang->testcase->bugs?>'><?php echo $lang->testcase->bugsAB;?></th>
        <th class='w-30px' title='<?php echo $lang->testcase->results?>'><?php echo $lang->testcase->resultsAB;?></th>
        <th class='w-30px' title='<?php echo $lang->testcase->stepNumber?>'><?php echo $lang->testcase->stepNumberAB;?></th>
        <th class='c-actions-3'> <?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $groupIndex = 0;?>
      <?php foreach($cases as $groupKey => $groupCases):?>
      <?php
      $i = 0;
      $groupName = $groupByList ? $groupByList[$groupKey] : '';
      if(empty($groupName) and $groupBy == 'story') $groupName = $lang->task->noStory;
      if(empty($groupName) and $groupBy == 'assignedTo') $groupName = $groupKey ? zget($users, $groupKey, $lang->task->noAssigned) : $lang->task->noAssigned;
      ?>
      <?php foreach($groupCases as $run):?>
      <tr data-id='<?php echo $groupIndex;?>' <?php if($i == 0) echo "class='divider-top'";?>>
        <?php if($i == 0):?>
        <td rowspan='<?php echo count($groupCases);?>' class='c-side text-left group-toggle text-top'>
          <div class='group-header'><?php echo html::a('###', "<i class='icon-caret-down'></i> $groupName", '', "class='text-primary'");?></div>
        </td>
        <?php endif;?>
        <?php
        if(!isset($run->case))
        {
            echo "<td colspan='13'></td></tr>";
            break;
        }
        ?>
        <td class='c-id-sm'><?php echo sprintf('%03d', $run->case);?></td>
        <td><span class='label-pri <?php echo 'label-pri-' . $run->pri;?>' title='<?php echo zget($lang->testcase->priList, $run->pri, $run->pri);?>'><?php echo zget($lang->testcase->priList, $run->pri, $run->pri);?></span></td>
        <td class='text-left case-title' title='<?php echo $run->title?>'><?php if(!common::printLink('testcase', 'view', "case=$run->case", $run->title)) echo $run->title;?></td>
        <td><?php echo zget($lang->testcase->typeList, $run->type, '');?></td>
        <td><?php echo zget($users, $run->assignedTo);?></td>
        <td><?php echo zget($users, $run->lastRunner);?></td>
        <td><?php if(!helper::isZeroDate($run->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($run->lastRunDate));?></td>
        <td class='<?php echo $run->lastRunResult;?>'><?php if($run->lastRunResult) echo $lang->testcase->resultList[$run->lastRunResult];?></td>
        <td class='<?php echo $run->status;?>'><?php echo ($run->version < $run->caseVersion) ? "<span class='warning'>{$lang->testcase->changed}</span>" : $this->processStatus('testtask', $run);?></td>
        <td><?php echo (common::hasPriv('testcase', 'bugs') and $run->bugs) ? html::a($this->createLink('testcase', 'bugs', "runID={$run->id}&caseID={$run->case}"), $run->bugs, '', "class='iframe'") : $run->bugs;?></td>
        <td><?php echo (common::hasPriv('testtask', 'results') and $run->results) ? html::a($this->createLink('testtask', 'results', "runID={$run->id}&caseID={$run->case}"), $run->results, '', "class='iframe'") : $run->results;?></td>
        <td><?php echo $run->stepNumber;?></td>
        <td class='c-actions'>
          <?php common::printIcon('testtask', 'runCase', "runID=$run->id&caseID=$run->case&version=$run->caseVersion", '', 'list', 'play', '', 'runCase iframe', false, "data-width='95%'");?>
          <?php common::printIcon('testcase', 'edit', "caseID=$run->case", '', 'list');?>
          <?php
          if(common::hasPriv('testtask', 'unlinkCase', $run))
          {
              $unlinkURL = helper::createLink('testtask', 'unlinkCase', "caseID=$run->id&confirm=yes");
              echo html::a("javascript:ajaxDelete(\"$unlinkURL\", \"casesForm\", confirmUnlink)", '<i class="icon-unlink"></i>', '', "title='{$this->lang->testtask->unlinkCase}' class='btn'");
          }
          ?>
        </td>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
      <tr data-id='<?php echo $groupIndex;?>' class='group-toggle group-summary hidden divider-top'>
        <td class='c-side text-left'><?php echo html::a('###', "<i class='icon-caret-right'></i> $groupName");?></td>
        <td colspan='13'></td>
      </tr>
      <?php $groupIndex++;?>
      <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
