<?php include '../../common/view/header.html.php';?>
<?php js::set('project', $project);?>
<?php js::set('estimation', $workestimation);?>
<div id='mainContent' class='main-content'>
  <form class='main-form form-ajax' method='post' id='stageForm' enctype='multipart/form-data'>
    <table class='table table-bordered table-form'>
      <thead>
        <tr class='text-center'>
          <th><?php echo $lang->durationestimation->stage;?></th>
          <th><?php echo $lang->durationestimation->workloadRate;?></th>
          <th><?php echo $lang->durationestimation->workload;?></th>
          <th><?php echo $lang->durationestimation->worktimeRate;?></th>
          <th><?php echo $lang->durationestimation->people;?></th>
          <th><?php echo $lang->durationestimation->startDate;?></th>
          <th><?php echo $lang->durationestimation->endDate;?></th>
        </tr>
      </thead>
      <tbody>
        <?php $i = 0;?>
        <?php if(!empty($stages)):?>
        <?php foreach($stages as $stage):?>
        <?php $estimation = zget($estimationList, $stage->id, array());?>
        <tr>
          <td>
            <?php echo $stage->name;?>
            <?php echo html::hidden('stage[]', $stage->id);?>
          </td>
          <td>
            <div class='input-group'>
              <input type='text' name='workload[]' id='workload<?php echo $i;?>' value='<?php echo zget($estimation, 'workload', $stage->percent); ?>' class='form-control' />
              <span class='input-group-addon'>%</span>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <input type='text' value='' class='form-control' />
              <span class='input-group-addon'><?php echo $lang->hourCommon;?></span>
            </div>
          </td>
          <td>
            <div class='input-group'>
              <input type='text' name='worktimeRate[]' id='<?php echo $i;?>' value='<?php echo zget($estimation, 'worktimeRate', '100');?>' class='form-control' />
              <span class='input-group-addon'>%</span>
            </div>
          </td>
          <td><input type='text' name='people[]' id='people<?php echo $i;?>' value='<?php echo zget($estimation, 'people', 0);?>' class='form-control' /></td>
          <td><input type='text' name='startDate[]' id='startDate<?php echo $i;?>' value='<?php echo zget($estimation, 'startDate', '');?>' class='form-control form-date' /></td>
          <td><input type='text' name='endDate[]' id='endDate<?php echo $i;?>' value='<?php echo zget($estimation, 'endDate', '');?>' class='form-control form-date' /></td>
        </tr>
        <?php $i ++;?>
        <?php endforeach;?>
        <?php endif;?>
        <tr>
          <td colspan='7'><?php printf($lang->durationestimation->summary, zget($workestimation, 'scale', ''));?></td>
        </tr>
      </tbody>
      <tfoot>
        <tr>
          <td colspan='7' class='text-center form-actions'><?php echo html::submitButton() . ' ' . html::backButton(); ?></td>
        </tr>
      </tfoot>
    </table>
    <?php js::set('i', $i);?>
  </form>
</div>
<?php include '../../common/view/footer.html.php';?>
