<?php include '../../common/view/header.lite.html.php';?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js'); ?>
<?php js::import($jsRoot . 'html2canvas/min.js'); ?>
<?php $soFar = sprintf($this->lang->report->annualData->soFar, $year);?>
<div id='container' style='background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/annual_data_bg.png'?>)'>
  <main id='main' style='background: url(<?php echo $config->webRoot . 'theme/default/images/main/annual_layout_header.png'?>) top no-repeat'>
    <header id='header'>
      <h1 class='text-holder' data-id='title'><?php echo $title;?></h1>
    </header>
    <div id='toolbar'>
      <?php echo html::select('year', $years, $year, "class='form-control'");?>
      <button type='button' class='btn btn-primary' id='exportBtn' title='<?php echo $lang->export;?>'><i class='icon icon-share'></i></button>
      <a id='imageDownloadBtn' class='hidden' download='annual_data.png'></a>
    </div>
    <section id='baseInfo'>
      <header><h2 class='text-holder'><?php echo $lang->report->annualData->baseInfo . $soFar;?></h2></header>
      <div>
        <ul id='infoList'>
          <li>
            <?php echo $userID ? $lang->report->annualData->logins : ($dept ? $lang->report->annualData->deptUsers : $lang->report->annualData->companyUsers);?>
            <strong><?php echo $userID ? $data['logins'] : $data['users'];?></strong>
          </li>
          <li>
            <?php echo $lang->report->annualData->actions;?>
            <strong><?php echo $data['actions'];?></strong>
          </li>
          <li>
            <?php echo $lang->report->annualData->consumed;?>
            <strong><?php echo $data['consumed'];?></strong>
          </li>
          <li class='dropdown dropdown-hover'>
            <?php echo $lang->report->annualData->todos;?>
            <strong><?php echo $data['todos']->count;?></strong>
            <ul class='dropdown-menu pull-right'>
              <li><?php echo $lang->report->annualData->todos;?></li>
              <li><span class='todoStatus'><?php echo $lang->report->annualData->todoStatus['all'];?></span><span><?php echo $data['todos']->count;?></span></li>
              <li><span class='todoStatus'><?php echo $lang->report->annualData->todoStatus['undone'];?></span><span><?php echo $data['todos']->undone;?></span></li>
              <li><span class='todoStatus'><?php echo $lang->report->annualData->todoStatus['done'];?></span><span><?php echo $data['todos']->done;?></span></li>
            </ul>
          </li>
          <?php
          $contributions = 0;
          $maxCount      = 0;
          $radarData     = array('product' => 0, 'project' => 0, 'devel' => 0, 'qa' => 0, 'other' => 0);
          foreach($data['contributions'] as $objectType => $objectContributions)
          {
              $sum = array_sum($objectContributions);
              if($sum > $maxCount) $maxCount = $sum;
              $contributions += $sum;

              foreach($objectContributions as $actionName => $count)
              {
                  $radarType = isset($config->report->annualData['radar'][$objectType][$actionName]) ? $config->report->annualData['radar'][$objectType][$actionName] : 'other';
                  $radarData[$radarType] += $count;
              }
          }
          ?>
          <?php if(!empty($dept) or !empty($userID)):?>
          <li>
            <?php echo $lang->report->annualData->contributions;?>
            <strong><?php echo $contributions;?></strong>
          </li>
          <?php endif;?>
        </ul>
      </div>
    </section>
    <section id='actionData'>
      <header><h2 class='text-holder'><?php echo $lang->report->annualData->actionData . $soFar;?></h2></header>
      <div>
        <ul>
          <?php foreach($lang->report->annualData->objectTypeList as $objectType => $objectName):?>
          <li class='dropdown dropdown-hover'>
            <span class='name'><?php echo $objectName;?></span>
            <span class='ratio'>
            <?php
            $objectContributions = isset($data['contributions'][$objectType]) ? $data['contributions'][$objectType] : array();
            $contributionActions = zget($config->report->annualData['contributions'], $objectType, array_keys($objectContributions));
            
            $colors = $config->report->annualData['colors'];
            $detail = "<li><span class='header'>{$objectName}</span></li>";
            foreach($contributionActions as $actionName)
            {
                if(isset($objectContributions[$actionName]))
                {
                    $color = array_shift($colors);
                    $count = $objectContributions[$actionName];
                    $width = round(($count / $maxCount * 100), 1);
                    echo "<span class='item' style='background-color:{$color};width:{$width}%'>{$count}</span>";
            
                    $detail .= "<li><span class='color' style='background-color:{$color}'></span><span class='item-name'>" . $lang->report->annualData->actionList[$actionName] . "</span><span class='count'>{$count}</span></li>";
                }
            }
            ?>
            </span>
            <ul class='dropdown-menu'><?php echo $detail;?></ul>
          </li>
          <?php endforeach;?>
        </ul>
      </div>
    </section>
    <section id='radar'>
      <header><h2 class='text-holder'><?php echo $lang->report->annualData->radar . $soFar;?></h2></header>
      <div id='radarCanvas'></div>
    </section>
    <section id='projectData'>
      <header><h2 class='text-holder'><?php echo $lang->report->annualData->projects . $soFar;?></h2></header>
      <div>
        <table class='table table-hover'>
          <thead>
            <tr>
              <?php foreach($lang->report->annualData->projectFields as $field => $name):?>
              <th class='<?php echo "c-$field";?>'><?php echo $name;?></th>
              <?php endforeach?>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['projectStat'] as $project):?>
            <tr>
              <?php foreach($lang->report->annualData->projectFields as $field => $name):?>
              <td class='<?php echo "c-$field";?>'><?php echo $project->$field;?></td>
              <?php endforeach?>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </section>
    <section id='productData'>
      <header><h2 class='text-holder'><?php echo $lang->report->annualData->products . $soFar;?></h2></header>
      <div>
        <table class='table table-hover'>
          <thead>
            <tr>
              <?php foreach($lang->report->annualData->productFields as $field => $name):?>
              <th class='<?php echo "c-$field";?>'><?php echo $name;?></th>
              <?php endforeach?>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['productStat'] as $product):?>
            <tr>
              <?php foreach($lang->report->annualData->productFields as $field => $name):?>
              <td class='<?php echo "c-$field";?>'><?php echo $product->$field;?></td>
              <?php endforeach?>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </section>
    <?php if(empty($dept) and empty($userID)):?>
    <section id='allStatusStat'>
      <header><h2 class='text-holder'><?php echo $lang->report->annualData->statusStat;?></h2></header>
      <div>
        <div class='canvas' id='allStoryStatusCanvas'></div>
        <div class='canvas' id='allTaskStatusCanvas'></div>
        <div class='canvas' id='allBugStatusCanvas'></div>
<?php
foreach($data['statusStat'] as $objectType => $objectStatusStat):?>
<span class='<?php echo $objectType;?>Overview hidden'>
<?php
$allCount    = 0;
$undoneCount = 0;
foreach($objectStatusStat as $status => $count)
{
    $allCount += $count;
    if($objectType == 'story' and $status != 'closed') $undoneCount += $count;
    if($objectType == 'task' and $status != 'done' and $status != 'closed' and $status != 'cancel') $undoneCount += $count;
    if($objectType == 'bug' and $status == 'active') $undoneCount += $count;
}
if($objectType == 'story') echo $lang->report->annualData->allStory;
if($objectType == 'task')  echo $lang->report->annualData->allTask;
if($objectType == 'bug')   echo $lang->report->annualData->allBug;
echo ' &nbsp; ' . $allCount;
echo '<br />';
echo $objectType == 'bug' ? $lang->report->annualData->unresolve : $lang->report->annualData->undone;
echo ' &nbsp; ' . $undoneCount;
?>
</span>
<?php endforeach;?>
      </div>
    </section>
    <?php endif;?>
  </main>
  <div id='loadIndicator' class='load-indicator'></div>
</div>
<script>
$(function()
{
    var radarChart  = echarts.init(document.getElementById('radarCanvas'));
    var radarOption = {
      tooltip: {},
      radar: {
          triggerEvent:true,
          <?php
          $max = max($radarData);
          $indicator = array();
          foreach($lang->report->annualData->radarItems as $radarKey => $radarName)
          {
          	$indicator[$radarKey]['name'] = $radarName;
          	$indicator[$radarKey]['max']  = $max;
          }
          ?>
          indicator: <?php echo json_encode(array_values($indicator));?>
      },
      series: [{
          name:'<?php echo $lang->report->annualData->radar;?>',
          type: 'radar',
          itemStyle: {color: "rgb(247, 193, 35)"},
          data: [{value: <?php echo json_encode(array_values($radarData));?>}]
      }]
    };

    radarChart.setOption(radarOption);

    var titleTextStyle = {
        color:'#fff',
        fontSize: 16
    };
    var tooltip = {
        trigger: 'item',
        formatter: '{a} <br/>{b}: {c} ({d}%)'
    };
    var legendLeft = '0';
    var legendTop  = '25';
    var legendItemWidth = 10;
    var legendItemHeight = 10;
    var legendTextStyle = {
        color:'#fff',
        fontSize: 12
    };
    var seriesTop = '50';
    var seriesRadius = ['40%', '70%'];
    var seriesLabel = {
        color:'#fff',
        formatter: '{b}  {d}%'
    };
    var overviewCSS = {position: 'absolute', left: '180px', top: '160px'};

    var storyStatusChart  = echarts.init(document.getElementById('allStoryStatusCanvas'));
    var storyStatusOption = {
	    title: {
            text: '<?php echo $lang->report->annualData->storyStatusStat;?>',
			textStyle: titleTextStyle,
        },
        tooltip: tooltip,
        legend: {
			left: legendLeft,
			top: legendTop,
            itemWidth: legendItemWidth,
            itemHeight: legendItemHeight,
			textStyle: legendTextStyle,
            data: <?php unset($lang->story->statusList['']); echo json_encode(array_values($lang->story->statusList));?>
        },
        series: [
            {
                name: '<?php echo $lang->report->annualData->storyStatusStat;?>',
                type: 'pie',
                top: seriesTop,
                radius: seriesRadius,
                avoidLabelOverlap: false,
                label: seriesLabel,
                <?php
                $storyStatusStat = array();
                foreach($lang->story->statusList as $status => $statusName) $storyStatusStat[$status] = array('name' => $statusName, 'value' => zget($data['statusStat']['story'], $status, 0));
                ?>
                data:<?php echo json_encode(array_values($storyStatusStat));?>
            }
        ]
    }
    storyStatusChart.setOption(storyStatusOption);
    storyStatusChart.on('finished', function()
    {
        $('#allStatusStat .storyOverview').appendTo('#allStoryStatusCanvas').removeClass('hidden').css(overviewCSS);
    });

    var taskStatusChart  = echarts.init(document.getElementById('allTaskStatusCanvas'));
    var taskStatusOption = {
	    title: {
            text: '<?php echo $lang->report->annualData->taskStatusStat;?>',
			textStyle: titleTextStyle,
        },
        tooltip: tooltip,
        legend: {
			left: legendLeft,
			top: legendTop,
            itemWidth: legendItemWidth,
            itemHeight: legendItemHeight,
			textStyle: legendTextStyle,
            data: <?php unset($lang->task->statusList['']); echo json_encode(array_values($lang->task->statusList));?>
        },
        series: [
            {
                name: '<?php echo $lang->report->annualData->taskStatusStat;?>',
                type: 'pie',
                top: seriesTop,
                radius: seriesRadius,
                avoidLabelOverlap: false,
                label: seriesLabel,
                <?php
                $taskStatusStat = array();
                foreach($lang->task->statusList as $status => $statusName) $taskStatusStat[$status] = array('name' => $statusName, 'value' => zget($data['statusStat']['task'], $status, 0));
                ?>
                data:<?php echo json_encode(array_values($taskStatusStat));?>
            }
        ]
    }
    taskStatusChart.setOption(taskStatusOption);
    taskStatusChart.on('finished', function()
    {
        $('#allStatusStat .taskOverview').appendTo('#allTaskStatusCanvas').removeClass('hidden').css(overviewCSS);
    });

    var bugStatusChart  = echarts.init(document.getElementById('allBugStatusCanvas'));
    var bugStatusOption = {
	    title: {
            text: '<?php echo $lang->report->annualData->bugStatusStat;?>',
			textStyle: titleTextStyle
        },
        tooltip: tooltip,
        legend: {
			left: legendLeft,
			top: legendTop,
            itemWidth: legendItemWidth,
            itemHeight: legendItemHeight,
			textStyle: legendTextStyle,
            data: <?php unset($lang->bug->statusList['']); echo json_encode(array_values($lang->bug->statusList));?>
        },
        series: [
            {
                name: '<?php echo $lang->report->annualData->bugStatusStat;?>',
                type: 'pie',
                top: seriesTop,
                radius: seriesRadius,
                avoidLabelOverlap: false,
                label: seriesLabel,
                <?php
                $bugStatusStat = array();
                foreach($lang->bug->statusList as $status => $statusName) $bugStatusStat[$status] = array('name' => $statusName, 'value' => zget($data['statusStat']['bug'], $status, 0));
                ?>
                data:<?php echo json_encode(array_values($bugStatusStat));?>
            }
        ]
    }
    bugStatusChart.setOption(bugStatusOption);
    bugStatusChart.on('finished', function()
    {
        $('#allStatusStat .bugOverview').appendTo('#allBugStatusCanvas').removeClass('hidden').css(overviewCSS);
    });
})
</script>
<?php include '../../common/view/footer.lite.html.php';?>
