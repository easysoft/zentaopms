<table class='table table-form'>
  <?php foreach($charts as $chartType => $chartOption):?>
  <tr>
    <td class='text-top'>
      <table class='table table-condensed table-report' id='bugSeverityGroups'>
        <?php if(empty($datas[$chartType])):?>
        <tr>
          <td class='none-data'>
            <h5><?php echo $lang->testtask->report->charts[$chartType];?></h5>
            <?php echo $lang->testreport->none;?>
          </td>
        </tr>
        <?php else:?>
        <tr class='text-top'>
          <td class='w-p60'>
            <div class='chart-wrapper text-center'>
              <h5><?php echo $lang->testtask->report->charts[$chartType];?></h5>
              <div class='chart-canvas'><canvas id='chart-<?php echo $chartType ?>' width='<?php echo $chartOption->width;?>' height='<?php echo $chartOption->height;?>' data-responsive='true'></canvas></div>
            </div>
          </td>
          <td class='text-top'>
            <div class='table-wrapper' style='overflow:auto'>
              <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='<?php echo $chartOption->type; ?>' data-target='#chart-<?php echo $chartType ?>' data-animation='false'>
                <thead>
                  <tr>
                    <th class='chart-label' colspan='2'><?php echo $lang->report->item;?></th>
                    <th class='c-id'><?php echo $lang->report->value;?></th>
                    <th class='c-id'><?php echo $lang->report->percent;?></th>
                  </tr>
                </thead>
                <?php foreach($datas[$chartType] as $key => $data):?>
                <tr class='text-center'>
                  <td class='chart-color'><i class='chart-color-dot'></i></td>
                  <td class='chart-label'><?php echo $data->name;?></td>
                  <td class='chart-value'><?php echo $data->value;?></td>
                  <td><?php echo ($data->percent * 100) . '%';?></td>
                </tr>
                <?php endforeach;?>
              </table>
            </div>
          </td>
        </tr>
        <?php endif;?>
      </table>
    </td>
  </tr>
  <?php endforeach;?>
  <?php foreach($bugInfo as $infoKey => $infoValue):?>
  <tr>
    <td class='text-top'>
      <table class='table table-condensed table-report' id='bugStageGroups'>
        <?php if(empty($infoValue)):?>
        <tr>
          <td class='none-data'>
            <h5><?php echo $lang->testreport->$infoKey?></h5>
            <?php echo $lang->testreport->none;?>
          </td>
        </tr>
        <?php elseif($infoKey == 'bugStageGroups'):?>
        <tr class='text-top'>
          <td class='w-p60'>
            <div class='chart-wrapper text-center'>
              <h5><?php echo $lang->testreport->$infoKey?></h5>
              <div class='chart-canvas'>
                <?php if(isset($_POST["chart-{$infoKey}"]) and strpos($_POST["chart-{$infoKey}"], 'data:image/png;base64,') === 0):?>
                <img src='<?php echo strip_tags($_POST["chart-{$infoKey}"])?>' />
                <?php else:?>
                <canvas id='chart-<?php echo $infoKey?>' width='90' height='20' data-responsive='true'></canvas>
                <?php endif;?>
              </div>
            </div>
          </td>
          <td class='text-top'>
            <div class='table-wrapper' style='overflow:auto'>
              <table class='table table-condensed table-hover table-striped table-bordered' data-chart='bar' data-target='#chart-<?php echo $infoKey?>' data-animation='false'>
                <thead>
                  <tr class='text-center'>
                    <th class='c-status'><?php echo $lang->bug->pri;?></th>
                    <th class='c-id'><?php echo $lang->testreport->bugStageList['generated'];?></th>
                    <th class='c-id'><?php echo $lang->testreport->bugStageList['legacy'];?></th>
                    <th class='c-id'><?php echo $lang->testreport->bugStageList['resolved'];?></th>
                  </tr>
                </thead>
                <?php foreach($lang->bug->priList as $key => $value):?>
                <tr class='text-center'>
                  <td class='chart-color c-icon'><i class="chart-color-dot pri-<?php echo $key;?>"></i> <?php echo $key == 0 ? $lang->null : $value;?></td>
                  <td class='chart-value'><?php echo $infoValue[$key]['generated'];?></td>
                  <td class='chart-value'><?php echo $infoValue[$key]['legacy'];?></td>
                  <td class='chart-value'><?php echo $infoValue[$key]['resolved'];?></td>
                </tr>
                <?php endforeach?>
              </table>
            </div>
          </td>
        </tr>
        <?php elseif($infoKey == 'bugHandleGroups'):?>
        <tr class='text-top'>
          <td class='w-p60'>
            <div class='chart-wrapper text-center'>
              <h5><?php echo $lang->testreport->$infoKey?></h5>
              <div class='chart-canvas'>
                <?php if(isset($_POST["chart-{$infoKey}"]) and strpos($_POST["chart-{$infoKey}"], 'data:image/png;base64,') === 0):?>
                <img src='<?php echo strip_tags($_POST["chart-{$infoKey}"])?>' />
                <?php else:?>
                <canvas id='chart-<?php echo $infoKey?>' width='90' height='20' data-responsive='true'></canvas>
                <?php endif;?>
              </div>
            </div>
          </td>
          <td class='text-top'>
            <div class='table-wrapper' style='overflow:auto'>
              <table class='table table-condensed table-hover table-striped table-bordered' data-chart='line' data-target='#chart-<?php echo $infoKey?>' data-animation='false'>
                <thead>
                  <tr class='text-center'>
                    <th class='c-date'><?php echo $lang->testreport->date;?></th>
                    <th class='c-id'><i class='chart-color-dot generated'></i> <?php echo $lang->testreport->bugStageList['generated'];?></th>
                    <th class='c-id'><i class='chart-color-dot legacy'></i> <?php echo $lang->testreport->bugStageList['legacy'];?></th>
                    <th class='c-id'><i class='chart-color-dot resolved'></i> <?php echo $lang->testreport->bugStageList['resolved'];?></th>
                  </tr>
                </thead>
                <?php
                $beginTime = isset($report->begin) ? strtotime($report->begin) : strtotime($begin);
                $endTime   = isset($report->end) ? strtotime($report->end) : strtotime($end);
                ?>
                <?php for($time = $beginTime; $time <= $endTime; $time += 86400):?>
                <?php $date = date('m-d', $time);?>
                <tr class='text-center'>
                  <td class='chart-value'><?php echo $date?></td>
                  <td class='chart-value'><?php echo $infoValue['generated'][$date];?></td>
                  <td class='chart-value'><?php echo $infoValue['legacy'][$date];?></td>
                  <td class='chart-value'><?php echo $infoValue['resolved'][$date];?></td>
                </tr>
                <?php endfor?>
              </table>
            </div>
          </td>
        </tr>
        <?php else:?>
        <?php $sum = 0; foreach($infoValue as $value) $sum += $value->value;?>
        <tr class='text-top'>
          <td class='w-p60'>
            <div class='chart-wrapper text-center'>
              <h5><?php echo $lang->testreport->$infoKey?></h5>
              <div class='chart-canvas'>
                <?php if(isset($_POST["chart-{$infoKey}"]) and strpos($_POST["chart-{$infoKey}"], 'data:image/png;base64,') === 0):?>
                <img src='<?php echo strip_tags($_POST["chart-{$infoKey}"])?>' />
                <?php else:?>
                <canvas id='chart-<?php echo $infoKey?>' width='500' height='140' data-responsive='true'></canvas>
                <?php endif;?>
              </div>
            </div>
          </td>
          <td class='text-top'>
            <div class='table-wrapper' style='overflow:auto'>
              <table class='table table-condensed table-hover table-striped table-bordered table-chart' data-chart='pie' data-target='#chart-<?php echo $infoKey?>' data-animation='false'>
                <thead>
                  <tr>
                    <th class='chart-label' colspan='2'><?php echo $lang->report->item;?></th>
                    <th class='c-id'><?php echo $lang->report->value;?></th>
                    <th class='c-id'><?php echo $lang->report->percent;?></th>
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
                <?php $colorList = $infoKey == 'bugSeverityGroups' ? $config->bug->colorList->severity : array();?>
                <tr class='text-center' data-color="<?php echo !empty($colorList) ? zget($colorList, $label, '#C0C0C0') : '';?>">
                  <td class='chart-color c-icon'><i class='chart-color-dot'></i></td>
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
  </tr>
  <?php endforeach;?>
</table>
<?php js::set('bugStageList', $lang->testreport->bugStageList);?>
<?php js::set('bugStageValueList', array_values($lang->testreport->bugStageList));?>
<?php js::set('bugPriList', $lang->bug->priList);?>
<?php js::set('zeroPri', $lang->null);?>
<?php js::set('bugStageGroups', $bugInfo['bugStageGroups']);?>
<?php js::set('bugHandleGroups', $bugInfo['bugHandleGroups']);?>
<?php js::set('dateGroups', !empty($bugInfo['bugHandleGroups']) ? array_keys($bugInfo['bugHandleGroups']['generated']) : '');?>
<?php js::set('priColorList', $config->bug->colorList->pri);?>
<script>
var priList   = [];
var stageList = [];
var colorList = ['#d5d9df', '#d50000', '#ff9800', '#2098ee', '#009688'];
var colorKey  = 0;
for(var key in bugPriList)
{
    var currentColor = colorKey <= 7 ? priColorList[colorKey] : '#C0C0C0';
    $('.pri-' + key).css('background', currentColor);
    var priName = key == 0 ? zeroPri : bugPriList[key];
    var pri = {
        label: priName,
        color: currentColor,
        fillColor: currentColor,
        data:  [bugStageGroups[key]['generated'], bugStageGroups[key]['legacy'], bugStageGroups[key]['resolved']]
    }
    priList.push(pri);
    colorKey++;
}
var data = {
    labels: bugStageValueList,
    datasets: priList,
};
var options = {responsive: true};
var bugStageGroups = $('#chart-bugStageGroups').barChart(data, options);

colorKey = 2;
for(var key in bugHandleGroups)
{
    $('.' + key).css('background', colorList[colorKey]);
    var stageName = bugStageList[key];
    var stage = {
        label: stageName,
        color: colorList[colorKey],
        data:  bugHandleGroups[key]
    }
    stageList.push(stage);
    colorKey++;
}
var data = {
    labels: dateGroups,
    datasets: stageList,
};
var bugHandleGroups = $('#chart-bugHandleGroups').lineChart(data, options);
</script>
