<div class='cell table-row chart-row'>
  <div class='chart-wrapper text-center'>
    <h4><?php echo $lang->milestone->chart->title;?></h4>
    <div class='chart-canvas'><canvas id='chart-line' width='500' height='140' data-responsive='true'></canvas></div>
  </div>
  <div id="chartUnit"><?php echo "({$lang->project->workHour})";?></div>
  <div id="chartLegend">
    <div class="line-pv"><div class='barline bg-primary'></div>PV</div>
    <div class="line-ev"><div class='barline'></div>EV</div>
    <div class="line-ac"><div class='barline'></div>AC</div>
  </div>
</div>
<script>
function initChar()
{
    var themePrimaryColor = $.getThemeColor('primary');
    var data =
    {
        labels: <?php echo json_encode($charts['labels'])?>,
        datasets: [
        {
            label: "PV",
            color: themePrimaryColor,
            pointColor: themePrimaryColor,
            pointStrokeColor: themePrimaryColor,
            pointHighlightStroke: themePrimaryColor,
            fillColor: 'rgba(0,106,241, .07)',
            pointHighlightFill: '#fff',
            data: <?php echo $charts['PV']?>
        },
        {
            label: "EV",
            color: 'rgb(0, 218, 136)',
            pointColor: 'rgb(0, 218, 136)',
            pointStrokeColor: 'rgb(0, 218, 136)',
            pointHighlightStroke: 'rgb(0, 218, 136)',
            fillColor: 'rgb(0, 218, 136, .07)',
            pointHighlightFill: '#fff',
            data: <?php echo $charts['EV']?>
        },
        {
            label: "AC",
            color: 'rgb(255, 145, 0)',
            pointColor: 'rgb(255, 145, 0)',
            pointStrokeColor: 'rgb(255, 145, 0)',
            pointHighlightStroke: 'rgb(255, 145, 0)',
            fillColor: 'rgb(255, 145, 0, .07)',
            pointHighlightFill: '#fff',
            data: <?php echo $charts['AC']?>
        }]
    };

    var chartLine= $("#chart-line").lineChart(data,
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
initChar();
</script>
