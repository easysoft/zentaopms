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

    jQuery.fn.progressPie = function(setting)
    {
        $(this).each(function()
        {
            var $this = $(this);
            var $canvas = $this.is('canvas') ? $this : $this.find('canvas');
            var options = $.extend(
            {
                value: 0,
                color: '#4CAF50',
                backColor: '#ddd',
                doughnut: true,
                doughnutSize: 85,
                width: 20,
                height: 20,
                showTip: false,
                name: '',
                tipTemplate: "<%=value%>%",
                animation: false
            }, setting, $this.data());
            var hasCanvas = $canvas.length;

            if(!hasCanvas) $canvas = $('<canvas>').appendTo($this);
            if($canvas.attr('width') !== undefined) options.width = $canvas.attr('width');
            else $canvas.attr('width', options.width);
            if($canvas.attr('height') !== undefined) options.height = $canvas.attr('height');
            else $canvas.attr('height', options.height);
            if(!hasCanvas && $.zui.browser.ie == 8) G_vmlCanvasManager.initElement($canvas[0]);

            options.value = Math.max(0, Math.min(100, options.value));

            var data = 
            [
                {value: options.value, label: options.name, color: options.color},
                {value: 100 - options.value, label: '', color: options.backColor}
            ];

            $canvas[options.doughnut ? 'doughnutChart' : 'pieChart'](data, $.extend(
            {
                segmentShowStroke: false,
                animation: options.animation,
                showTooltips: options.showTip,
                tooltipTemplate: options.tipTemplate,
                percentageInnerCutout: options.doughnutSize,
            }, options.chartOptions));
        });
    };

    $(function()
    {
        $('.table-chart').tableChart();
        var $pies = $('.progress-pie');
        if($pies.length > 100)  setTimeout(function(){$pies.progressPie();}, 1000);
        else $pies.progressPie();
    });
}());
</script>
