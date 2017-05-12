<table class='table table-form'>
<?php $row = 0;?>
<?php foreach($bugInfo as $infoKey => $infoValue):?>
  <?php if(($row % 1) == 0) echo '<tr>';?>
  <?php $row++;?>
    <td class='text-top'>
      <table class='table table-condensed table-report' id='bugSeverityGroups'>
        <?php if(empty($infoValue)):?>
        <tr>
          <td class='none-data'>
            <h5><?php echo $lang->testreport->$infoKey?></h5>
            <?php echo $lang->testreport->none;?>
          </td>
        </tr>
        <?php else:?>
        <?php $sum = 0; foreach($infoValue as $value) $sum += $value->value;?>
        <tr class='text-top'>
          <td class='w-p70'>
            <div class='chart-wrapper text-center'>
              <h5><?php echo $lang->testreport->$infoKey?></h5>
              <div class='chart-canvas'>
                <?php if(isset($_POST["chart-{$infoKey}"])):?>
                <img src='<?php echo $_POST["chart-{$infoKey}"]?>' />
                <?php else:?>
                <canvas id='chart-<?php echo $infoKey?>' width='90' height='20' data-responsive='true'></canvas>
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
                $data  = 0;
                if(isset($infoValue[$listKey]))
                {
                    $label = $infoValue[$listKey]->name;
                    $data  = $infoValue[$listKey]->value;
                }
                if(empty($label) and empty($data)) continue;
                ?>
                <tr class='text-center'>
                  <td class='chart-color w-20px'><i class='chart-color-dot icon-circle'></i></td>
                  <td class='chart-label'><?php echo $label;?></td>
                  <td class='chart-value'><?php echo $data;?></td>
                  <td><?php echo round($data / $sum * 100, 2) . '%';?></td>
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
