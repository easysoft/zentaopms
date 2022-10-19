<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js'); ?>
<?php js::import($jsRoot . 'html2canvas/min.js'); ?>
<?php $annualDataLang   = $lang->report->annualData;?>
<?php $annualDataConfig = $config->report->annualData;?>
<?php $soFar = sprintf($annualDataLang->soFar, $year);?>
<?php js::set('contributionGroups', $contributionGroups); ?>
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
            <?php echo $userID ? $annualDataLang->logins : ($dept !== '' ? $annualDataLang->deptUsers : $annualDataLang->companyUsers);?>
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
          <?php if($dept !== '' or !empty($userID)):?>
          <li>
            <?php echo $annualDataLang->contributions;?>
            <strong><?php echo $contributions;?></strong>
          </li>
          <?php endif;?>
        </ul>
      </div>
    </section>
    <section id='actionData'>
      <header><h2 class='text-holder'><?php echo (($dept === '' and empty($userID)) ? $annualDataLang->actionData :$annualDataLang->contributionData) . $soFar;?></h2></header>
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
            $detail = '';
            $items  = array();
            $maxWidth      = 0;
            $maxWidthColor = '';
            $allPercent    = 0;
            foreach($contributionActions as $actionName)
            {
                if($maxCount == 0) continue;
                if(isset($objectContributions[$actionName]))
                {
                    $color = array_shift($colors);
                    $count = $objectContributions[$actionName];
                    if($count == 0) continue;

                    $width = floor($count / $maxCount * 100);
                    if($width == 0) $width = 1;
                    $length = strlen($count);
                    if($width < $annualDataConfig['itemMinWidth'][$length]) $width = $annualDataConfig['itemMinWidth'][$length];

                    $allPercent += $width;
                    if($maxWidth < $width)
                    {
                        $maxWidth      = $width;
                        $maxWidthColor = $color;
                    }

                    $item['color'] = $color;
                    $item['width'] = $width;
                    $item['count'] = $count;
                    $items[$color] = $item;

                    $detail .= "<li><span class='color' style='background-color:{$color}'></span><span class='item-name'>" . $annualDataLang->actionList[$actionName] . "</span><span class='count'>{$count}</span></li>";
                }
            }
            if($allPercent > 100) $items[$maxWidthColor]['width'] = $items[$maxWidthColor]['width'] - ($allPercent - 100);
            if($detail) $detail = "<li><span class='header'>{$objectName}</span></li>" . $detail;
            foreach($items as $item) echo "<span class='item' style='background-color:{$item['color']};width:{$item['width']}%'>{$item['count']}</span>";
            ?>
            </span>
            <?php if($detail):?>
            <ul class='dropdown-menu'><?php echo $detail;?></ul>
            <?php endif;?>
          </li>
          <?php endforeach;?>
        </ul>
      </div>
    </section>
    <section id='radar'>
      <header><h2 class='text-holder'><?php echo $annualDataLang->radar . $soFar;?></h2></header>
      <div id='radarCanvas' class="hidden"></div>
      <div class="scroll-canvas hidden">
        <img alt="" id="showImg">
        <i class="icon icon-play" id="stopPlaying"></i>
        <ul id="timeline" ref="timeline" class="scroll"></ul>
      </div>
        
    </section>
    <section id='executionData'>
      <header><h2 class='text-holder'><?php echo $annualDataLang->executions . $soFar;?></h2></header>
      <div class='has-table'>
        <table class='table table-hover table-fixed table-borderless table-condensed'>
          <thead class='hidden'>
            <tr>
              <?php foreach($annualDataLang->executionFields as $field => $name):?>
              <th class='<?php echo "c-$field";?>'><?php echo $name;?></th>
              <?php endforeach?>
            </tr>
          </thead>
          <tbody>
            <?php foreach($data['executionStat'] as $execution):?>
            <tr>
              <?php foreach($annualDataLang->executionFields as $field => $name):?>
              <td class='<?php echo "c-$field";?>' title='<?php echo $execution->$field;?>'><?php echo $execution->$field;?></td>
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
              <?php foreach($annualDataLang->executionFields as $field => $name):?>
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
              <td class='<?php echo "c-$field";?>' title='<?php echo $product->$field;?>'><?php echo $product->$field;?></td>
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
    <?php if($dept === '' and empty($userID)):?>
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
    <?php
    $objectTypeList['story'] = $radarData['product'];
    $objectTypeList['task']  = $radarData['execution'] > $radarData['devel'] ? $radarData['execution'] : $radarData['devel'];
    $objectTypeList['bug']   = $radarData['qa'];
    $objectTypeList['case']  = $radarData['qa'];
    arsort($objectTypeList);
    ?>
    <?php foreach(array_keys($objectTypeList) as $objectType):?>
    <section class='dataYearStat' id='<?php echo $objectType;?>Data'>
      <?php if($objectType == 'story') $sectionHeader = $annualDataLang->stories;?>
      <?php if($objectType == 'task')  $sectionHeader = $annualDataLang->tasks;?>
      <?php if($objectType == 'bug')   $sectionHeader = $annualDataLang->bugs;?>
      <?php if($objectType == 'case')  $sectionHeader = $annualDataLang->cases;?>
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
<?php echo js::set('exportByZentao', $annualDataLang->exportByZentao);?>
<script>
$(function()
{
    var contributionData = [];
    if(contributionGroups) {
        for(var contributionKey in contributionGroups)
        {
            var contributionItem = {
                year: contributionKey,
                data: [],
                img: ''
            }
            for(var itemKey in contributionGroups[contributionKey])
            {
                contributionItem.data.push(contributionGroups[contributionKey][itemKey]);
            }
            contributionData.push(contributionItem);
        }
    }
    if(contributionData.length > 1)
    {
        $('.scroll-canvas').removeClass('hidden');
        for(var k = 0; k < contributionData.length; k++)
        {
            contributionData[k].img = renderCanvasImg([{value: contributionData[k].data}], true, k);
        }
    }
    else 
    {
        $('#radarCanvas').removeClass('hidden');
        renderCanvasImg([{value: <?php echo json_encode(array_values($radarData));?>}], false)
    }
    
    function renderCanvasImg(paramsData, renderImg, index)
    {  
        var radarChart = null;
        if(renderImg)
        {
            var canvas = document.createElement('div');
            canvas.id = 'canvas' + index;
            canvas.style.width = '300px';
            canvas.style.height = '300px';
            canvas.style.display = 'none';
            radarChart = echarts.init(canvas);
        }
        else 
        {
            radarChart = echarts.init(document.getElementById('radarCanvas'));
        }
        var radarOption = {
        tooltip: {},
        radar: {
            splitArea:{areaStyle:{color: ['#010419']}},
            radius:'65%',
            <?php
            $max = max($radarData);
            if($max == 0) $max = 1;
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
            data: paramsData
        }]
        };
        radarChart.setOption(radarOption);
        if(renderImg)
        {
            var radarCanvasimg = radarChart.getDataURL({
                type: 'png',
                PixelRatio: 2,
            });
            return radarCanvasimg;
        }
    }
    
    var index = 0;
    var timer = null;
    contributionData.map(function(item)
    {
        var createLi = document.createElement('li');
        var createP = document.createElement('p');
        var createImg = document.createElement('img');
        createImg.src = item.img;
        createImg.style.display = 'none';
        createP.innerHTML = contributionData.length < 10 ? item.year : item.year.substring(item.year.length - 2, item.year.length);;
        createLi.appendChild(createP);
        createLi.appendChild(createImg);
        timeline.appendChild(createLi);
    });
    
    $('#timeline li')[0].classList.add('selecteded');
    $('#showImg')[0].src = $('#timeline li img')[0].src;
    $('#timeline').on('click', function(e) {
        var liData = $('#timeline li');
        var pData = $('#timeline li p');
        var event = e || window.event;
        var target = event.target || event.srcElement;
        if (['P', 'LI'].includes(target.tagName) ) {  
            classChange(liData, target, target.tagName == 'LI');
            for (var i = 0; i < liData.length; i++) {
                if ($(liData[i])[0].classList.contains('selecteded')) {
                    if(target.tagName == 'LI')
                    {
                        $(liData[i]).prevAll().addClass('active');
                    }
                    else {
                        $(liData[i]).parent().prevAll().addClass('active');
                    }
                    $('#showImg')[0].src = $(liData[i]).find('img')[0].src;
                    index = i;
                    break;
                }
        
            }
        }
    });
   
    function classChange(liData, target, isParent)
    {
        for (var i = 0; i < liData.length; i++) {
            $(liData[i]).removeClass('selecteded');
        }
        // isParent ? $(target).addClass('selecteded') : $(target).parent().addClass('selecteded');
        if(isParent)
        {
            $(target).addClass('selecteded');
            $(target).prevAll().addClass('active');
        }
        else {
            $(target).parent().addClass('selecteded');
            $(target).parent().prevAll().addClass('active');
        }
    }
    if ($('#stopPlaying'))
    {
        $('#stopPlaying').on('click', function()
        {
            if ($('#stopPlaying')[0].classList.contains('icon-play'))
            {
                $('#stopPlaying').removeClass('icon-play');
                $('#stopPlaying').addClass('icon-pause');
                if (!timer) {
                    autoPlay();
                }
            }
            else 
            {
                $('#stopPlaying').removeClass('icon-pause');
                $('#stopPlaying').addClass('icon-play');
                if (timer)
                {
                    timer = clearInterval(timer);
                }
                else
                {
                    return
                }
            }
        });
    }
 
    function autoPlay()
    {
        var liData = $('#timeline li');
        var pData = $('#timeline li p');
        timer = setInterval(function()
        {
            if (index < pData.length - 1)
            {
                classChange(liData, pData[index + 1]);
                $('#showImg')[0].src = $('#timeline li img')[index].src;
                index++;
            }
            else 
            {
                index = 0;               
                classChange(liData, pData[index]);
                $('#stopPlaying').removeClass('icon-pause');
                $('#stopPlaying').addClass('icon-play');
                $('#timeline li').removeClass('active');
                clearInterval(timer);
            }
        }, 1000);
    }

    var overviewCSS = {position: 'absolute', left: '172px', top: '160px'};

    <?php unset($lang->story->statusList['']);?>
    <?php unset($lang->bug->statusList['']);?>
    <?php unset($lang->task->statusList['']);?>
    <?php if($dept === ''  and empty($userID)):?>
    <?php foreach($data['statusStat'] as $objectType => $objectStatusStat):?>
    <?php
    $statusStat = array();
    foreach($lang->$objectType->statusList as $status => $statusName)
    {
        $statusCount = zget($objectStatusStat, $status, 0);
        if($statusCount == 0) continue;
        $statusStat[$status] = array('name' => $statusName, 'value' => $statusCount);
    }
    $canvasID         = 'all' . ucfirst($objectType) . 'StatusCanvas';
    $canvasTitleKey   = $objectType . 'StatusStat';
    $jsonedStatusStat = json_encode(array_values($statusStat));
    echo "drawStatusPieChart('{$canvasID}', '{$annualDataLang->$canvasTitleKey}', $jsonedStatusStat,
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
        if($itemCount == 0) continue;
        $stat[$key] = array('name' => $name, 'value' => $itemCount);
    }

    $ucfirst        = ucfirst($objectType);
    $canvasID       = $objectType == 'case' ? 'yearCaseResultCanvas' : 'year' . $ucfirst . 'StatusCanvas';
    $canvasTitleKey = $objectType == 'case' ? 'caseResultStat' : $objectType . 'StatusStat';
    $jsonedStat     = json_encode(array_values($stat));

    $drawFunction = "drawStatusPieChart('{$canvasID}', '{$annualDataLang->$canvasTitleKey}', $jsonedStat";
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
    $legends      = array();
    $monthActions = array();
    $allCount     = 0;
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

        $allCount += array_sum($monthAction['data']);
    }

    if($allCount == 0)
    {
        $monthActions = array();
        $legends = array();
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
