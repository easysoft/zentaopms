<?php $sysURL = $this->session->notHead ? common::getSysURL() : '';?>
<table class='table main-table' id='cases'>
  <thead>
    <tr>
      <th class='c-id'>     <?php echo $lang->idAB;?></th>
      <th class='c-status'> <?php echo $lang->priAB;?></th>
      <th class='text-left'><?php echo $lang->testcase->title;?></th>
      <th class='c-status'> <?php echo $lang->testcase->type;?></th>
      <th class='c-user'>   <?php echo $lang->testtask->assignedTo;?></th>
      <th class='c-user'>   <?php echo $lang->testtask->lastRunAccount;?></th>
      <th class='c-date'>   <?php echo $lang->testtask->lastRunTime;?></th>
      <th class='c-status'> <?php echo $lang->testtask->lastRunResult;?></th>
      <th class='c-status'> <?php echo $lang->statusAB;?></th>
    </tr>
  </thead>
  <?php if($cases):?>
  <tbody>
    <?php $i = 0;?>
    <?php foreach($cases as $taskID => $caseList):?>
    <?php foreach($caseList as $case):?>
    <?php if(!isset($pager) and $i > 50):?>
    <?php echo html::hidden('cases[]', $case->id);?>
    <?php else:?>
    <tr>
      <td><?php echo sprintf('%03d', $case->id) . html::hidden('cases[]', $case->id)?></td>
      <td><span class='label-pri label-pri-<?php echo $case->pri?>' title='<?php echo zget($lang->testcase->priList, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri);?></span></td>
      <td class='text-left c-name' title='<?php echo $case->title?>'><?php echo html::a($sysURL . $this->createLink('testcase', 'view', "caseID=$case->id&version=$case->version&from=testtask&taskID=$case->task", '', true), $case->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
      <td><?php echo zget($lang->testcase->typeList, $case->type);?></td>
      <td><?php echo zget($users, $case->assignedTo);?></td>
      <td><?php echo zget($users, $case->lastRunner);?></td>
      <td><?php echo substr($case->lastRunDate, 2);?></td>
      <td class='result-testcase <?php echo $case->lastRunResult;?>'><?php echo zget($lang->testcase->resultList, $case->lastRunResult);?></td>
      <?php $status = $this->processStatus('testcase', $case);?>
      <td title='<?php echo $status;?>'>
        <span class="status-case status-<?php echo $case->status?>"><?php echo $status;?></span>
      </td>
    </tr>
    <?php endif;?>
    <?php $i++;?>
    <?php endforeach;?>
    <?php endforeach;?>
    <?php if(!isset($pager) and $i > 50):?>
    <tr>
      <td colspan='9'><?php echo sprintf($lang->testreport->hiddenCase, $i - 50);?></td>
    </tr>
    <?php endif;?>
  </tbody>
</table>
<?php if(isset($pager)):?>
<div class='table-footer'>
  <?php $pager->show('right', 'pagerjs');?>
</div>
<?php endif;?>
  <?php else:?>
  <tr><td class='none-data' colspan='9'><?php echo $lang->testreport->none;?></td></tr>
  <?php endif;?>
</table>
<style>
.c-date {width: 130px;}
#cases + .table-footer {margin-top: -20px;}
</style>
