<table class="table table-bordered basicInfo">
  <tbody>
    <tr>
      <td rowspan='3'><strong><?php echo $lang->milestone->common;?></strong></td>
      <th><?php echo $lang->program->name;?></th>
      <td colspan='3'><?php echo $basicInfo->program->name;?></td>
      <th><?php echo $lang->program->end;?></th>
      <td><?php echo $basicInfo->project->end;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->program->PM;?></th>
      <td><?php echo zget($users, $basicInfo->program->PM);?></td>
      <th><?php echo $lang->milestone->name;?></th>
      <td><?php echo $basicInfo->project->name;?></td>
      <th><?php echo $lang->program->realFinished;?></th>
      <td><?php echo $basicInfo->project->realFinished;?></td>
    </tr>
    <tr>
      <th><?php echo $lang->milestone->startedWeeks;?></th>
      <td><?php echo $basicInfo->project->startedWeeks;?></td>
      <th><?php echo $lang->milestone->finishedWeeks;?></th>
      <td><?php echo $basicInfo->project->finishedWeeks;?></td>
      <th><?php echo $lang->milestone->offset;?></th>
      <td><?php echo $basicInfo->project->offset;?></td>
    </tr>
  </tbody>
</table>
