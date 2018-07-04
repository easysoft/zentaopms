<?php $sysURL = $this->session->notHead ? common::getSysURL() : '';?>
<table class='table main-table' id='cases'>
  <thead>
    <tr>
      <th class='w-id'>    <?php echo $lang->idAB;?></th>
      <th class='w-pri'>   <?php echo $lang->priAB;?></th>
      <th class='text-left'><?php echo $lang->testcase->title;?></th>
      <th class='w-type'>  <?php echo $lang->testcase->type;?></th>
      <th class='w-user'>  <?php echo $lang->testtask->assignedTo;?></th>
      <th class='w-user'>  <?php echo $lang->testtask->lastRunAccount;?></th>
      <th class='w-150px'> <?php echo $lang->testtask->lastRunTime;?></th>
      <th class='w-80px'>  <?php echo $lang->testtask->lastRunResult;?></th>
      <th class='w-100px'><?php echo $lang->statusAB;?></th>
    </tr>
  </thead>
  <?php if($cases):?>
  <tbody>
    <?php foreach($cases as $case):?>
    <tr>
      <td><?php echo sprintf('%03d', $case->id) . html::hidden('cases[]', $case->id)?></td>
      <td><span class='label-pri label-pri-<?php echo $case->pri?>' title='<?php echo zget($lang->testcase->priList, $case->pri);?>'><?php echo zget($lang->testcase->priList, $case->pri);?></span></td>
      <td class='text-left' title='<?php echo $case->title?>'><?php echo html::a($sysURL . $this->createLink('testcase', 'view', "caseID=$case->id&version=$case->version&from=testtask&taskID=$case->task", '', true), $case->title, '', "data-toggle='modal' data-type='iframe' data-width='90%'");?></td>
      <td><?php echo zget($lang->testcase->typeList, $case->type);?></td>
      <td><?php echo zget($users, $case->assignedTo);?></td>
      <td><?php echo zget($users, $case->lastRunner);?></td>
      <td><?php echo substr($case->lastRunDate, 2);?></td>
      <td><?php echo zget($lang->testcase->resultList, $case->lastRunResult);?></td>
      <td title='<?php echo zget($lang->testtask->statusList, $case->status);?>'>
        <span class="status-<?php echo $case->status?>">
          <span class="label label-dot"></span>
          <span class='status-text'><?php echo zget($lang->testtask->statusList, $case->status);?></span>
        </span>
      </td>
    </tr>
    <?php endforeach;?>
  </tbody>
  <?php else:?>
  <tr><td class='none-data' colspan='9'><?php echo $lang->testreport->none;?></td></tr>
  <?php endif;?>
</table>
