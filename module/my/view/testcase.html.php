<?php
/**
 * The test view file of dashboard module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2023 禅道软件（青岛）有限公司(ZenTao Software (Qingdao) Co., Ltd. www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
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
<?php js::set('confirmDelete', $lang->testcase->confirmDelete)?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php
    $recTotalLabel = " <span class='label label-light label-badge'>{$pager->recTotal}</span>";
    foreach($lang->my->featureBar[$app->rawMethod]['testcase'] as $typeKey => $name)
    {
        echo html::a(inlink($app->rawMethod, "mode=$mode&type=$typeKey"), "<span class='text'>{$name}</span>" . ($type == $typeKey ? $recTotalLabel : ''), '', "class='btn btn-link" . ($type == $typeKey ? ' btn-active-text' : '') . "'");
    }
    ?>
    <a class="btn btn-link querybox-toggle" id='bysearchTab'><i class="icon icon-search muted"></i> <?php echo $lang->search->common;?></a>
  </div>
</div>
<div id="mainContent">
  <div class="cell<?php if($type == 'bysearch') echo ' show';?>" id="queryBox" data-module=<?php echo ($app->rawMethod == 'contribute' ? 'contributeTestcase' : 'workTestcase');?>></div>
  <?php if(empty($cases)):?>
  <div class="table-empty-tip">
    <p><span class="text-muted"><?php echo $lang->testcase->noCase;?></span></p>
  </div>
  <?php else:?>
  <form id='myCaseForm' class="main-table table-case" data-ride="table" method="post">
    <table class="table has-sort-head" id='caseList'>
      <?php
      $vars = "mode=$mode&type=$type&param=$param&orderBy=%s&recTotal=$recTotal&recPerPage=$recPerPage&pageID=$pageID";
      $this->app->loadLang('testtask');
      $canBatchEdit = (common::hasPriv('testcase', 'batchEdit') and $type == 'assigntome');
      ?>
      <thead>
        <tr>
        <th class="<?php echo $canBatchEdit ? 'w-100px' : 'w-50px';?>">
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary check-all" title="<?php echo $lang->selectAll?>">
              <label></label>
            </div>
            <?php endif;?>
            <?php common::printOrderLink('id', $orderBy, $vars, $lang->idAB);?>
          </th>
          <th>                    <?php common::printOrderLink('title',         $orderBy, $vars, $lang->testcase->title);?></th>
          <th class='c-pri'>      <?php common::printOrderLink('pri',           $orderBy, $vars, $lang->priAB);?></th>
          <?php if($type == 'assigntome'):?>
          <th class='c-task'>     <?php common::printOrderLink('task',          $orderBy, $vars, $lang->testtask->common);?></th>
          <?php endif;?>
          <th class='c-type'>     <?php common::printOrderLink('type',          $orderBy, $vars, $lang->typeAB);?></th>
          <th class='c-status'>   <?php echo $lang->statusAB;?></th>
          <th class='c-user'>     <?php common::printOrderLink('openedBy',      $orderBy, $vars, $lang->testcase->openedByAB);?></th>
          <th class='c-user'>     <?php common::printOrderLink('lastRunner',    $orderBy, $vars, $lang->testtask->lastRunAccount);?></th>
          <th class='c-full-date'><?php common::printOrderLink('lastRunDate',   $orderBy, $vars, $lang->testtask->lastRunTime);?></th>
          <th class='c-result'>   <?php common::printOrderLink('lastRunResult', $orderBy, $vars, $lang->testtask->lastRunResult);?></th>
          <th class='c-actions-5 text-center'> <?php echo $lang->actions;?></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($cases as $case):?>
        <?php
        $caseID       = $type == 'assigntome' ? $case->case : $case->id;
        $runID        = $type == 'assigntome' ? $case->run  : 0;
        $canBeChanged = common::canBeChanged('testcase', $case);
        ?>
        <tr>
          <td class="c-id">
            <?php if($canBatchEdit):?>
            <div class="checkbox-primary">
              <input type='checkbox' name='caseIDList[]' value='<?php echo $case->id;?>' <?php if(!$canBeChanged) echo 'disabled';?> />
              <label></label>
            </div>
            <?php endif;?>
            <?php echo sprintf('%03d', $case->id); ?>
          </td>
          <?php $params = "testcaseID=$caseID&version=$case->version";?>
          <?php if($type == 'assigntome') $params .= "&from=testtask&taskID=$case->task";?>
          <td class='c-name'><?php echo html::a($this->createLink('testcase', 'view', $params), $case->title, null, "style='color: $case->color' title='{$case->title}'");?></td>
          <td><span class='label-pri <?php echo 'label-pri-' . $case->pri?>' title='<?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span></td>
          <?php if($type == 'assigntome'):?>
          <td class='c-name' title='<?php echo $case->taskName;?>'><?php echo $case->taskName;?></td>
          <?php endif;?>
          <td><?php echo zget($lang->testcase->typeList, $case->type);?></td>
          <td class='status-testcase status-<?php echo $case->status;?> nobr'>
            <?php
            if($case->needconfirm)
            {
                print("<span class='status-story status-changed' title='{$this->lang->story->changed}'>{$this->lang->story->changed}</span>");
            }
            elseif(isset($case->fromCaseVersion) and $case->fromCaseVersion > $case->version and !$case->needconfirm)
            {
                print("<span class='status-story status-changed' title='{$this->lang->testcase->changed}'>{$this->lang->testcase->changed}</span>");
            }
            else
            {
                print("<span class='status-testcase status-{$case->status}'>" . $this->processStatus('testcase', $case) . "</span>");
            }
            ?>
          </td>
          <td><?php echo zget($users, $case->openedBy);?></td>
          <td><?php echo zget($users, $case->lastRunner);?></td>
          <td><?php echo helper::isZeroDate($case->lastRunDate) ? '' : substr($case->lastRunDate, 5, 11);?></td>
          <td class='result-testcase <?php echo $case->lastRunResult;?>'><?php echo $case->lastRunResult ? $lang->testcase->resultList[$case->lastRunResult] : $lang->testcase->unexecuted;?></td>
          <td class='c-actions'>
            <?php
            if($canBeChanged)
            {
                $disabled = (isset($case->lastRunResult) and $case->lastRunResult != 'fail') ? 'disabled' : '';
                common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$caseID,version=$case->version,runID=$runID", $case, 'list', 'bug', '', "iframe $disabled", 'true', "data-app='qa' data-toggle=''");
                common::printIcon('testcase', 'create',  "productID=$case->product&branch=$case->branch&moduleID=$case->module&from={$app->rawMethod}&param=$caseID", $case, 'list', 'copy', '', 'iframe', true, "data-width='95%'");
                $disabled = $case->status == 'wait' ? 'disabled' : '';
                common::printIcon('testtask', 'runCase', "runID=$runID&caseID=$caseID&version=$case->version", '', 'list', 'play', '', "iframe $disabled", true, "data-width='95%'", '', $case->project);
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
      <?php if($canBatchEdit):?>
      <div class="checkbox-primary check-all"><label><?php echo $lang->selectAll?></label></div>
      <?php endif;?>
      <div class="table-actions btn-toolbar">
        <?php
        if($canBatchEdit)
        {
            $actionLink = $this->createLink('testcase', 'batchEdit', "productID=0&branch=all&type=case&tab=my");
            $misc       = "data-form-action='$actionLink'";
            echo html::commonButton($lang->edit, $misc);
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
