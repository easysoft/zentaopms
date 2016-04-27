<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<style>
.table-chart tr > td.chart-color {padding-left: 0!important; text-align: center; padding-right: 0!important; color: #f1f1f1}
.chart-wrapper {padding: 10px; background-color: #f1f1f1; border: 1px solid #e5e5e5}
.table-wrapper > .table-bordered > thead > tr:first-child th {border-top: 1px solid #ddd}
</style>
<!--[if lte IE 8]>
<?php
js::import($jsRoot . 'chartjs/excanvas.min.js');
?>
<![endif]-->
<?php
if($config->debug)
{
    js::import($jsRoot . 'chartjs/chart.min.js');
}
?>
<script>
(function()
{
    var colorIndex = 0;
    function nextAccentColor(idx)
    {
        if(typeof idx === 'undefined') idx = colorIndex++;
        return new $.zui.Color({h: idx * 67 % 360, s: 0.5, l: 0.55});
    }

    jQuery.fn.tableChart = function()
    {
        $(this).each(function()
        {
            var $table    = $(this);
            var options   = $table.data();
            var chartType = options.chart || 'pie';
            var $canvas   = $(options.target);
            if(!$canvas.length) return;
            var chart = null;

            if(chartType === 'pie')
            {
                options = $.extend({scaleShowLabels: true, scaleLabel: '<%=label%>: <%=value%>'}, options);
                var data = [];
                var $rows = $table.find('tbody > tr').each(function(idx)
                {
                    var $row = $(this);
                    var color = nextAccentColor().toCssStr();

                    $row.attr('data-id', idx).find('.chart-color-dot').css('color', color);
                    data.push({label: $row.find('.chart-label').text(), value: parseInt($row.find('.chart-value').text()), color: color, id: idx});
                });

                if(data.length > 1) options.scaleLabelPlacement = 'outside';
                else if(data.length === 1)
                {
                    options.scaleLabelPlacement = 'inside';
                    data.push({label: '', value: data[0].value/2000, color: '#fff', showLabel: false})
                }

                chart = $canvas.pieChart(data, options);
                $canvas.on('mousemove', function(e)
                {
                    var activePoints = chart.getSegmentsAtEvent(e);
                    $rows.removeClass('active');
                    if(activePoints.length)
                    {
                        $rows.filter('[data-id="' + activePoints[0].id + '"]').addClass('active');
                    }
                });
            }
            else if(chartType === 'bar')
            {
                var labels = [], dataset = {label: $table.find('thead .chart-label').text(), color: nextAccentColor().toCssStr(), data: []};

                var $rows = $table.find('tbody > tr').each(function(idx)
                {
                    var $row = $(this);
                    labels.push($row.find('.chart-label').text());
                    dataset.data.push(parseInt($row.find('.chart-value').text()));
                });
                var data = {labels: labels, datasets: [dataset]};
                if(labels.length) options.barValueSpacing = 5;

                chart = $canvas.barChart(data, options);
            }

            if(chart !== null) $table.data('zui.chart', chart);
        });
    };

    $(function()
    {
        $('.table-chart').tableChart();
    });
}());
</script>
