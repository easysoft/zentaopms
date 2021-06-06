<?php
/**
 * The burn view file of execution module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     execution
 * @version     $Id: burn.html.php 4164 2013-01-20 08:27:55Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chart.html.php';?>
<?php js::set('executionID', $executionID);?>
<?php js::set('executionName', $execution->name);?>
<?php js::set('watermark', $lang->execution->watermark);?>
<?php js::set('burnXUnit', $lang->execution->burnXUnit);?>
<?php js::set('burnYUnit', $lang->execution->burnYUnit);?>
<?php js::set('type', $type);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    $weekend = ($type == 'noweekend') ? 'withweekend' : "noweekend";
    common::printLink('execution', 'computeBurn', 'reload=yes', '<i class="icon icon-refresh"></i> ' . $lang->execution->computeBurn, 'hiddenwin', "title='{$lang->execution->computeBurn}{$lang->execution->burn}' class='btn btn-primary' id='computeBurn'");
    echo '<div class="space"></div>';
    echo html::a($this->createLink('execution', 'burn', "executionID=$executionID&type=$weekend&interval=$interval"), $lang->execution->$weekend, '', "class='btn btn-link'");
    if(common::canModify('execution', $execution)) common::printLink('execution', 'fixFirst', "execution=$execution->id", $lang->execution->fixFirst, '', "class='btn btn-link iframe' data-width='700'");
    echo $lang->execution->howToUpdateBurn;
    ?>
    <?php if($config->systemMode == 'new'):?>
    <div class='input-control w-150px'>
      <?php echo html::select('burnBy', $lang->execution->burnByList, $burnBy, "class='form-control chosen'");?>
    </div>
    <?php endif;?>
    <?php if($interval):?>
    <div class='input-control thWidth'>
      <?php echo html::select('interval', $dayList, $interval, "class='form-control chosen'");?>
    </div>
    <?php endif;?>
  </div>
  <div class='pull-right'>
    <?php echo html::submitButton($lang->export, "onclick='downloadBurn();'", 'btn btn-primary');?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <h2 class='text-center'><?php echo $executionName . ' ' . $this->lang->execution->burn . '(' . zget($lang->execution->burnByList, $burnBy) . ')';?></h2>
  <div id="burnWrapper">
    <div id="burnChart">
      <canvas id="burnCanvas"></canvas>
    </div>
    <div id="burnYUnit"><?php echo $burnBy == 'storyPoint' ?  "({$lang->execution->storyPoint})" : "({$lang->execution->workHour})";?></div>
    <div id="burnXUnit"><?php echo $lang->execution->burnXUnit;?></div>
    <div id="burnLegend">
      <div class="line-ref"><div class='barline'></div><?php echo $lang->execution->charts->burn->graph->reference;?></div>
      <div class="line-real"><div class='barline bg-primary'></div><?php echo $lang->execution->charts->burn->graph->actuality;?></div>
    </div>
  </div>
</div>
<script>
$('#burnBy').change(function()
{
    $.cookie('burnBy', $(this).val(), {expires:config.cookieLife, path:config.webRoot});
    var interval = typeof($('#interval').val()) == 'undefined' ? 0 : $('#interval').val() ;
    location.href = createLink('execution', 'burn', 'executionID=' + executionID + '&type=' + type + '&interval=' + interval + '&burnBy=' + $(this).val());
});

function initBurnChar()
{
    var themePrimaryColor = $.getThemeColor('primary');
    var data =
    {
        labels: <?php echo json_encode($chartData['labels'])?>,
        datasets: [
        {
            label: "<?php echo $lang->execution->charts->burn->graph->reference;?>",
            color: "#F1F1F1",
            pointColor: '#D8D8D8',
            pointStrokeColor: '#D8D8D8',
            pointHighlightStroke: '#D8D8D8',
            fillColor: 'transparent',
            pointHighlightFill: '#fff',
            data: <?php echo $chartData['baseLine']?>
        },
        {
            label: "<?php echo $lang->execution->charts->burn->graph->actuality;?>",
            color: themePrimaryColor,
            pointStrokeColor: themePrimaryColor,
            pointHighlightStroke: themePrimaryColor,
            pointColor: themePrimaryColor,
            fillColor: 'rgba(0,106,241, .07)',
            pointHighlightFill: '#fff',
            data: <?php echo $chartData['burnLine']?>
        }]
    };

    var burnChart = $("#burnCanvas").lineChart(data,
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
        multiTooltipTitleTemplate: '<%= label %> <?php echo $lang->execution->workHour;?> /h',
        multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>",
    });
}
</script>
<?php include '../../common/view/footer.html.php';?>
