<?php
/**
 * The burn view file of project module of ZenTaoPMS.
 *
 * @copyright   Copyright 2009-2015 青岛易软天创网络科技有限公司(QingDao Nature Easy Soft Network Technology Co,LTD, www.cnezsoft.com)
 * @license     ZPL (http://zpl.pub/page/zplv12.html)
 * @author      Chunsheng Wang <chunsheng@cnezsoft.com>
 * @package     project
 * @version     $Id: burn.html.php 4164 2013-01-20 08:27:55Z wwccss $
 * @link        http://www.zentao.net
 */
?>
<?php include '../../common/view/header.html.php';?>
<?php include '../../common/view/chart.html.php';?>
<?php js::set('projectID', $projectID);?>
<?php js::set('type', $type);?>
<div id='mainMenu' class='clearfix'>
  <div class='btn-toolbar pull-left'>
    <?php
    $weekend = ($type == 'noweekend') ? 'withweekend' : "noweekend";
    common::printLink('project', 'computeBurn', 'reload=yes', '<i class="icon icon-refresh"></i> ' . $lang->project->computeBurn, 'hiddenwin', "title='{$lang->project->computeBurn}{$lang->project->burn}' class='btn btn-primary' id='computeBurn'");
    echo '<div class="space"></div>';
    echo html::a($this->createLink('project', 'burn', "projectID=$projectID&type=$weekend&interval=$interval"), $lang->project->$weekend, '', "class='btn btn-link'");
    common::printLink('project', 'fixFirst', "project=$project->id", $lang->project->fixFirst, '', "class='btn btn-link iframe' data-width='600'");
    echo $lang->project->howToUpdateBurn;
    ?>
    <?php if($interval):?>
    <div class='input-control w-120px'>
      <?php echo html::select('interval', $dayList, $interval, "class='form-control chosen'");?>
    </div>
    <?php endif;?>
  </div>
</div>
<div id='mainContent' class='main-content'>
  <h2 class='text-center'><?php echo $projectName . ' ' . $this->lang->project->burn;?></h2>
  <div id="burnWrapper">
    <div id="burnChart">
      <canvas id="burnCanvas"></canvas>
    </div>
    <div id="burnYUnit"><?php echo "({$lang->project->workHour})";?></div>
    <div id="burnLegend">
      <div class="line-ref"><div class='barline'></div><?php echo $lang->project->charts->burn->graph->reference;?></div>
      <div class="line-real"><div class='barline bg-primary'></div><?php echo $lang->project->charts->burn->graph->actuality;?></div>
    </div>
  </div>
</div>
<script>
function initBurnChar()
{
    var themePrimaryColor = $.getThemeColor('primary');
    var data =
    {
        labels: <?php echo json_encode($chartData['labels'])?>,
        datasets: [
        {
            label: "<?php echo $lang->project->charts->burn->graph->reference;?>",
            color: "#F1F1F1",
            pointColor: '#D8D8D8',
            pointStrokeColor: '#D8D8D8',
            pointHighlightStroke: '#D8D8D8',
            fillColor: 'transparent',
            pointHighlightFill: '#fff',
            data: <?php echo $chartData['baseLine']?>
        },
        {
            label: "<?php echo $lang->project->charts->burn->graph->actuality;?>",
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
        multiTooltipTitleTemplate: '<%= label %> <?php echo $lang->project->workHour;?> /h',
        multiTooltipTemplate: "<%if (datasetLabel){%><%=datasetLabel%>: <%}%><%= value %>",
    });
}
</script>
<?php include '../../common/view/footer.html.php';?>
