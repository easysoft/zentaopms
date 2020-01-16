<?php include '../../common/view/header.lite.html.php';?>
<?php js::import($jsRoot . 'html2canvas/min.js'); ?>
<div id='container' style='background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/annual_data_bg.png'?>)'>
  <main id='main'>
    <div id='mainBg' style='background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/annual_data_layout.png'?>)'></div>
    <header id='header'>
      <h1 class='text-holder' data-id='title'></h1>
    </header>
    <section id='block1'>
      <header><h2 class='text-holder' data-id='block1.title'></h2></header>
      <div><ul id='block1List'></ul></div>
    </section>
    <section id='block2'>
      <header><h2 class='text-holder' data-id='block2.title'></h2></header>
      <div id='block2Chart' class='progress-pie inline-block space progress-pie-200' data-value='0' data-doughnut-size='70' data-color='#186bb1' data-back-color='#84cff0' data-show-tip='true'>
        <canvas width='180' height='180' style='width: 180px; height: 180px;'></canvas>
        <div class='progress-info'>
          <strong><span class='text-holder' data-id='block2.dataTotal'></span><small class='text-holder' data-id='block2.unit'></small></strong>
        </div>
      </div>
      <div id='block2ListWrapper'><ul id='block2List'></ul></div>
    </section>
    <section id='block3'>
      <table class='table' id='block3TableHeader'>
        <thead>
          <tr></tr>
        </thead>
      </table>
      <div class='table-wrapper'>
        <table class='table' id='block3Table'>
        </table>
      </div>
    </section>
    <section id='block4'>
      <header>
        <h2 class='text-holder' data-id='block4.title'></h2>
      </header>
      <div class='row'>
        <div class='col-xs-6'>
          <div id='block4chart1' class='progress-pie inline-block space progress-pie-200' data-value='0' data-doughnut-size='80' data-color='#186bb1'>
            <canvas width='160' height='160' style='width: 160px; height: 160px;'></canvas>
            <div class='progress-info'>
              <p class='text-holder' data-id='block4.chart1.title'></p>
              <strong><span class='text-holder' data-id='block4.chart1.total'></span><small class='text-holder' data-id='block4.chart1.unit'></small></strong>
            </div>
          </div>
          <ul class='clearfix' id='block4chart1Info'>
          </ul>
        </div>
        <div class='col-xs-6'>
          <div id='block4chart2' class='progress-pie inline-block space progress-pie-160' data-value='0' data-doughnut-size='80' data-color='#186bb1'>
            <canvas width='160' height='160' style='width: 160px; height: 160px;'></canvas>
            <div class='progress-info'>
              <p class='text-holder' data-id='block4.chart2.title'></p>
              <strong><span class='text-holder' data-id='block4.chart2.total'></span><small class='text-holder' data-id='block4.chart2.unit'></small></strong>
            </div>
          </div>
          <ul class='clearfix' id='block4chart2Info'>
          </ul>
        </div>
      </div>
    </section>
    <section id='block5'>
      <header>
        <h2 class='text-holder' data-id='block5.title'></h2>
        <div id='block5Legend'></div>
      </header>
      <canvas id='block5Chart' width='520' height='240'></canvas>
    </section>
    <div id='toolbar'>
      <?php echo html::select('year', $years, $year, "class='form-control'");?>
      <button type='button' class='btn btn-primary' id='exportBtn' title='<?php echo $lang->export;?>'><i class='icon icon-share'></i></button>
      <a id='imageDownloadBtn' class='hidden' download='annual_data.png'></a>
    </div>
  </main>
  <div id='loadIndicator' class='load-indicator'></div>
</div>
<script>
var annualData =
{
    title: '<?php echo $title;?>',

    <?php foreach($config->report->annualData[$role] as $blockKey => $blockConfig):?>
    <?php echo $blockKey;?>:
    {
        <?php if(!empty($blockConfig['title'])):?>
        title: '<?php echo $lang->report->annualData->{$blockConfig['title']};?>',
        <?php endif;?>
        <?php
        $blockData = array();
        if($blockKey == 'block1')
        {
            foreach($blockConfig['data'] as $name) $blockData[] = array('title' => $lang->report->annualData->{$name}, 'value' => $data[$name]);
            echo "data :" . json_encode($blockData);
        }
        elseif($blockKey == 'block2')
        {
            echo "unit: '" . $lang->report->annualData->unit . "',";
            if($role == 'dev')
            {
                $count = $data['projectStat']['count'];
                $blockData[] = array('title' => $lang->report->annualData->doneProject, 'value' => $data['projectStat']['done'], 'percent' => (empty($count) ? 0 : round($data['projectStat']['done'] / $count, 4) * 100 . '%'));
                $blockData[] = array('title' => $lang->report->annualData->doingProject, 'value' => $data['projectStat']['doing'], 'percent' => (empty($count) ? 0 : round($data['projectStat']['doing'] / $count, 4) * 100 . '%'));
                $blockData[] = array('title' => $lang->report->annualData->suspendProject, 'value' => $data['projectStat']['suspended'], 'percent' => (empty($count) ? 0 : round($data['projectStat']['suspended'] / $count, 4) * 100 . '%'));
                echo "data: " . json_encode($blockData);
            }
            elseif($role == 'po')
            {
                foreach($data['productStat'] as $productStat)
                {
                    $count = $productStat['count'];
                    $blockData[] = array('title' => $productStat['name'], 'value' => (int)$productStat['mine'], 'percent' => (empty($count) ? '0' : round($productStat['mine'] / $count, 3) * 100 . '%'));
                }
                echo "data: " . json_encode($blockData);
            }
            elseif($role == 'qa')
            {
                foreach($data['productStat'] as $productStat)
                {
                    $count = $productStat['count'];
                    $blockData[] = array('title' => $productStat['name'], 'value' => (int)$productStat['mine'], 'percent' => (empty($count) ? '0' : round($productStat['mine'] / $count, 3) * 100 . '%'));
                }
                echo "data: " . json_encode($blockData);
            }
        }
        elseif($blockKey == 'block3')
        {
            $cols = array();
            $rows = array();
            if($role == 'po')
            {
                $cols[] = array('title' => $lang->report->annualData->productName, 'width' => 'auto', 'align' => 'left');
                $cols[] = array('title' => $lang->report->annualData->planCount, 'width' => '80', 'align' => 'center');
                $cols[] = array('title' => $lang->report->annualData->storyCount, 'width' => '80', 'align' => 'center');

                foreach($data['products'] as $product) $rows[] = array($product->name, $product->plans, $product->stories);
            }
            elseif($role == 'dev')
            {
                $cols[] = array('title' => $lang->report->annualData->projectName, 'width' => 'auto', 'align' => 'left');
                $cols[] = array('title' => $lang->report->annualData->finishedStory, 'width' => '80', 'align' => 'center');
                $cols[] = array('title' => $lang->report->annualData->finishedTask, 'width' => '80', 'align' => 'center');
                $cols[] = array('title' => $lang->report->annualData->resolvedBug, 'width' => '80', 'align' => 'center');

                foreach($data['projects'] as $project) $rows[] = array($project->name, $project->stories, $project->tasks, $project->bugs);
            }
            elseif($role == 'qa')
            {
                $cols[] = array('title' => $lang->report->annualData->productName, 'width' => 'auto', 'align' => 'left');
                $cols[] = array('title' => $lang->report->annualData->foundBug, 'width' => '80', 'align' => 'center');

                foreach($data['products'] as $product) $rows[] = array($product->name, $product->bugs);
            }

            echo "cols: " . json_encode($cols) . ',';
            echo "rows: " . json_encode($rows);
        }
        elseif($blockKey == 'block4')
        {
            if($role == 'qa')
            {
                foreach($blockConfig['data'] as $i => $name)
                {
                    $totalData = array();
                    foreach($data[$name] as $pri => $value) $totalData[] = array('title' => $pri, 'value' => $value, 'legend' => 'P' . $pri);

                    if($name == 'bugPri')  $title = $lang->report->annualData->totalCreatedBug;
                    if($name == 'casePri') $title = $lang->report->annualData->totalCreatedCase;
                    $chart = array('title' => $title, 'unit' => $lang->report->annualData->unit, 'data' => $totalData);
                    echo "chart" . ($i + 1) . ': ' . json_encode($chart) . ',';
                }
            }
            elseif($role == 'po')
            {
                $this->app->loadLang('story');
                foreach($blockConfig['data'] as $i => $name)
                {
                    $totalData = array();
                    foreach($data[$name] as $key => $value)
                    {
                        $legend = 'P' . $key;
                        if($name == 'storyStage')
                        {
                            $legend = zget($lang->story->stageList, $key);
                            if(empty($legend)) $legend = 'NULL';
                        }
                        $totalData[] = array('title' => $key, 'value' => $value, 'legend' => $legend);
                    }

                    if($name == 'storyPri')   $title = $lang->report->annualData->totalStoryPri;
                    if($name == 'storyStage') $title = $lang->report->annualData->totalStoryStage;
                    $chart = array('title' => $title, 'unit' => $lang->report->annualData->unit, 'data' => $totalData);
                    echo "chart" . ($i + 1) . ': ' . json_encode($chart) . ',';
                }
            }
            elseif($role == 'dev')
            {
                foreach($blockConfig['data'] as $i => $name)
                {
                    $totalData = array();
                    foreach($data[$name] as $key => $value) $totalData[] = array('title' => $key, 'value' => $value, 'legend' => 'P' . $key);

                    if($name == 'finishedTaskPri') $title = $lang->report->annualData->totalFinishedTask;
                    if($name == 'resolvedBugPri')  $title = $lang->report->annualData->totalResolvedBug;
                    $chart = array('title' => $title, 'unit' => $lang->report->annualData->unit, 'data' => $totalData);
                    echo "chart" . ($i + 1) . ': ' . json_encode($chart) . ',';
                }
            }
        }
        elseif($blockKey == 'block5')
        {
            echo 'labels:' . json_encode($lang->datepicker->monthNames) . ',';

            if($role == 'qa')
            {
                $datasets = array();
                foreach($blockConfig['data'] as $i => $name)
                {
                    if($name == 'bugMonth')  $label = $lang->report->annualData->totalCreatedBug;
                    if($name == 'caseMonth') $label = $lang->report->annualData->totalCreatedCase;
                    $datasets[] = array('label' => $label, 'data' => $data[$name]);
                }
            }
            elseif($role == 'po')
            {
                $datasets = array();
                foreach($blockConfig['data'] as $i => $name)
                {
                    if($name == 'storyMonth')  $label = $lang->report->annualData->poStatistics;
                    $datasets[] = array('label' => $label, 'data' => $data[$name]);
                }
            }
            elseif($role == 'dev')
            {
                $datasets = array();
                foreach($blockConfig['data'] as $i => $name)
                {
                    if($name == 'effortMonth')
                    {
                        $label = $lang->report->annualData->totalConsumed;
                        foreach($data[$name] as $month => $consumed) $data[$name][$month] = round($consumed, 2);
                    }
                    if($name == 'bugMonth') $label = $lang->report->annualData->totalResolvedBug;
                    if($name == 'taskMonth')$label = $lang->report->annualData->totalFinishedTask;
                    $datasets[] = array('label' => $label, 'data' => $data[$name]);
                }
            }
            echo "datasets: " . json_encode($datasets);
        }
        ?>
    },
    <?php endforeach;?>
};

$(function()
{
    showAnnualData(annualData);
});
</script>
<?php include '../../common/view/footer.lite.html.php';?>
