<table class='table table-form'>
<?php $row = 0;?>
<?php foreach($bugInfo as $infoKey => $infoValue):?>
  <?php if(($row % 1) == 0) echo '<tr>';?>
  <?php $row++;?>
    <td class='text-top'>
      <?php $sum = array_sum($infoValue);?>
      <table class='table table-condensed table-report' id='bugSeverityGroups'>
        <?php if($sum == 0):?>
        <tr>
          <td class='none-data'>
            <h5><?php echo $lang->testreport->$infoKey?></h5>
            <?php echo $lang->testreport->none;?>
          </td>
        </tr>
        <?php else:?>
        <tr class='text-top'>
          <td class='w-p70'>
            <div class='chart-wrapper text-center'>
              <h5><?php echo $lang->testreport->$infoKey?></h5>
              <div class='chart-canvas'>
                <?php if(isset($_POST["chart-{$infoKey}"])):?>
                <img src='<?php echo $_POST["chart-{$infoKey}"]?>' />
                <?php else:?>
                <canvas id='chart-<?php echo $infoKey?>' width='100' height='14' data-responsive='true'></canvas>
                <?php endif;?>
              </div>
            </div>
          </td>
          <td>
            <div class='table-wrapper' style='overflow:auto'>
              <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='pie' data-target='#chart-<?php echo $infoKey?>' data-animation='false'>
                <thead>
                  <tr>
                    <th class='chart-label' colspan='2'><?php echo $lang->report->item;?></th>
                    <th><?php echo $lang->report->value;?></th>
                    <th><?php echo $lang->report->percent;?></th>
                  </tr>
                </thead>
                <?php
                $list = $infoValue;
                if($infoKey == 'bugSeverityGroups')   $list = $lang->bug->severityList;
                if($infoKey == 'bugStatusGroups')     $list = $lang->bug->statusList;
                if($infoKey == 'bugResolutionGroups') $list = $lang->bug->resolutionList;
                ?>
                <?php foreach($list as $listKey => $listValue):?>
                <?php
                $label = $listValue;
                if($infoKey == 'bugOpenedByGroups' or $infoKey == 'bugResolvedByGroups') $label = zget($users, $listKey);
                if($infoKey == 'bugModuleGroups') $label = zget($modules, $listKey);
                if(empty($label)) continue;
                $data = zget($infoValue, $listKey, 0);
                ?>
                <tr class='text-center'>
                  <td class='chart-color w-20px'><i class='chart-color-dot icon-circle'></i></td>
                  <td class='chart-label'><?php echo $label;?></td>
                  <td class='chart-value'><?php echo $data;?></td>
                  <td><?php echo ($data / $sum * 100) . '%';?></td>
                </tr>
                <?php endforeach?>
              </table>
            </div>
          </td>
        </tr>
        <?php endif;?>
      </table>
    </td>
  <?php if(($row % 1) == 0) echo '<tr>';?>
  <?php endforeach;?>
</table>
