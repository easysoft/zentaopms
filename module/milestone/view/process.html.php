<table class="table table-bordered basicInfo">
  <thead>
    <tr>
      <th rowspan='2'><?php echo $lang->milestone->processCommon;?></th>
      <th rowspan='2'><?php echo $lang->milestone->stage;?></th>
      <th rowspan='2'><?php echo $lang->milestone->toNow;?></th>
      <th colspan='2' class='text-center'><?php echo $lang->milestone->targetRange;?></th>
      <th rowspan='2' class='text-center'><?php echo $lang->milestone->analysis;?></th>
      <th rowspan='2' class='text-center'><?php echo $lang->milestone->stage;?></th>
      <th rowspan='2' class='text-center'><?php echo $lang->milestone->toNow;?></th>
    </tr>
    <tr>
      <th class='text-center'><?php echo $lang->milestone->ge;?></th>
      <th class='text-center'><?php echo $lang->milestone->le;?></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td><?php echo $lang->milestone->PV;?></td>
      <td><?php echo $process->milestonePV;?></td>
      <td><?php echo $process->nowPV;?></td>
      <td class='text-center'>-</td>
      <td class='text-center'>-</td>
      <td rowspan='3' class='text-center'><?php echo $lang->milestone->process;?></td>
      <td rowspan='3'><?php echo $process->milestoneSpiTip;?></td>
      <td rowspan='3'><?php echo $process->nowSpiTip;?></td>
    </tr>
    <tr>
      <td><?php echo $lang->milestone->EV;?></td>
      <td><?php echo $process->milestoneEV;?></td>
      <td><?php echo $process->nowEV;?></td>
      <td class='text-center'>-</td>
      <td class='text-center'>-</td>
    </tr>
    <tr>
      <td><?php echo $lang->milestone->AC;?></td>
      <td><?php echo $process->milestoneAC;?></td>
      <td><?php echo $process->nowAC;?></td>
      <td class='text-center'>-</td>
      <td class='text-center'>-</td>
    </tr>
    <tr>
      <td><?php echo $lang->milestone->SPI;?></td>
      <td><?php echo $process->milestoneSPI;?></td>
      <td><?php echo $process->nowSPI;?></td>
      <td class='text-center'><?php echo $process->spiMin;?></td>
      <td class='text-center'><?php echo $process->spiMax;?></td>
      <td rowspan='4' class='text-center'><?php echo $lang->milestone->projectCost;?></td>
      <td rowspan='4'><?php echo $process->milestoneCpiTip;?></td>
      <td rowspan='4'><?php echo $process->nowCpiTip;?></td>
    </tr>
    <tr>
      <td><?php echo $lang->milestone->CPI;?></td>
      <td><?php echo $process->milestoneCPI;?></td>
      <td><?php echo $process->nowCPI;?></td>
      <td class='text-center'><?php echo $process->cpiMin;?></td>
      <td class='text-center'><?php echo $process->cpiMax;?></td>
    </tr>
    <tr>
      <td><?php echo $lang->milestone->SV;?></td>
      <td><?php echo $process->milestoneSV . '%';?></td>
      <td><?php echo $process->nowSV . '%';?></td>
      <td class='text-center'><?php echo $process->svMin . '%';?></td>
      <td class='text-center'><?php echo $process->svMax . '%';?></td>
    </tr>
    <tr>
      <td><?php echo $lang->milestone->CV;?></td>
      <td><?php echo $process->milestoneCV . '%';?></td>
      <td><?php echo $process->nowCV . '%';?></td>
      <td class='text-center'><?php echo $process->cvMin . '%';?></td>
      <td class='text-center'><?php echo $process->cvMax . '%';?></td>
    </tr>
  </tbody>
</table>
