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
<style>
.w-230px{width:230px;}
</style>
<?php js::set('confirmDelete', $lang->testcase->confirmDelete)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    echo "<span class='nav-title'>{$lang->testtask->common}: </span>";
    echo html::a(inlink('testtask', "type=wait"),       "<span class='text'>{$lang->testtask->wait}</span>", '', "class='btn btn-link'");
    echo html::a(inlink('testtask', "type=done"),       "<span class='text'>{$lang->testtask->done}</span>", '', "class='btn btn-link'");
    echo "<span class='divider'></span>";
    echo "<span class='nav-title'>{$lang->testcase->common}: </span>";
    echo html::a(inlink('testcase', "type=assigntome"), "<span class='text'>{$lang->testcase->assignToMe}</span>" . ($type == 'assigntome' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'assigntome' ? ' btn-active-text' : '') . "'");
    echo html::a(inlink('testcase', "type=openedbyme"), "<span class='text'>{$lang->testcase->openedByMe}</span>" . ($type == 'openedbyme' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'openedbyme' ? ' btn-active-text' : '') . "'");
    ?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($cases)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->testcase->noCase;?></span></p>
  </div>
  <?php else:?>
  <form id='myCaseForm' class="main-table table-case" data-ride="table" method="post">
    <table class="table has-sort-head" id='caseList'>
      <?php
      $vars = "type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
      $this->app->loadLang('testtask');
      $canBatchEdit = common::hasPriv('testcase', 'batchEdit');
      $canBatchRun  = common::hasPriv('testtask', 'batchRun');
      ?>
      <thead>
        <tr>
          <th class='w-100px'>
            <?php if($canBatchEdit or $canBatchRun):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-50px'>   <?php common::printOrderLink('pri',      $orderBy, $vars, $lang->priAB);?></th>
          <th>                 <?php common::printOrderLink('title',    $orderBy, $vars, $lang->testcase->title);?></th>
          <th class='w-type'>  <?php common::printOrderLink('type',     $orderBy, $vars, $lang->typeAB);?></th>
          <th class='c-user'>  <?php common::printOrderLink('openedBy', $orderBy, $vars, $lang->openedByAB);?></th>
          <th class='w-80px'>  <?php common::printOrderLink('lastRunner',    $orderBy, $vars, $lang->testtask->lastRunAccount);?></th>
          <th class='w-120px'> <?php common::printOrderLink('lastRunDate',   $orderBy, $vars, $lang->testtask->lastRunTime);?></th>
          <th class='w-80px'>  <?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testtask->lastRunResult);?></th>
          <th class='c-status'><?php common::printOrderLink('status',        $orderBy, $vars, $lang->statusAB);?></th>
          <th class='c-actions-5 text-center'> <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($cases as $case):?>
        <?php
        $caseID = $type == 'assigntome' ? $case->case : $case->id;
        $runID  = $type == 'assigntome' ? $case->id   : 0;
        ?>
        <tr>
          <td class="c-id">
            <?php if($canBatchEdit or $canBatchRun):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='caseIDList[]' value='<?php echo $case->id;?>' />
              <label></label>
            </div>
            <?php endif;?>
            <?php printf('%03d', $case->id);?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $case->pri?>' title='<?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span></td>
          <?php $params = "testcaseID=$caseID&version=$case->version";?>
          <?php if($type == 'assigntome') $params .= "&from=testtask&taskID=$case->task";?>
          <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', $params), $case->title, null, "style='color: $case->color'");?></td>
          <td><?php echo zget($lang->testcase->typeList, $case->type);?></td>
          <td><?php echo zget($users, $case->openedBy);?></td>
          <td><?php echo zget($users, $case->lastRunner);?></td>
          <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
          <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
          <td class='<?php if(isset($run)) echo $run->status;?>'><?php echo $this->processStatus('testcase', $case);?></td>
          <td class='c-actions'>
            <?php
            common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$caseID,version=$case->version,runID=$runID", $case, 'list', 'bug');
            common::printIcon('testcase', 'create',  "productID=$case->product&branch=$case->branch&moduleID=$case->module&from=testcase&param=$caseID", $case, 'list', 'copy');
            common::printIcon('testtask', 'runCase', "runID=$runID&caseID=$caseID&version=$case->version", '', 'list', 'play', '', 'iframe', '', "data-width='95%'");
            common::printIcon('testtask', 'results', "runID=$runID&caseID=$caseID", '', 'list', 'list-alt', '', 'iframe', '', "data-width='95%'");
            common::printIcon('testcase', 'edit',    "caseID=$caseID", $case, 'list', 'edit');
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canBatchEdit or $canBatchRun):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
      <?php
      if($canBatchEdit)
      {
          $actionLink = $this->createLink('testcase', 'batchEdit');
          echo html::commonButton($lang->edit, "onclick=setFormAction('$actionLink')");
      }
      if($canBatchRun and $type != 'assigntome')
      {
          $actionLink = $this->createLink('testtask', 'batchRun', "productID=0&orderBy=$orderBy&from=testcase");
          echo html::commonButton($lang->testtask->runCase,  "onclick=setFormAction('$actionLink')");
      }
      ?>
      </div>
      <?php $pager->show('right', 'pagerjs');?>
    </div>
  </form>
  <?php endif;?>
</div>
<?php js::set('listName', 'caseList')?>
<?php include '../../common/view/footer.html.php';?>
