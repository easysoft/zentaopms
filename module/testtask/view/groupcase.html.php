<?php
/**
 * The case group view file of testcase module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
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
      <th class='w-60px'> <?php echo $lang->actions;?></th>
    </tr>
  </thead>
  <?php $i = 0;?>
  <?php foreach($cases as $groupKey => $groupTasks):?>
  <?php $groupClass = ($i % 2 == 0) ? 'even' : 'highlight-warning'; $i ++;?>
  <tr id='node-<?php echo $groupKey;?>' class='actie-disabled group-title'>
    <td class='text-right <?php echo $groupClass;?> text-left large strong group-name'><?php echo $groupKey;?></td>
    <td colspan='9' class='text-left'><?php if($groupByList) echo $groupByList[$groupKey];?></td>
  </tr>
  <?php foreach($groupTasks as $case):?>
  <?php $caseLink = $this->createLink('case','view',"caseID=$case->id"); ?>
  <tr id='<?php echo $case->id;?>' class='a-center child-of-node-<?php echo $groupKey;?>'>
    <td class='<?php echo $groupClass;?>'></td>
    <td class='text-left'>&nbsp;<?php echo $case->id . $lang->colon; if(!common::printLink('testcase', 'view', "case=$case->id", $case->title)) echo $case->title;?></td>
    <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?></span></td>
    <td><?php echo $lang->case->typeList[$case->type];?></td>
    <td><?php echo $users[$case->assignedTo];?></td>
    <td><?php echo $users[$case->lastRunner];?></td>
    <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
    <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
    <td class='<?php echo $case->status;?>'><?php echo ($case->version < $case->caseVersion) ? "<span class='warning'>{$lang->testcase->changed}</span>" : $lang->testtask->statusList[$case->status];?></td>
    <td>
      <?php common::printIcon('testcase', 'edit', "caseID=$case->id", '', 'list');?>
      <?php common::printIcon('testcase', 'delete', "caseID=$case->id", '', 'list', '', 'hiddenwin');?>
    </td>
  </tr>
  <?php endforeach;?>
  <?php endforeach;?>
</table>
<?php include '../../common/view/footer.html.php';?>
