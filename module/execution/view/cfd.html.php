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
<?php include '../../common/view/chart.html.php';?>
<?php js::set('executionID', $executionID);?>
<?php js::set('executionName', $execution->name);?>
<?php js::set('watermark', $lang->execution->watermark);?>
<?php js::set('cfdXUnit', $lang->execution->burnXUnit);?>
<?php js::set('cfdYUnit', $lang->execution->burnYUnit);?>
<?php js::set('type', $type);?>
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
      <canvas id="cfdCanvas"></canvas>
    </div>
    <div id="cfdYUnit"><?php echo $lang->execution->count;?></div>
    <div id="cfdXUnit"><?php echo $lang->execution->burnXUnit;?></div>
  </div>
</div>
<script>
var sets   = [];
var colors = ['#33B4DB', '#7ECF69', '#FFC73A', '#FF5A61', '#50C8D0', '#AF5AFF', '#4EA3FF', '#6C73FF', '#FF8C5A'];
var i      = 0;
<?php foreach($chartData['line'] as $label => $set):?>
sets.push({
    label: '<?php echo $label;?>',
    color: colors[i],
    pointStrokeColor: colors[i],
    pointHighlightStroke: colors[i],
    pointColor: colors[i],
    fillColor: colors[i] + '12',
    pointHighlightFill: colors[i],
    data: <?php echo $set;?>
})
i ++;
<?php endforeach;?>

$('#type').change(function()
{
    location.href = createLink('execution', 'cfd', 'executionID=' + executionID + '&type=' + $(this).val());
});

$(function(){ initBurnChar();})

function initBurnChar()
{
    var data =
    {
        labels: <?php echo json_encode($chartData['labels'])?>,
        datasets: sets
    };

    var cfdChart = $("#cfdCanvas").lineChart(data,
    {
        pointDotStrokeWidth: 2,
        pointDotRadius: 3,
        datasetStrokeWidth: 3,
        datasetFill: true,
        datasetStroke: true,
        scaleShowBeyondLine: false,
        responsive: true,
        bezierCurve: false,
        scaleFontColor: '#838A9D',
        tooltipXPadding: 10,
        tooltipYPadding: 10,
        multiTooltipTitleTemplate: '<%= label %> <?php echo $lang->execution->count;?>',
        multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>",
    });
}
</script>
<?php include '../../common/view/footer.html.php';?>
