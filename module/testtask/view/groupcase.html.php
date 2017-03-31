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
<table class='table table-fixed' id='treetable'>
  <thead>
    <tr>
      <th class='w-50px'></th>
      <th><?php echo $lang->testcase->title;?></th>
      <th class='w-pri'>  <?php echo $lang->priAB;?></th>
      <th class='w-80px'> <?php echo $lang->typeAB;?></th>
      <th class='w-user'> <?php echo $lang->testtask->assignedTo;?></th>
      <th class='w-80px'> <?php echo $lang->testtask->lastRunAccount;?></th>
      <th class='w-120px'><?php echo $lang->testtask->lastRunTime;?></th>
      <th class='w-80px'> <?php echo $lang->testtask->lastRunResult;?></th>
      <th class='w-80px'> <?php echo $lang->testtask->status;?></th>
      <th class='w-30px' title='<?php echo $lang->testcase->bugs?>'><?php echo $lang->testcase->bugsAB;?></th>
      <th class='w-30px' title='<?php echo $lang->testcase->results?>'><?php echo $lang->testcase->resultsAB;?></th>
      <th class='w-30px' title='<?php echo $lang->testcase->stepNumber?>'><?php echo $lang->testcase->stepNumberAB;?></th>
      <th class='w-60px'> <?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <?php $i = 0;?>
  <?php foreach($cases as $groupKey => $groupCases):?>
  <?php $groupClass = ($i % 2 == 0) ? 'even' : 'highlight-warning'; $i ++;?>
  <tr id='node-<?php echo $groupKey;?>' class='actie-disabled group-title'>
    <td class='text-right <?php echo $groupClass;?> text-left large strong group-name'><?php echo $groupKey;?></td>
    <td colspan='12' class='text-left'><?php if($groupByList) echo $groupByList[$groupKey];?></td>
  </tr>
  <?php foreach($groupCases as $run):?>
  <tr id='<?php echo $run->id;?>' class='a-center child-of-node-<?php echo $groupKey;?>'>
    <td class='<?php echo $groupClass;?>'></td>
    <td class='text-left'>&nbsp;<?php echo $run->case . $lang->colon; if(!common::printLink('testcase', 'view', "case=$run->case", $run->title)) echo $run->title;?></td>
    <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $run->pri, $run->pri)?>'><?php echo zget($lang->testcase->priList, $run->pri, $run->pri);?></span></td>
    <td><?php echo $lang->testcase->typeList[$run->type];?></td>
    <td><?php echo $users[$run->assignedTo];?></td>
    <td><?php echo $users[$run->lastRunner];?></td>
    <td><?php if(!helper::isZeroDate($run->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($run->lastRunDate));?></td>
    <td class='<?php echo $run->lastRunResult;?>'><?php if($run->lastRunResult) echo $lang->testcase->resultList[$run->lastRunResult];?></td>
    <td class='<?php echo $run->status;?>'><?php echo ($run->version < $run->caseVersion) ? "<span class='warning'>{$lang->testcase->changed}</span>" : $lang->testtask->statusList[$run->status];?></td>
    <td><?php echo (common::hasPriv('testcase', 'bugs') and $run->bugs) ? html::a($this->createLink('testcase', 'bugs', "runID={$run->id}&caseID={$run->case}"), $run->bugs, '', "class='iframe'") : $run->bugs;?></td>
    <td><?php echo (common::hasPriv('testtask', 'results') and $run->results) ? html::a($this->createLink('testtask', 'results', "runID={$run->id}&caseID={$run->case}"), $run->results, '', "class='iframe'") : $run->results;?></td>
    <td><?php echo $run->stepNumber;?></td>
    <td>
      <?php common::printIcon('testcase', 'edit', "caseID=$run->case", '', 'list');?>
      <?php common::printIcon('testcase', 'delete', "caseID=$run->case", '', 'list', '', 'hiddenwin');?>
    </td>
  </tr>
  <?php endforeach;?>
  <?php endforeach;?>
</table>
<?php include '../../common/view/footer.html.php';?>
