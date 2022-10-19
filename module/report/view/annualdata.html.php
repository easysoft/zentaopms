<?php include '../../common/view/header.lite.html.php';?>
<?php include '../../common/view/chosen.html.php';?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js'); ?>
<?php js::import($jsRoot . 'html2canvas/min.js'); ?>
<?php $annualDataLang   = $lang->report->annualData;?>
<?php $annualDataConfig = $config->report->annualData;?>
<?php $soFar = sprintf($annualDataLang->soFar, $year);?>
<?php js::set('totalYears', (array)($years)); ?>
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
      <div id='radarCanvas' style="display: none;"></div>
      <canvas id="canvas" width="330" height="280" style="margin-top:-30px"></canvas>
        <div class="scroll-shell">
            <i class="icon icon-play" id="stopPlaying"></i>
            <ul id="timeline" ref="timeline" onclick="timeline($event)" class="scroll"></ul>
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
    var radarChart  = echarts.init(document.getElementById('radarCanvas'));
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
          data: [{value: <?php echo json_encode(array_values($radarData));?>}]
      }]
    };
    console.log(totalYears);

    radarChart && radarChart.setOption(radarOption);

    function exportImg () 
    {
        var radarCanvasArr = echarts.getInstanceByDom(document.getElementById('radarCanvas'));
        if(!radarCanvasArr)
        {
            radarCanvasArr = echarts.init(document.getElementById('radarCanvas'));
        }
        var radarCanvasimg = radarCanvasArr.getDataURL({
            type: 'png',
            PixelRatio: 1.5,
        });
        canvasImg = radarCanvasimg;
        pngimages.push(canvasImg);
        
    }

    
    var canvasImg = '';
    var pngimages = [];
    exportImg();
    setInterval(function(){exportImg();}, 1000);

    var cStream, recorder, chunks = [];
    function saveChunks(e)
    {
        chunks.push(e.data);
    }
    function stopRecording()
    {
        recorder.stop();
    }

    function exportStream(e)
    {
        var blob = new Blob(chunks);
        var vidURL = URL.createObjectURL(blob);
        var canvasVideo = document.createElement('video');
        canvasVideo.controls = true;
        canvasVideo.src = vidURL;
        canvasVideo.onended = function() {
            URL.revokeObjectURL(vidURL);
        }
        document.body.insertBefore(canvasVideo, canvas);
    }

    var x = 0;
    var ctx = canvas.getContext('2d');

    var left = 0;
  
    var animationCanvas = function() {
        x = (x + 2) % (canvas.width + 100);
        ctx.fillStyle = '#01061b';
       
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        var img=document.createElement("img");
        img.src = canvasImg;
        ctx.drawImage(img, 10, 10);
        ctx.fillStyle = 'red';
        // ctx.fillRect(x - 50, 20, 50, 50);
        
        rafId = requestAnimationFrame(animationCanvas);
       

    };
    animationCanvas();
    var years = [];
    for(var key in totalYears){
        years.push(totalYears[key]);
    }
    var radarCanvasDom = document.getElementById('radarCanvas');
    if(years.length > 1) {
        radarCanvasDom.style.display = 'none';
    }
    
    
    // var years = [2016, 2017, 2018, 2019, 2020, 2021, 2022]
    var index = 0
    var timer=null
    //创建时间轴对应的li
    years.map(k => {
        var createLi = document.createElement('li')
        var createP = document.createElement('p')
        createP.innerHTML = k
        createLi.appendChild(createP)
        timeline.appendChild(createLi)
    })
    //默认选中第一个
    var timelines = document.querySelectorAll('#timeline li');
    timelines[0].classList.add('selecteded');
    var ps = document.querySelectorAll('#timeline li p');
    ps[0].classList.add('class1');
 
    //点击事件,点击其中一个切换到相应的效果
    var ulElement = document.querySelector('#timeline');
    ulElement.onclick = function(e) {
    var lis = document.querySelectorAll('#timeline li');
    var ps = document.querySelectorAll('#timeline li p');
    var event = e || window.event;
    var target = event.target || event.srcElement;
    if (target.tagName == 'P') {  
        classChange(ps, lis, target);
        for (var i = 0; i < lis.length; i++) {
            if (lis[i].getAttribute('class') == 'selecteded') {
                //记住此时被点击的索引,方便点击播放按钮时继续播放
                index = i;
                console.log(index);
                break;
            }
    
        }
    }
   }
   
   //公共部分,清除掉所有的样式,再给点击的添加相应的类名
    function classChange(ps, lis, target) {
        ps.forEach(k => {
            k.classList.remove('class1');
        })
        target.classList.add('class1');
        lis.forEach(v => {
            v.classList.remove('selecteded');
        })
        target.parentNode.classList.add('selecteded');
    }
 
    //播放和暂停按钮
    var stopPlaying = document.getElementById('stopPlaying');
    if (stopPlaying)
    {
        stopPlaying.onclick = () => {
            if (stopPlaying.className.indexOf('play') != -1)
            {
                stopPlaying.classList.remove('icon-play');
                stopPlaying.classList.add('icon-pause');
                if (!timer) {
                    autoPlay();
                }
            }
            else 
            {
                console.log('clearInterval');
                stopPlaying.classList.remove('icon-pause');
                stopPlaying.classList.add('icon-play');
                if (timer)
                {
                    timer = clearInterval(timer)
                }
                else
                {
                    return
                }
            }
        }
    }
 
   //自动播放
   function autoPlay()
   {
        var lis = document.querySelectorAll('#timeline li');
        var ps = document.querySelectorAll('#timeline li p');
        timer = setInterval(() => {
            if (index < ps.length - 1) {
                classChange(ps, lis, ps[index + 1]);
                index++;
            } else {
                //跳转到开始
                index = 0;               
                classChange(ps, lis, ps[index]);
                stopPlaying.classList.remove('icon-pause');
                stopPlaying.classList.add('icon-play');
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
// <script type="text/javascript" src="./js/processor.js">
</script>
<?php include '../../common/view/footer.lite.html.php';?>
