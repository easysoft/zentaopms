<?php include '../../common/view/header.lite.html.php';?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js'); ?>
<?php js::import($jsRoot . 'html2canvas/min.js'); ?>
<?php $annualDataLang   = $lang->report->annualData;?>
<?php $annualDataConfig = $config->report->annualData;?>
<?php $soFar = sprintf($annualDataLang->soFar, $year);?>
<div id='container' style='background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/annual_data_bg.png'?>)'>
  <main id='main' style='background: url(<?php echo $config->webRoot . 'theme/default/images/main/annual_layout_header.png'?>) top no-repeat'>
    <header id='header'>
      <h1 class='text-holder' data-id='title'><?php echo $title;?></h1>
    </header>
    <div id='toolbar'>
      <div class='pull-left'>
        <span><?php echo $annualDataLang->scope;?></span>
        <?php echo html::select('year', $years, $year, "class='form-control'");?>
        <?php echo html::select('dept', $depts, $dept, "class='form-control chosen'");?>
        <?php echo html::select('userID', $users, $userID, "class='form-control chosen'");?>
      </div>
      <div class='pull-right'>
        <button type='button' class='btn btn-primary' id='exportBtn' title='<?php echo $lang->export;?>'><i class='icon icon-export'></i></button>
        <a id='imageDownloadBtn' class='hidden' download='annual_data.png'></a>
      </div>
    </div>
    <section id='baseInfo'>
      <header><h2 class='text-holder'><?php echo $annualDataLang->baseInfo . $soFar;?></h2></header>
      <div>
        <ul id='infoList'>
          <li>
            <?php echo $userID ? $annualDataLang->logins : ($dept ? $annualDataLang->deptUsers : $annualDataLang->companyUsers);?>
            <strong><?php echo $userID ? $data['logins'] : $data['users'];?></strong>
          </li>
          <li>
            <?php echo $annualDataLang->actions;?>
            <strong><?php echo $data['actions'];?></strong>
          </li>
          <li>
            <?php echo $annualDataLang->consumed;?>
            <strong><?php echo $data['consumed'];?></strong>
          </li>
          <li class='dropdown dropdown-hover'>
            <?php echo $annualDataLang->todos;?>
            <strong><?php echo $data['todos']->count;?></strong>
            <ul class='dropdown-menu pull-right'>
              <li><?php echo $annualDataLang->todos;?></li>
              <li><span class='todoStatus'><?php echo $annualDataLang->todoStatus['all'];?></span><span><?php echo (int)$data['todos']->count;?></span></li>
              <li><span class='todoStatus'><?php echo $annualDataLang->todoStatus['undone'];?></span><span><?php echo (int)$data['todos']->undone;?></span></li>
              <li><span class='todoStatus'><?php echo $annualDataLang->todoStatus['done'];?></span><span><?php echo (int)$data['todos']->done;?></span></li>
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
                  $radarTypes = isset($annualDataConfig['radar'][$objectType][$actionName]) ? $annualDataConfig['radar'][$objectType][$actionName] : array('other');
                  foreach($radarTypes as $radarType) $radarData[$radarType] += $count;
              }
          }
          ?>
          <?php if(!empty($dept) or !empty($userID)):?>
          <li>
            <?php echo $annualDataLang->contributions;?>
            <strong><?php echo $contributions;?></strong>
          </li>
          <?php endif;?>
        </ul>
      </div>
    </section>
    <section id='actionData'>
      <header><h2 class='text-holder'><?php echo ((empty($dept) and empty($userID)) ? $annualDataLang->actionData :$annualDataLang->contributionData) . $soFar;?></h2></header>
      <div>
        <ul>
          <?php foreach($annualDataLang->objectTypeList as $objectType => $objectName):?>
          <li class='dropdown dropdown-hover'>
            <span class='name'><?php echo $objectName;?></span>
            <span class='ratio'>
            <?php
            $objectContributions = isset($data['contributions'][$objectType]) ? $data['contributions'][$objectType] : array();
            $contributionActions = zget($annualDataConfig['contributions'], $objectType, array_keys($objectContributions));
            
            $colors = $annualDataConfig['colors'];
            $detail = "<li><span class='header'>{$objectName}</span></li>";
            foreach($contributionActions as $actionName)
            {
                if(isset($objectContributions[$actionName]))
                {
                    $color = array_shift($colors);
                    $count = $objectContributions[$actionName];
                    $width = floor($count / $maxCount * 100);
                    echo "<span class='item' style='background-color:{$color};width:{$width}%'>{$count}</span>";
            
                    $detail .= "<li><span class='color' style='background-color:{$color}'></span><span class='item-name'>" . $annualDataLang->actionList[$actionName] . "</span><span class='count'>{$count}</span></li>";
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
      <header><h2 class='text-holder'><?php echo $annualDataLang->radar . $soFar;?></h2></header>
      <div id='radarCanvas'></div>
    </section>
    <section id='projectData'>
      <header><h2 class='text-holder'><?php echo $annualDataLang->projects . $soFar;?></h2></header>
      <div class='has-table'>
        <table class='table table-hover table-fixed table-borderless table-condensed'>
          <thead class='hidden'>
            <tr>
              <?php foreach($annualDataLang->projectFields as $field => $name):?>
              <th class='<?php echo "c-$field";?>'><?php echo $name;?></th>
              <?php endforeach?>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['projectStat'] as $project):?>
            <tr>
              <?php foreach($annualDataLang->projectFields as $field => $name):?>
              <td class='<?php echo "c-$field";?>'><?php echo $project->$field;?></td>
              <?php endforeach?>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <div class='table-header-fixed'>
        <table class='table table-hover table-fixed table-borderless table-condensed'>
          <thead>
            <tr>
              <?php foreach($annualDataLang->projectFields as $field => $name):?>
              <th class='<?php echo "c-$field";?>'><?php echo $name;?></th>
              <?php endforeach?>
            </tr>
          </thead>
        </table>
      </div>
    </section>
    <section id='productData'>
      <header><h2 class='text-holder'><?php echo $annualDataLang->products . $soFar;?></h2></header>
      <div class='has-table'>
        <table class='table table-hover table-borderless table-condensed'>
          <thead class='hidden'>
            <tr>
              <?php foreach($annualDataLang->productFields as $field => $name):?>
              <th class='<?php echo "c-$field";?>'><?php echo $name;?></th>
              <?php endforeach?>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['productStat'] as $product):?>
            <tr>
              <?php foreach($annualDataLang->productFields as $field => $name):?>
              <td class='<?php echo "c-$field";?>'><?php echo $product->$field;?></td>
              <?php endforeach?>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <div class='table-header-fixed'>
        <table class='table table-hover table-fixed table-borderless table-condensed'>
          <thead>
            <tr>
              <?php foreach($annualDataLang->productFields as $field => $name):?>
              <th class='<?php echo "c-$field";?>'><?php echo $name;?></th>
              <?php endforeach?>
            </tr>
          </thead>
        </table>
      </div>
      <div class='table-header-fixed'>
    </section>
    <?php if(empty($dept) and empty($userID)):?>
    <section id='allTimeStatusStat'>
      <header><h2 class='text-holder'><?php echo $annualDataLang->statusStat;?></h2></header>
      <div>
        <div class='canvas' id='allStoryStatusCanvas'></div>
        <div class='canvas' id='allTaskStatusCanvas'></div>
        <div class='canvas' id='allBugStatusCanvas'></div>
        <?php
        foreach($data['statusStat'] as $objectType => $objectStatusStat):?>
        <div class='<?php echo $objectType;?>Overview hidden'><?php echo $this->report->getStatusOverview($objectType, $objectStatusStat);?></div>
        <?php endforeach;?>
      </div>
    </section>
    <?php endif;?>
    <?php foreach(array('story', 'task', 'bug', 'case') as $objectType):?>
    <section class='dataYearStat' id='<?php echo $objectType;?>Data'>
      <?php if($objectType == 'story') $sectionHeader = $annualDataLang->stories;?>
      <?php if($objectType == 'task') $sectionHeader = $annualDataLang->tasks;?>
      <?php if($objectType == 'bug') $sectionHeader = $annualDataLang->bugs;?>
      <?php if($objectType == 'case') $sectionHeader = $annualDataLang->cases;?>
      <?php $ucfirst = ucfirst($objectType);?>
      <header><h2 class='text-holder'><?php echo $sectionHeader . $soFar;?></h2></header>
      <div>
        <div class='canvas left' id='<?php echo $objectType == 'case' ?  "yearCaseResultCanvas" : "year{$ucfirst}StatusCanvas";?>'></div>
        <div class='canvas right' id='year<?php echo $ucfirst;?>ActionCanvas'></div>
        <?php if($objectType != 'case'):?>
        <div class='year<?php echo $ucfirst;?>Overview hidden'><?php echo $this->report->getStatusOverview($objectType, $data["{$objectType}Stat"]['statusStat']);?></div>
        <?php endif;?>
      </div>
    </section>
    <?php endforeach;?>
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
          splitArea:{areaStyle:{color: ['#010419']}},
          <?php
          $max = max($radarData);
          $indicator = array();
          foreach($annualDataLang->radarItems as $radarKey => $radarName)
          {
          	$indicator[$radarKey]['name'] = $radarName;
          	$indicator[$radarKey]['max']  = $max;
          }
          ?>
          indicator: <?php echo json_encode(array_values($indicator));?>
      },
      series: [{
          name:'<?php echo $annualDataLang->radar;?>',
          areaStyle:{color: 'rgb(45, 40, 33)'},
          type: 'radar',
          itemStyle: {color: "#fff", borderColor:"rgb(247, 193, 35)"},
          lineStyle: {color: "rgb(247, 193, 35)"},
          data: [{value: <?php echo json_encode(array_values($radarData));?>}]
      }]
    };

    radarChart.setOption(radarOption);

    var overviewCSS = {position: 'absolute', left: '180px', top: '160px'};

    <?php unset($lang->story->statusList['']);?>
    <?php unset($lang->bug->statusList['']);?>
    <?php unset($lang->task->statusList['']);?>
    <?php if(empty($dept) and empty($userID)):?>
    <?php foreach($data['statusStat'] as $objectType => $objectStatusStat):?>
    <?php
    $statusStat = array();
    foreach($lang->$objectType->statusList as $status => $statusName) $statusStat[$status] = array('name' => $statusName, 'value' => zget($objectStatusStat, $status, 0));
    $canvasID         = 'all' . ucfirst($objectType) . 'StatusCanvas';
    $canvasTitleKey   = $objectType . 'StatusStat';
    $jsonedStatus     = json_encode(array_values($lang->$objectType->statusList));
    $jsonedStatusStat = json_encode(array_values($statusStat));
    echo "drawStatusPieChart('{$canvasID}', '{$annualDataLang->$canvasTitleKey}', $jsonedStatus, $jsonedStatusStat,
        function()
        {
            $('#allTimeStatusStat .{$objectType}Overview').appendTo('#{$canvasID}').removeClass('hidden').css(overviewCSS)
        });\n";
    ?>
    <?php endforeach;?>
    <?php endif;?>

    var yearOverviewCSS = {position: 'absolute', left: '200px', top: '160px'};
    <?php foreach(array('story', 'task', 'bug', 'case') as $objectType):?>
    <?php
    $stat     = array();
    $items = $objectType == 'case' ? $lang->testcase->resultList : $lang->$objectType->statusList;
    $statKey = $objectType == 'case' ? 'resultStat' : 'statusStat';
    foreach($items as $key => $name)
    {
        $itemCount  = zget($data["{$objectType}Stat"][$statKey], $key, 0);
        $stat[$key] = array('name' => $name, 'value' => $itemCount);
    }

    $ucfirst        = ucfirst($objectType);
    $canvasID       = $objectType == 'case' ? 'yearCaseResultCanvas' : 'year' . $ucfirst . 'StatusCanvas';
    $canvasTitleKey = $objectType == 'case' ? 'caseResultStat' : $objectType . 'StatusStat';
    $jsonedItems    = json_encode(array_values($items));
    $jsonedStat     = json_encode(array_values($stat));

    $drawFunction = "drawStatusPieChart('{$canvasID}', '{$annualDataLang->$canvasTitleKey}', $jsonedItems, $jsonedStat";
    if($objectType != 'case')
    {
        $drawFunction .= ", function()
        {
            $('#{$objectType}Data .year{$ucfirst}Overview').appendTo('#{$canvasID}').removeClass('hidden').css(yearOverviewCSS)
        }";
    }
    $drawFunction .= ");\n";

    echo $drawFunction;
    ?>
    <?php endforeach;?>

    <?php
    $commonTemplate['name']  = '';
    $commonTemplate['type']  = 'bar';
    $commonTemplate['stack'] = 'all';
    $commonTemplate['label'] = array('show' => false);
    $commonTemplate['data']  = array();
    
    $jsonedMonths = json_encode($months);
    foreach($annualDataConfig['month'] as $objectType => $actions):?>
    <?php
    $legends = array();
    $monthActions = array();
    foreach($actions as $actionKey => $action)
    {
        if(!isset($data["{$objectType}Stat"]['actionStat'][$actionKey])) continue;

        $actionName = $annualDataLang->actionList[$action];
        $legends[]  = $actionName;

        $monthAction = $commonTemplate;
        $monthAction['name']  = $actionName;
        $monthAction['stack'] = $objectType;
        $monthAction['data']  = array_values($data["{$objectType}Stat"]['actionStat'][$actionKey]);
        $monthActions[]       = $monthAction;
    }
    $canvasID = 'year' . ucfirst($objectType) . 'ActionCanvas';
    $canvasTitleKey = $objectType . 'MonthActions';
    $legends = json_encode($legends);
    $monthActions = json_encode($monthActions);
    echo "drawMonthsBarChart('{$canvasID}', '{$annualDataLang->$canvasTitleKey}', {$legends}, {$jsonedMonths}, {$monthActions});\n";
    ?>
    <?php endforeach;?>
})
</script>
<?php include '../../common/view/footer.lite.html.php';?>
