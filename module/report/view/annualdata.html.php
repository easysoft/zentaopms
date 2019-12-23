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
      <button type='button' class='btn btn-primary' id='exportBtn'><i class='icon icon-export'></i></button>
      <a id='imageDownloadBtn' class='hidden' download='annual_data.png'></a>
    </div>
  </main>
  <div id='loadIndicator' class='load-indicator'></div>
</div>
<script>
// 年度数据示例
var annualData =
{
    // 大标题
    title: '2019年工作内容统计一览表 ── XXX',

    // 区块1
    block1:
    {
        title: '基本数据信息',
        data:
        [
            {title: '累计登录次数', value: 344},
            {title: '累计动态数', value: 1543},
            {title: '累计日志数', value: 629},
            {title: '累计工时数', value: 772}
        ]
    },

    // 区块2
    block2:
    {
        title: '参与项目概览',
        data:
        [
            {title: '已完成的项目', value: 20, percent: '71.4%'},
            {title: '正在进行的项目', value: 7, percent: '25%'},
            {title: '已挂起的项目', value: 1, percent: '3.5%'}
        ],
        unit: '个'
    },

    // 区块3
    block3:
    {
        cols:
        [
            {title: '项目名称', width: 'auto', align: 'left'},
            {title: '完成需求数', width: 80, align: 'center'},
            {title: '完成任务数', width: 80, align: 'center'},
            {title: '解决bug数', width: 72, align: 'center'}
        ],
        rows:
        [
            ['项目名称一', 22, 2, '0%'],
            ['项目名称二', 22, 2, '0%'],
            ['项目名称三', 22, 2, '0%'],
            ['项目名称四', 22, 2, '0%'],
            ['项目名称五', 22, 2, '0%'],
            ['项目名称六', 22, 2, '0%'],
            ['项目名称七', 22, 2, '0%'],
            ['项目名称八', 22, 2, '0%'],
            ['项目名称九', 22, 2, '0%'],
            ['项目名称十', 22, 2, '0%'],
            ['项目名称十一', 22, 2, '0%'],
            ['项目名称十二', 22, 2, '0%']
        ]
    },

    // 区块4
    block4:
    {
        title: '完成任务与解决bug数据',
        chart1:
        {
            title: '累计完成任务数',
            unit: '个',
            data:
            [
                // legend 属性可选，如果留空，则不再下方的图例上显示标题
                {value: 42, title: '优先级1', legend: 'P1'},
                {value: 172, title: '优先级2', legend: 'P2'},
                {value: 212, title: '优先级3', legend: 'P3'},
                {value: 86, title: '优先级4', legend: 'P4'}
            ],
        },
        chart2:
        {
            title: '累计修复bug数',
            unit: '个',
            data:
            [
                {value: 42, title: '优先级1'},
                {value: 32, title: '优先级2'},
                {value: 20, title: '优先级3'},
                {value: 34, title: '优先级4'}
            ],
        }
    },

    block5:
    {
        title: '完成任务与解决bug工时统计',
        labels: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月'],
        datasets:
        [
            {
                label: '完成任务累计工时',
                data: [110, 154, 184, 220, 212, 250, 130, 146, 201, 89, 140, 59],
            },
            {
                label: '解决bug累计工时',
                data: [10, 5, 11, 20, 30, 14, 3, 20, 50, 27, 13, 3],
            }
        ]
    },
};

// 显示年度数据
$(function()
{
    showAnnualData(annualData);
});
</script>
<?php include '../../common/view/footer.lite.html.php';?>
