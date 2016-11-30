<?php if($extView = $this->getExtViewFile(__FILE__)){include $extView; return helper::cd();}?>
<style>
.projectline {padding: 2px!important}
</style>
<!--[if lte IE 8]>
<?php
js::import($jsRoot . 'chartjs/excanvas.min.js');
?>
<![endif]-->
<?php js::import($jsRoot . 'chartjs/chart.line.min.js');?>
<script>
var isIE = $.zui.browser.isIE();
jQuery.fn.projectLine = function(setting)
{
    var $lines = $(this);
    if(isIE && $.zui.browser.ie < 9 && $lines.length > 10) return;
    
    $lines.each(function()
    {
        var $e = $(this);
        var options = $.extend({values: $e.attr('values')}, $e.data(), setting),
            height = $e.height() - 4,
            values = [],
            maxWidth = $e.width() - 4;
        var strValues = options.values.split(','), maxValue = 0;
        for(var i in strValues)
        {
            var v = parseFloat(strValues[i]);
            if(v != NaN)
            {
                values.push(v);
                maxValue = Math.max(v, maxValue);
            }
        }

        var scaleSteps = Math.min(maxValue, 30);
        var scaleStepWidth = Math.ceil(maxValue/scaleSteps);

        var width = Math.min(maxWidth, Math.max(10, values.length*maxWidth/30));
        var canvas = $e.children('canvas');
        if(!canvas.length)
        {
            $e.append('<canvas class="projectline-canvas"></canvas>');
            canvas = $e.children('canvas');
            if($.zui.browser.ie == 8) G_vmlCanvasManager.initElement(canvas[0]);
        }
        canvas.attr('width', width).attr('height',height);
        $e.data('projectLineChart', new Chart(canvas[0].getContext("2d")).Line(
        {
            labels : values,
            datasets: 
            [{
                fillColor : "rgba(0,0,255,0.25)",
                strokeColor : "rgba(0,0,255,1)",
                pointColor : "rgba(255,136,0,1)",
                pointStrokeColor : "#fff",
                data : values
            }]
        },
        {
            animation: !isIE,
            scaleOverride: true,
            scaleStepWidth: Math.ceil(maxValue/10),
            scaleSteps: 10,
            scaleStartValue: 0
        }));
    });
}

$(function(){$('.projectline').projectLine();});
</script>
