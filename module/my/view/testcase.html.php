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
<?php js::set('mode', $mode);?>
<?php js::set('total', $pager->recTotal);?>
<?php js::set('rawMethod', $app->rawMethod);?>
<style>
.w-230px {width: 230px;}
</style>
<?php js::set('confirmDelete', $lang->testcase->confirmDelete)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    if($app->rawMethod == 'contribute') echo html::a(inlink($app->rawMethod, "mode=$mode&type=openedbyme"), "<span class='text'>{$lang->testcase->openedByMe}</span>" . ($type == 'openedbyme' ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == 'openedbyme' ? ' btn-active-text' : '') . "'");
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
      $vars = "mode=$mode&type=$type&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
      $this->app->loadLang('testtask');
      $canBatchRun    = (common::hasPriv('testtask', 'batchRun')  and $type == 'assigntome');
      $canBatchEdit   = (common::hasPriv('testcase', 'batchEdit') and $type == 'assigntome');
      $canBatchAction = ($canBatchRun or $canBatchEdit);
      ?>
      <thead>
        <tr>
        <th class="<?php echo $canBatchAction ? 'w-100px' : 'w-50px';?>">
            <?php if($canBatchAction):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th class='w-50px'>  <?php common::printOrderLink('pri',      $orderBy, $vars, $lang->priAB);?></th>
          <th>                 <?php common::printOrderLink('title',    $orderBy, $vars, $lang->testcase->title);?></th>
          <?php if($type == 'assigntome'):?>
          <th class='w-100px'> <?php common::printOrderLink('task',     $orderBy, $vars, $lang->testtask->common);?></th>
          <?php endif;?>
          <th class='w-type'>  <?php common::printOrderLink('type',          $orderBy, $vars, $lang->typeAB);?></th>
          <th class='c-user'>  <?php common::printOrderLink('openedBy',      $orderBy, $vars, $lang->openedByAB);?></th>
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
        $caseID       = $type == 'assigntome' ? $case->case : $case->id;
        $runID        = $type == 'assigntome' ? $case->id   : 0;
        $canBeChanged = common::canBeChanged('testcase', $case);
        ?>
        <tr>
          <td class="c-id">
            <?php if($canBatchAction):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='caseIDList[]' value='<?php echo $case->id;?>' <?php if(!$canBeChanged) echo 'disabled';?> />
              <label></label>
            </div>
            <?php endif;?>
            <?php echo sprintf('%03d', $case->id); ?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $case->pri?>' title='<?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span></td>
          <?php $params = "testcaseID=$caseID&version=$case->version";?>
          <?php if($type == 'assigntome') $params .= "&from=testtask&taskID=$case->task";?>
          <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', $params), $case->title, null, "style='color: $case->color'");?></td>
          <?php if($type == 'assigntome'):?>
          <td class='c-name' title='<?php echo $case->taskName;?>'><?php echo $case->taskName;?></td>
          <?php endif;?>
          <td><?php echo zget($lang->testcase->typeList, $case->type);?></td>
          <td><?php echo zget($users, $case->openedBy);?></td>
          <td><?php echo zget($users, $case->lastRunner);?></td>
          <td><?php if(!helper::isZeroDate($case->lastRunDate)) echo date(DT_MONTHTIME1, strtotime($case->lastRunDate));?></td>
          <td class='<?php echo $case->lastRunResult;?>'><?php if($case->lastRunResult) echo $lang->testcase->resultList[$case->lastRunResult];?></td>
          <td class='<?php if(isset($run)) echo $run->status;?>'><?php echo $this->processStatus('testcase', $case);?></td>
          <td class='c-actions'>
            <?php
            if($canBeChanged)
            {
                common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$caseID,version=$case->version,runID=$runID", $case, 'list', 'bug', '', 'iframe', 'true', "data-app='qa' data-toggle=''");
                common::printIcon('testcase', 'create',  "productID=$case->product&branch=$case->branch&moduleID=$case->module&from={$app->rawMethod}&param=$caseID", $case, 'list', 'copy', '', 'iframe', true, "data-width='95%'");
                common::printIcon('testtask', 'runCase', "runID=$runID&caseID=$caseID&version=$case->version", '', 'list', 'play', '', 'iframe', true, "data-width='95%'", '', $case->project);
                common::printIcon('testtask', 'results', "runID=$runID&caseID=$caseID", '', 'list', 'list-alt', '', 'iframe', true, "data-width='95%'", '', $case->project);
                common::printIcon('testcase', 'edit',    "caseID=$caseID", $case, 'list', 'edit', '', 'iframe', true, "data-width='95%'", '', $case->project);
            }
            ?>
          </td>
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
    <div class="table-footer">
      <?php if($canBatchAction):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('testcase', 'batchEdit');
            $misc       = "data-form-action='$actionLink'";
            echo html::commonButton($lang->edit, $misc);
        }
        if($canBatchRun)
        {
            $actionLink = $this->createLink('testtask', 'batchRun', 'productID=0');
            $misc       = "data-form-action='$actionLink'";
            echo html::commonButton($lang->testtask->runCase, $misc);
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
