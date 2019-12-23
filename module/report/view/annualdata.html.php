<?php include '../../common/view/header.lite.html.php';?>
<div id='container' style='background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/annual_data_bg.png'?>)'>
  <main id="main" style='background-image: url(<?php echo $config->webRoot . 'theme/default/images/main/annual_data_layout.png'?>)'>
    <header id='header'>
      <h1>2019年工作内容统计一览表 ── <span class='text-holder' data-id='username'>XXX</span></h1>
    </header>
    <section id='basic'>
      <header>基本数据信息</header>
      <ul>
        <li>累计登录次数 <strong class='text-holder' data-id='totalLoginTimes'>344</strong></li>
        <li>累计动态数 <strong class='text-holder' data-id='totalDynamicCount'>1543</strong></li>
        <li>累计日志数 <strong class='text-holder' data-id='totalLogCount'>629</strong></li>
        <li>累计工时数 <strong class='text-holder' data-id='totalWrokHours'>772</strong></li>
      </ul>
    </section>
    <section id='projectsSummary'>
      <header>参与项目概览</header>
      <div id='projectsSummaryChart' class='progress-pie inline-block space progress-pie-200' data-value='0' data-doughnut-size='70' data-color='#186bb1' data-back-color='#84cff0' data-labels='已完成的项目,正在进行的项目,已挂起的项目' data-show-tip='true' data-tooltip-template= "<%=value%>">
        <canvas width='180' height='180' style='width: 180px; height: 180px;'></canvas>
        <div class='progress-info'>
          <strong><span class='text-holder' data-id='totalProjectsCount'></span><small>个</small></strong>
        </div>
      </div>
      <ul>
        <li><span class='dot'></span> 已完成的项目 <div><span><span class='text-holder' data-id='finishProjects'>20</span><small>个</small></span><span><span class='text-holder' data-id='finishProjectsPercent'>71.4</span><small>%</small></span></div></li>
        <li><span class='dot'></span> 正在进行的项目 <div><span><span class='text-holder' data-id='activateProjects'>7</span><small>个</small></span><span><span class='text-holder' data-id='activateProjectsPercent'>25</span><small>%</small></span></div></li>
        <li><span class='dot'></span> 已挂起的项目 <div><span><span class='text-holder' data-id='suspendProjects'>1</span><small>个</small></span><span><span class='text-holder' data-id='suspendProjectsPercent'>3.5</span><small>%</small></span></div></li>
      </ul>
    </section>
    <section id='projectsList'>
      <table class='table' id='projectsTableHeader'>
        <thead>
          <tr>
            <th class='col-name'>项目名称</th>
            <th class='col-storyCount'>完成需求数</th>
            <th class='col-taskCount'>完成任务数</th>
            <th class='col-bugCount'>解决bug数</th>
          </tr>
        </thead>
      </table>
      <div class='table-wrapper'>
        <table class='table' id='projectsTable'>
        </table>
      </div>
    </section>
    <section id='tasksBugs'>
      <header>
        完成任务与解决bug数据
        <div>优先级：<span class='pri pri-1'>1</span><span class='pri pri-2'>2</span><span class='pri pri-3'>3</span><span class='pri pri-4'>4</span></div>
      </header>
      <div class='row'>
        <div class='col-xs-6'>
          <div id='tasksChart' class='progress-pie inline-block space progress-pie-200' data-value='0' data-doughnut-size='80' data-color='#186bb1'>
            <canvas width='160' height='160' style='width: 160px; height: 160px;'></canvas>
            <div class='progress-info'>
              <p>累计完成任务数</p>
              <strong><span class='text-holder' data-id='finishTaskTotalCount'>512</span><small>个</small></strong>
            </div>
          </div>
          <ul class='clearfix'>
            <li><span class='pri pri-1'></span> <span class='text-holder' data-id='finishTaskCountPri1'>7</span> 个</li>
            <li><span class='pri pri-2'></span> <span class='text-holder' data-id='finishTaskCountPri2'>7</span> 个</li>
            <li><span class='pri pri-3'></span> <span class='text-holder' data-id='finishTaskCountPri3'>7</span> 个</li>
            <li><span class='pri pri-4'></span> <span class='text-holder' data-id='finishTaskCountPri4'>7</span> 个</li>
          </ul>
        </div>
        <div class='col-xs-6'>
          <div id='bugsChart' class='progress-pie inline-block space progress-pie-160' data-value='0' data-doughnut-size='80' data-color='#186bb1'>
            <canvas width='160' height='160' style='width: 160px; height: 160px;'></canvas>
            <div class='progress-info'>
              <p>累计修复bug数</p>
              <strong><span class='text-holder' data-id='finishBugTotalCount'>512</span><small>个</small></strong>
            </div>
          </div>
          <ul class='clearfix'>
            <li><span class='pri pri-1'></span> <span class='text-holder' data-id='finishBugCountPri1'>7</span> 个</li>
            <li><span class='pri pri-2'></span> <span class='text-holder' data-id='finishBugCountPri2'>7</span> 个</li>
            <li><span class='pri pri-3'></span> <span class='text-holder' data-id='finishBugCountPri3'>7</span> 个</li>
            <li><span class='pri pri-4'></span> <span class='text-holder' data-id='finishBugCountPri4'>7</span> 个</li>
          </ul>
        </div>
      </div>
    </section>
    <section id='tasksBugsHours'>
      <header>
        完成任务与解决bug工时统计
        <div><span class='dot dot-1'></span> 完成任务累计工时 <span class='dot dot-2'></span> 解决bug累计工时</div>
      </header>
      <canvas id='hoursChart' width='520' height='240'></canvas>
    </section>
  </main>
</div>
<script>
// 年度数据示例
var annualData =
{
    // 用户名
    username: 'XXX',

    // 累计动态数
    totalLoginTimes: 344,
    // 累计日志数
    totalDynamicCount: 1543,
    // 累计工时数
    totalWrokHours: 772,

    // 参与总项目数
    totalProjectsCount: 29,
    // 已完成的项目
    finishProjects: 20,
    // 正在进行的项目
    activateProjects: 7,
    // 已挂起的项目
    suspendProjects: 1,
    // 已完成的项目占比
    finishProjectsPercent: 71.4,
    // 正在进行的项目占比
    activateProjectsPercent: 25,
    // 已挂起的项目占比
    suspendProjectsPercent: 3.5,

    // 项目列表
    projectsList:
    [
        {name: '项目名称一', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称二', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称三', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称四', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称五', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称六', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称七', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称八', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称九', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称十', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称十一', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称十二', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称十三', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称十四', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称十五', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称十六', storyCount: 22, taskCount: 2, bugCount: '0%'},
        {name: '项目名称十七', storyCount: 22, taskCount: 2, bugCount: '0%'}
    ],

    // 总完成任务数
    finishTaskTotalCount: 512,
    // 完成任务数 - 优先级1
    finishTaskCountPri1: 42,
    // 完成任务数 - 优先级2
    finishTaskCountPri2: 172,
    // 完成任务数 - 优先级3
    finishTaskCountPri3: 212,
    // 完成任务数 - 优先级4
    finishTaskCountPri4: 86,

    // 总完成bug数
    finishBugTotalCount: 128,
    // 完成bug数 - 优先级1
    finishBugCountPri1: 42,
    // 完成bug数 - 优先级2
    finishBugCountPri2: 32,
    // 完成bug数 - 优先级3
    finishBugCountPri3: 20,
    // 完成bug数 - 优先级4
    finishBugCountPri4: 34,

    // 年度解决任务累计工时
    yearlyTask: '完成任务累计工时',
    yearlyTaskHours: [110, 154, 184, 220, 212, 250, 130, 146, 201, 89, 140, 59],
    // 年度解决bug累计工时,
    yearlyBug: '解决bug累计工时',
    yearlyBugHours: [10, 5, 11, 20, 30, 14, 3, 20, 50, 27, 13, 3],
    // 年度工时统计标标签
    yearlyLabels: ['一月', '二月', '三月', '四月', '五月', '六月', '七月', '八月', '九月', '十月', '十一月', '十二月']
};

// 显示年度数据
$(function()
{
    showAnnualData(annualData);
});
</script>
<?php include '../../common/view/footer.lite.html.php';?>
