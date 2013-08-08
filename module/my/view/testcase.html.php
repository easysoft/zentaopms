<?php
/**
 * The test view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2013 青岛易软天创网络科技有限公司 (QingDao Nature Easy Soft Network Technology Co,LTD www.cnezsoft.com)
 * @license     LGPL (http://www.gnu.org/licenses/lgpl.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     dashboard
 * @version     $Id: test.html.php 1191 2010-11-13 07:30:35Z jajacn@126.com $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/colorize.html.php';?>
<?php js::set('confirmDelete', $lang->testcase->confirmDelete)?>
<div id='featurebar'>
  <div class='f-left'>
    <?php
    echo "<span id='testtask'>"      . html::a($this->createLink('my', 'testtask'),  $lang->my->testTask) . "</span>";
    echo "<span id='assigntomeTab'>" . html::a($this->createLink('my', 'testcase', "type=assigntome"),  $lang->testcase->assignToMe) . "</span>";
    //echo "<span id='donebymeTab'>"   . html::a($this->createLink('my', 'testcase', "type=donebyme"),    $lang->testcase->doneByMe)   . "</span>";
    echo "<span id='openedbymeTab'>" . html::a($this->createLink('my', 'testcase', "type=openedbyme"),  $lang->testcase->openedByMe) . "</span>";
    ?>
  </div>
</div>

<form method='post' id='myCaseForm'>
  <table class='table-1 fixed tablesorter colored' id='caseList'>
    <?php 
      $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
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
        <th class='w-140px'><?php echo $lang->actions;?></th>
      </tr>
    </thead>
    <tbody>
      <?php
      $canBatchEdit = common::hasPriv('testcase', 'batchEdit');
      $canBatchRun  = common::hasPriv('testtask', 'batchRun');
      ?>
      <?php foreach($cases as $case):?>
      <tr class='a-center'>
        <td>
          <?php if($canBatchEdit or $canBatchRun):?><input type='checkbox' name='caseIDList[]'  value='<?php echo $case->id;?>'/><?php endif;?>
          <?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), sprintf('%03d', $case->id));?>
        </td>
        <td><span class='<?php echo 'pri' . $case->pri?>'><?php echo $case->pri?></span</td>
        <td class='a-left'><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), $case->title);?></td>
        <td><?php echo $lang->testcase->typeList[$case->type];?></td>
        <td><?php echo $users[$case->openedBy];?></td>
        <td><?php echo $users[$case->lastRunner];?></td>
        <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
        <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
        <td class='<?php if(isset($run)) echo $run->status;?>'><?php echo $lang->testcase->statusList[$case->status];?></td>
        <td>
        <?php
        common::printIcon('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->version", '', 'list', '', '', 'iframe');
        common::printIcon('testtask', 'results', "runID=0&caseID=$case->id", '', 'list', '', '', 'iframe');
        common::printIcon('testcase', 'edit',    "caseID=$case->id", $case, 'list');
        common::printIcon('testcase', 'create',  "productID=$case->product&moduleID=$case->module&from=testcase&param=$case->id", $case, 'list', 'copy');

        $deleteURL = $this->createLink('testcase', 'delete', "caseID=$case->id&confirm=yes");
        echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"caseList\",confirmDelete)", '&nbsp;', '', "class='icon-green-common-delete' title='{$lang->testcase->delete}'");

        common::printIcon('testcase', 'createBug', "product=$case->product&extra=caseID=$case->id,version=$case->version,runID=", $case, 'list', 'createBug');
        ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody> 
    <tfoot>
      <tr>
        <td colspan='10'>
          <?php if($cases):?>
          <div class='f-left'>
          <?php
          if($canBatchEdit or $canBatchRun) echo html::selectAll() . html::selectReverse(); 
          if($canBatchEdit) 
          {
              $actionLink = $this->createLink('testcase', 'batchEdit');
              echo html::submitButton($lang->edit, "onclick=setFormAction('$actionLink')");
          }
          if($canBatchRun) 
          {
              $actionLink = $this->createLink('testtask', 'batchRun', "productID=0&orderBy=$orderBy&from=testcase");
              echo html::submitButton($lang->testtask->runCase,  "onclick=setFormAction('$actionLink')");
          }
          ?>
          </div>
          <?php endif?>
          <?php $pager->show();?>
        </td>
      </tr>
    </tfoot>
  </table>
</form>
<?php js::set('listName', 'caseList')?>
<script language="Javascript">$("#<?php echo $type;?>Tab").addClass('active'); </script>
<?php include '../../common/view/footer.html.php';?>
