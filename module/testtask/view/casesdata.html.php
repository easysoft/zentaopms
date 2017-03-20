<?php
/**
 * The cases data view file of testtask module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Yidong Wang <yidong@cnezsoft.com>
 * @package     testtask
 * @version     $Id$
 * @link        http://www.zentao.net
 */
?>
    <?php include '../../common/view/tablesorter.html.php';?>
    <?php $vars = "taskID=$task->id&browseType=$browseType&param=$param&orderBy=%s&recToal={$pager->recTotal}&recPerPage={$pager->recPerPage}"; ?>
    <table class='table table-condensed table-hover table-striped tablesorter table-fixed table-selectable' id='caseList'>
      <thead>
        <tr class='colhead'>
          <th class='w-id {sorter: false}'><nobr><?php common::printOrderLink('id',            $orderBy, $vars, $lang->idAB);?></nobr></th>
          <th class='w-pri {sorter: false}'>     <?php common::printOrderLink('pri',           $orderBy, $vars, $lang->priAB);?></th>
          <th class='{sorter: false}'>           <?php common::printOrderLink('title',         $orderBy, $vars, $lang->testcase->title);?></th>
          <th class='w-type {sorter: false}'>    <?php common::printOrderLink('type',          $orderBy, $vars, $lang->testcase->type);?></th>
          <th class='w-user {sorter: false}'>    <?php common::printOrderLink('assignedTo',    $orderBy, $vars, $lang->testtask->assignedTo);?></th>
          <th class='w-user {sorter: false}'>    <?php common::printOrderLink('lastRunner',    $orderBy, $vars, $lang->testtask->lastRunAccount);?></th>
          <th class='w-100px {sorter: false}'>   <?php common::printOrderLink('lastRunDate',   $orderBy, $vars, $lang->testtask->lastRunTime);?></th>
          <th class='w-80px {sorter: false}'>    <?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testtask->lastRunResult);?></th>
          <th class='w-status {sorter: false}'>  <?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
          <th class='w-30px' title='<?php echo $lang->testcase->bugs?>'> <?php echo $lang->testcase->bugsAB;?></th>
          <th class='w-30px' title='<?php echo $lang->testcase->results?>'> <?php echo $lang->testcase->resultsAB;?></th>
          <th class='w-30px' title='<?php echo $lang->testcase->stepNumber?>'> <?php echo $lang->testcase->stepNumberAB;?></th>
          <th class='w-120px {sorter: false}'><?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <?php
      $canBatchEdit   = common::hasPriv('testcase', 'batchEdit');
      $canBatchAssign = common::hasPriv('testtask', 'batchAssign');
      $canBatchRun    = common::hasPriv('testtask', 'batchRun');
      ?>
      <?php if($runs):?>
      <tbody>
        <?php foreach($runs as $run):?>
        <tr class='text-center'>
          <td class='cell-id'>
            <?php if($canBatchEdit or $canBatchAssign or $canBatchRun):?>
            <input type='checkbox' name='caseIDList[]' value='<?php echo $run->case;?>'/> 
            <?php endif;?>
            <?php printf('%03d', $run->case);?>
          </td>
          <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $run->pri, $run->pri)?>'><?php echo zget($lang->testcase->priList, $run->pri, $run->pri)?></span></td>
          <td class='text-left nobr'>
            <?php if($run->branch) echo "<span class='label label-info label-badge'>{$branches[$run->branch]}</span>"?>
            <?php echo html::a($this->createLink('testcase', 'view', "caseID=$run->case&version=$run->version&from=testtask&taskID=$run->task"), $run->title);?>
          </td>
          <td><?php echo $lang->testcase->typeList[$run->type];?></td>
          <td><?php $assignedTo = $users[$run->assignedTo]; echo substr($assignedTo, strpos($assignedTo, ':') + 1);?></td>
          <td><?php $lastRunner = $users[$run->lastRunner]; echo substr($lastRunner, strpos($lastRunner, ':') + 1);?></td>
          <td><?php if(!helper::isZeroDate($run->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($run->lastRunDate));?></td>
          <td class='<?php echo $run->lastRunResult;?>'><?php if($run->lastRunResult) echo $lang->testcase->resultList[$run->lastRunResult];?></td>
          <td class='<?php echo $run->status;?>'><?php echo ($run->version < $run->caseVersion) ? "<span class='warning'>{$lang->testcase->changed}</span>" : $lang->testtask->statusList[$run->status];?></td>
          <td><?php echo (common::hasPriv('testcase', 'bugs') and $run->bugs) ? html::a($this->createLink('testcase', 'bugs', "runID={$run->id}&caseID={$run->case}"), $run->bugs, '', "class='iframe'") : $run->bugs;?></td>
          <td><?php echo (common::hasPriv('testtask', 'results') and $run->results) ? html::a($this->createLink('testtask', 'results', "runID={$run->id}&caseID={$run->case}"), $run->results, '', "class='iframe'") : $run->results;?></td>
          <td><?php echo $run->stepNumber;?></td>
          <td class='text-center'>
            <?php
            common::printIcon('testtask', 'runCase',    "id=$run->id", '', 'list', '', '', 'runCase iframe', false, "data-width='95%'");
            common::printIcon('testtask', 'results',    "id=$run->id", '', 'list', '', '', 'iframe', '', "data-width='90%'");

            if(common::hasPriv('testtask', 'unlinkCase'))
            {
                $unlinkURL = $this->createLink('testtask', 'unlinkCase', "caseID=$run->id&confirm=yes");
                echo html::a("javascript:ajaxDelete(\"$unlinkURL\",\"caseList\",confirmUnlink)", '<i class="icon-unlink"></i>', '', "title='{$lang->testtask->unlinkCase}' class='btn-icon'");
            }

            common::printIcon('testcase', 'createBug', "product=$productID&branch=$task->branch&extra=projectID=$task->project,buildID=$task->build,caseID=$run->case,version=$run->version,runID=$run->id,testtask=$taskID", $run, 'list', 'bug', '', 'iframe', '', "data-width='90%'");
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <?php endif;?>
