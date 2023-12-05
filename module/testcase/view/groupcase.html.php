<?php
/**
 * The case group view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
<div class="main-table" data-ride="table" data-checkable="false" data-group="true">
  <?php if(empty($cases)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testcase->noCase;?></span>
      <?php if(common::canModify('product', $product) and common::hasPriv('testcase', 'create')):?>
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
            <button type="button" class="btn btn-block btn-link group-collapse-all"><?php echo $lang->execution->treeLevel['root'];?> <i class="icon-caret-down"></i></button>
            <button type="button" class="btn btn-block btn-link group-expand-all"><?php echo $lang->execution->treeLevel['all'];?> <i class="icon-caret-up"></i></button>
          </div>
        </th>
        <th class='c-id-sm'><?php echo $lang->idAB;?></th>
        <th class='c-pri' title=<?php echo $lang->testcase->pri;?>>  <?php echo $lang->priAB;?></th>
        <th class='c-title text-left'><?php echo $lang->testcase->title;?></th>
        <th class='c-type'> <?php echo $lang->typeAB;?></th>
        <th class='c-user'> <?php echo $lang->testtask->lastRunAccount;?></th>
        <th class='c-date'><?php echo $lang->testtask->lastRunTime;?></th>
        <th class='c-result'> <?php echo $lang->testtask->lastRunResult;?></th>
        <th class='c-status'> <?php echo $lang->testcase->status;?></th>
        <th class='c-bugs' title='<?php echo $lang->testcase->bugs?>'><?php echo $lang->testcase->bugsAB;?></th>
        <th class='c-results' title='<?php echo $lang->testcase->results?>'><?php echo $lang->testcase->resultsAB;?></th>
        <th class='c-steps' title='<?php echo $lang->testcase->stepNumber?>'><?php echo $lang->testcase->stepNumberAB;?></th>
        <th class='c-actions-2'> <?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php $groupIndex = 0;?>
      <?php foreach($cases as $groupKey => $groupTasks):?>
      <?php
      $i = 0;
      $groupName = $groupByList ? $groupByList[$groupKey] : '';
      if(empty($groupName) and $groupBy == 'story') $groupName = $lang->task->noStory;
      if(empty($groupName) and $groupBy == 'assignedTo') $groupName = $lang->task->noAssigned;
      ?>
      <?php foreach($groupTasks as $case):?>
      <tr data-id='<?php echo $groupIndex;?>' <?php if($i == 0) echo "class='divider-top'";?>>
        <?php if($i == 0):?>
        <td rowspan='<?php echo count($groupTasks);?>' class='c-side text-left group-toggle text-top'>
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
          <?php common::printIcon('testcase', 'edit', "caseID=$case->id", $case, 'list');?>
          <?php common::printIcon('testcase', 'delete', "caseID=$case->id", $case, 'list', 'trash', 'hiddenwin');?>
        </td>
      </tr>
      <?php $i++;?>
      <?php endforeach;?>
      <tr data-id='<?php echo $groupIndex;?>' class='group-toggle group-summary hidden divider-top'>
        <td class='c-side text-left'><?php echo html::a('###', "<i class='icon-caret-right'></i> $groupName");?></td>
        <td colspan='12' class="text-left">
          <div class="small with-padding"><span class="text-muted"><?php echo $lang->testcase->allTestcases;?></span> <?php echo $i;?></div>
        </td>
      </tr>
      <?php $groupIndex++;?>
      <?php endforeach;?>
    </tbody>
  </table>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
