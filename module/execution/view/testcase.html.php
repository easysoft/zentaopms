<?php include '../../common/view/header.html.php';?>
<div id="mainMenu" class="clearfix">
  <div class="btn-toolbar pull-left">
    <?php echo html::a(inlink('testcase', "executionID=$executionID&type=$type&orderBy=$orderBy"), "<span class='text'>{$lang->execution->all}</span>", '', "class='btn btn-link btn-active-text'");?>
  </div>
  <div class="btn-toolbar pull-right">
    <?php if(common::canModify('execution', $execution)) echo html::a(helper::createLink('testcase', 'create', "productID=$productID&branch=0&moduleID=0&from=execution&param=$execution->id", '', '', '', true), "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-primary' data-app='{$this->app->openApp}'");?>
  </div>
</div>
<div id="mainContent">
  <?php if(empty($cases)):?>
  <div class="table-empty-tip">
    <p>
      <span class="text-muted"><?php echo $lang->testcase->noCase;?></span>
      <?php if(common::canModify('execution', $execution) and common::hasPriv('testcase', 'create')):?>
      <?php echo html::a(helper::createLink('testcase', 'create', "productID=$productID&branch=0&moduleID=0&from=execution&param=$execution->id", '', '', '', true), "<i class='icon icon-plus'></i> " . $lang->testcase->create, '', "class='btn btn-info' data-app='{$this->app->openApp}'");?>
      <?php endif;?>
    </p>
  </div>
  <?php else:?>
  <form class='main-table' method='post' id='executionBugForm' data-ride="table">
    <table class='table has-sort-head' id='bugList'>
    <?php $vars = "executionID=$executionID&type=$type&orderBy=%s&recTotal={$pager->recTotal}&recPerPage={$pager->recPerPage}&pageID={$pager->pageID}";?>
      <thead>
        <tr>
          <th class='w-50px'>  <?php common::printOrderLink('id',       $orderBy, $vars, $lang->idAB);?></th>
          <th class='w-50px'>  <?php common::printOrderLink('pri',      $orderBy, $vars, $lang->priAB);?></th>
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
        $canBeChanged = common::canBeChanged('testcase', $case);
        ?>
        <tr>
          <td class="c-id">
            <?php echo sprintf('%03d', $case->id); ?>
          </td>
          <td><span class='label-pri <?php echo 'label-pri-' . $case->pri?>' title='<?php echo zget($lang->testcase->priList, $case->pri, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri, $case->pri)?></span></td>
          <?php $params = "testcaseID=$caseID&version=$case->version";?>
          <?php if($type == 'assigntome') $params .= "&from=testtask&taskID=$case->task";?>
          <td class='text-left'><?php echo html::a($this->createLink('testcase', 'view', $params, '', '', $case->project), $case->title, null, "style='color: $case->color' data-app='{$this->app->openApp}'");?></td>
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
                common::printIcon('testcase', 'createBug', "product=$case->product&branch=$case->branch&extra=caseID=$caseID,version=$case->version,runID=$runID", $case, 'list', 'bug', '', '', '', '', '', $case->project);
                common::printIcon('testcase', 'create',  "productID=$case->product&branch=$case->branch&moduleID=$case->module&from=testcase&param=$caseID", $case, 'list', 'copy', '', '', '', '', '', $case->project);
                common::printIcon('testtask', 'runCase', "runID=$runID&caseID=$caseID&version=$case->version", '', 'list', 'play', '', 'iframe', true, "data-width='95%'", '', $case->project);
                common::printIcon('testtask', 'results', "runID=$runID&caseID=$caseID", '', 'list', 'list-alt', '', 'iframe', true, "data-width='95%'", '', $case->project);
                common::printIcon('testcase', 'edit',    "caseID=$caseID", $case, 'list', 'edit', '', '', '', '', '', $case->project);
            }
            ?>
          </td> 
        </tr>
        <?php endforeach;?>
      </tbody>
    </table>
  </form>
  <?php endif;?>
</div>
<?php include '../../common/view/footer.html.php';?>
