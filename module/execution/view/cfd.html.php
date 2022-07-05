<?php
/**
 * The burn view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL(http://zpl.pub/page/zplv12.html) or AGPL(https://www.gnu.org/licenses/agpl-3.0.en.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: cfd.html.php 4164 2013-01-20 08:27:55Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php js::set('executionID', $executionID);?>
<?php js::set('executionName', $execution->name);?>
<?php js::set('watermark', $lang->execution->watermark);?>
<?php js::set('cfdXUnit', $lang->execution->burnXUnit);?>
<?php js::set('cfdYUnit', $lang->execution->burnYUnit);?>
<?php js::import($jsRoot . 'echarts/echarts.common.min.js'); ?>
<?php js::import($jsRoot . 'html2canvas/min.js'); ?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    if(strpos('wait,doing', $execution->status) !== false)
    {
        common::printLink('execution', 'computeCFD', "reload=yes&executionID=$executionID", '<i class="icon icon-refresh"></i> ' . $lang->execution->computeCFD, 'hiddenwin', "title='{$lang->execution->computeCFD}' class='btn btn-primary' id='computeCFD'");
        echo '<div class="space"></div>';
    }
    ?>
    <div class='input-control w-100px'>
      <?php echo html::select('type', $lang->execution->cfdTypeList, $type, "class='form-control chosen'");?>
    </div>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <h2 class='text-center'><?php echo $executionName . ' - ' . zget($lang->execution->cfdTypeList, $type) . $lang->execution->CFD;?></h2>
  <div id="cfdWrapper">
    <div id="cfdChart">
      <canvas id="cfdCanvas" width='1400' height='600'></canvas>
    </div>
    <div id="cfdYUnit"><?php echo $lang->execution->count;?></div>
    <div id="cfdXUnit"><?php echo $lang->execution->burnXUnit;?></div>
  </div>
</div>
<script>
var series = [];
var colors = ['#33B4DB', '#7ECF69', '#FFC73A', '#FF5A61', '#50C8D0', '#AF5AFF', '#4EA3FF', '#6C73FF', '#FF8C5A'];
var i      = 0;
<?php foreach($chartData['line'] as $label => $set):?>
series.push({
    name: '<?php echo $label;?>',
    type: 'line',
    stack: 'Total',
    areaStyle: {},
    emphasis: {
        focus: 'series'
    },
    data: <?php echo $set;?>
})
i ++;
<?php endforeach;?>

var chartDom = document.getElementById('cfdCanvas');
var myChart = echarts.init(chartDom);
var option;

option = {
    tooltip: {
        trigger: 'axis',
        axisPointer: {
            type: 'cross',
            label: {
                backgroundColor: '#6a7985'
            }
        }
    },
    legend: {
        data: ['Email', 'Union Ads', 'Video Ads', 'Direct', 'Search Engine']
    },
    toolbox: {
        feature: {
            saveAsImage: {}
        }
    },
    grid: {
        left: '3%',
        right: '4%',
        bottom: '3%',
        containLabel: true
    },
    xAxis: [
    {
        type: 'category',
        boundaryGap: false,
        data: <?php echo json_encode($chartData['labels'])?>,
    }
    ],
    yAxis: [
    {
        type: 'value'
    }
    ],
    series: series,
};

option && myChart.setOption(option);

$('#type').change(function()
{
    location.href = createLink('execution', 'cfd', 'executionID=' + executionID + '&type=' + $(this).val());
});
</script>
<?php include '../../common/view/footer.html.php';?>
