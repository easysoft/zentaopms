<?php
/**
 * The test view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: test.html.php 1191 2010-11-13 07:30:35Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include './featurebar.html.php';?>
<div class='sub-featurebar'>
  <ul class='nav'>
    <?php
    echo "<li id='testtaskTab'>"  . html::a($this->createLink('user', 'testtask', "account=$account"),  $lang->user->testTask2Him) . "</li>";
    echo "<li id='case2HimTab'>"  . html::a($this->createLink('user', 'testcase', "account=$account&type=case2Him"),  $lang->user->case2Him) . "</li>";
    echo "<li id='caseByHimTab'>" . html::a($this->createLink('user', 'testcase', "account=$account&type=caseByHim"),  $lang->user->caseByHim) . "</li>";
    ?>
  </ul>
</div>

<table class='table tablesorter table-fixed'>
  <?php 
    $vars = "account=$account&type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
    $this->app->loadLang('testtask');
  ?>
  <thead>
    <tr class='colhead'>
      <th class='w-id'>    <?php common::printOrderLink('id',       $orderBy, $vars, $lang->idAB);?></th>
      <th class='w-pri'>   <?php common::printOrderLink('pri',      $orderBy, $vars, $lang->priAB);?></th>
      <th>                 <?php common::printOrderLink('title',    $orderBy, $vars, $lang->testcase->title);?></th>
      <th class='w-type'>  <?php common::printOrderLink('type',     $orderBy, $vars, $lang->typeAB);?></th>
      <th class='w-user'>  <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
      <th class='w-80px'>  <?php common::printOrderLink('lastRunner',    $orderBy, $vars, $lang->testtask->lastRunAccount);?></th>
      <th class='w-120px'> <?php common::printOrderLink('lastRunDate',   $orderBy, $vars, $lang->testtask->lastRunTime);?></th>
      <th class='w-80px'>  <?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testtask->lastRunResult);?></th>
      <th class='w-status'><?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
    </tr>
  </thead>
  <tbody>
    <?php foreach($cases as $case):?>
    <?php $caseID = $type == 'case2Him' ? $case->case : $case->id?>
    <tr class='text-center'>
      <td><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$caseID&version=$case->version"), sprintf('%03d', $caseID));?></td>
      <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span</td>
      <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$caseID&version=$case->version"), $case->title);?></td>
      <td><?php echo $lang->testcase->typeList[$case->type];?></td>
      <td><?php echo $users[$case->openedBy];?></td>
      <td><?php echo $users[$case->lastRunner];?></td>
      <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
      <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
      <td class='<?php echo $case->status;?>'><?php echo $lang->testcase->statusList[$case->status];?></td>
    </tr>
    <?php endforeach;?>
  </tbody> 
  <tfoot><tr><td colspan='9'><?php $pager->show();?></td></tr></tfoot>
</table>
<script language="Javascript">$("#testTab").addClass('active'); $("#<?php echo $type;?>Tab").addClass('active'); </script>
<?php include '../../common/view/footer.html.php';?>
