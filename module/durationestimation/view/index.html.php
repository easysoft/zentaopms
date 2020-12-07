<?php include '../../common/view/header.html.php';?>
<div id='mainContent' class='main-content'>
  <form class='main-form form-ajax' method='post' id='planForm' enctype='multipart/form-data'>
    <table class='table table-list table-bordered'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->durationestimation->stage;?></th>
          <th><?php echo $lang->durationestimation->workload;?></th>
          <th><?php echo $lang->durationestimation->worktimeRate;?></th>
          <th><?php echo $lang->durationestimation->people;?></th>
          <th><?php echo $lang->durationestimation->startDate;?></th>
          <th><?php echo $lang->durationestimation->endDate;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php foreach($stages as $plan):?>
        <?php $estimation = zget($estimationList, $plan->id, 0);?>
        <tr class='text-center'>
          <td>
            <?php echo $plan->name;?>
            <?php echo html::hidden('stage[]', $plan->id);?>
          </td>
          <td><?php echo zget($estimation, 'workload', ''); ?>% </td>
          <td><?php echo zget($estimation, 'worktimeRate', '');?>% </td>
          <td><?php echo zget($estimation, 'people', '');?></td>
          <td><?php echo zget($estimation, 'startDate', '');?></td>
          <td><?php echo zget($estimation, 'endDate', '');?></td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='6' class='form-actions text-center'>
            <?php echo html::a(inlink('create', "projectID={$project->id}"), $lang->durationestimation->setting, '', "class='btn btn-primary'");?>
          </td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
