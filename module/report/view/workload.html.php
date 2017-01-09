<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/datepicker.html.php';?>
<div id='titlebar'>
  <div class='heading'>
    <span class='prefix'><?php echo html::icon($lang->icons['report-file']);?></span>
    <strong> <?php echo $title;?></strong>
  </div>
</div>
<div class='side'>
  <?php include 'blockreportlist.html.php';?>
  <div class='panel panel-body' style='padding: 10px 6px'>
    <div class='text proversion'>
      <strong class='text-danger small text-latin'>PRO</strong> &nbsp;<span class='text-important'><?php echo $lang->report->proVersion;?></span>
    </div>
  </div>
</div>
<div class='main'>
  <form method='post'>
    <div class='row' style='margin-bottom:5px;'>
      <div class='col-sm-3'>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->report->dept;?></span>
          <?php echo html::select('dept', $depts, $dept, "class='form-control chosen' onchange='changeParams(this)'");?>
        </div>
      </div>
      <div class='col-sm-4'>
        <div class='input-group input-group-sm'>
          <span class='input-group-addon'><?php echo $lang->report->taskAssignedDate;?></span>
          <div class='datepicker-wrapper datepicker-date'><?php echo html::input('begin', $begin, "class='w-100px form-control' onchange='changeParams(this)'");?></div>
          <span class='input-group-addon fix-border'><?php echo $lang->report->to;?></span>
          <div class='datepicker-wrapper datepicker-date'><?php echo html::input('end', $end, "class='form-control' onchange='changeParams(this)'");?></div>
        </div>
      </div>
      <div class='col-sm-2'>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->report->diffDays;?></span>
          <?php echo html::input('days', $days, "class='form-control' autocomplete='off' style='text-align:center'");?>
        </div>
      </div>
      <div class='col-sm-2'>
        <div class='input-group'>
          <span class='input-group-addon'><?php echo $lang->report->workday;?></span>
          <?php echo html::input('workday', $workday, "class='form-control' autocomplete='off' style='width:50px'");?>
        </div>
      </div>
      <div class='col-sm-1'>
        <div class='input-group'><?php echo html::submitButton();?></div>
      </div>
    </div>
  </form>
  <table class='table table-condensed table-striped table-bordered table-fixed active-disabled' id="workload">
    <thead>
    <tr class='colhead'>
      <th><?php echo $lang->report->user;?></th>
      <th><?php echo $lang->report->project;?></th>
      <th><?php echo $lang->report->task;?></th>
      <th><?php echo $lang->report->remain;?></th>
      <th><?php echo $lang->report->taskTotal;?></th>
      <th><?php echo $lang->report->manhourTotal;?></th>
      <th><?php echo $lang->report->workloadAB;?></th>
    </tr>
    </thead>
    <tbody>
    <?php $color = false;?>
    <?php foreach($workload as $account => $load):?>
      <?php if(!array_key_exists($account, $users)) continue;?>
      <tr class="a-center">
        <td rowspan="<?php echo count($load['task']);?>"><?php echo $users[$account];?></td>
        <?php $id = 1;?>
        <?php foreach($load['task'] as $project => $info):?>
        <?php $class = $color ? 'rowcolor' : '';?>
        <?php if($id != 1) echo '<tr class="a-center">';?>
        <td class="<?php echo $class;?>"><?php echo html::a($this->createLink('project', 'view', "projectID={$info['projectID']}"), $project);?></td>
        <td class="<?php echo $class;?>"><?php echo $info['count'];?></td>
        <td class="<?php echo $class;?>"><?php echo $info['manhour'];?></td>
        <?php if($id == 1):?>
        <td rowspan="<?php echo count($load['task']);?>">
          <?php echo $load['total']['count'];?>
        </td>
        <td rowspan="<?php echo count($load['task']);?>">
          <?php echo $load['total']['manhour'];?>
        </td>
        <td rowspan="<?php echo count($load['task']);?>">
          <?php echo round($load['total']['manhour'] / $allHour * 100, 2) . '%';?>
        </td>
        <?php endif;?>
        <?php if($id != 1) echo '</tr>'; $id ++;?>
        <?php $color = !$color;?>
        <?php endforeach;?>
      </tr>
    <?php endforeach;?>
    </tbody>
  </table>
</div>
<?php include '../../common/view/footer.html.php';?>
