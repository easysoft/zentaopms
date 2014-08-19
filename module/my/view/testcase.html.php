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
<?php js::set('confirmDelete', $lang->testcase->confirmDelete)?>
<div id='featurebar'>
  <nav class='nav'>
    <?php
    echo "<li id='waitTesttask'>"  . html::a($this->createLink('my', 'testtask', 'type=wait'),  $lang->testtask->wait) . "</li>";
    echo "<li id='doneTesttask'>"  . html::a($this->createLink('my', 'testtask', 'type=done'),  $lang->testtask->done) . "</li>";
    echo "<li id='assigntomeTab'>" . html::a($this->createLink('my', 'testcase', "type=assigntome"),  $lang->testcase->assignToMe) . "</li>";
    echo "<li id='openedbymeTab'>" . html::a($this->createLink('my', 'testcase', "type=openedbyme"),  $lang->testcase->openedByMe) . "</li>";
    ?>
  </nav>
</div>
<form method='post' id='myCaseForm'>
  <table class='table table-condensed table-fixed table-hover table-striped table-borderless tablesorter' id='caseList'>
    <?php 
      $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
      $this->app->loadLang('testtask');
    ?>
    <thead>
      <tr class='text-center'>
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
      <tr class='text-center'>
        <td>
          <?php if($canBatchEdit or $canBatchRun):?><input type='checkbox' name='caseIDList[]'  value='<?php echo $case->id;?>'/><?php endif;?>
          <?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), sprintf('%03d', $case->id));?>
        </td>
        <td><span class='<?php echo 'pri' . zget($lang->testcase->priList, $case->pri, $case->pri)?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span</td>
        <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', "testcaseID=$case->id"), $case->title);?></td>
        <td><?php echo $lang->testcase->typeList[$case->type];?></td>
        <td><?php echo $users[$case->openedBy];?></td>
        <td><?php echo $users[$case->lastRunner];?></td>
        <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
        <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
        <td class='<?php if(isset($run)) echo $run->status;?>'><?php echo $lang->testcase->statusList[$case->status];?></td>
        <td class='text-right'>
        <?php
        common::printIcon('testtask', 'runCase', "runID=0&caseID=$case->id&version=$case->version", '', 'list', 'play', '', 'iframe');
        common::printIcon('testtask', 'results', "runID=0&caseID=$case->id", '', 'list', 'flag-checkered', '', 'iframe');
        common::printIcon('testcase', 'edit',    "caseID=$case->id", $case, 'list', 'pencil');
        common::printIcon('testcase', 'create',  "productID=$case->product&moduleID=$case->module&from=testcase&param=$case->id", $case, 'list', 'copy');

        if(common::hasPriv('testcase', 'delete'))
        {
            $deleteURL = $this->createLink('testcase', 'delete', "caseID=$case->id&confirm=yes");
            echo html::a("javascript:ajaxDelete(\"$deleteURL\",\"caseList\",confirmDelete)", '<i class="icon-remove"></i>', '', "class='btn-icon' title='{$lang->testcase->delete}'");
        }

        common::printIcon('testcase', 'createBug', "product=$case->product&extra=caseID=$case->id,version=$case->version,runID=", $case, 'list', 'bug');
        ?>
        </td>
      </tr>
      <?php endforeach;?>
    </tbody> 
    <tfoot>
      <tr>
        <td colspan='10'>
          <?php if($cases):?>
          <div class='table-actions clearfix'>
          <?php
          if($canBatchEdit or $canBatchRun) echo "<div class='btn-group'>" . html::selectButton() . '</div>';
          if($canBatchEdit) 
          {
              $actionLink = $this->createLink('testcase', 'batchEdit');
              echo html::submitButton("<i class='icon-edit'></i> " . $lang->edit, "onclick=setFormAction('$actionLink')");
          }
          if($canBatchRun) 
          {
              $actionLink = $this->createLink('testtask', 'batchRun', "productID=0&orderBy=$orderBy&from=testcase");
              echo html::submitButton("<i class='icon-play'></i> " . $lang->testtask->runCase,  "onclick=setFormAction('$actionLink')");
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
