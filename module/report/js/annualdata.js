/**
 * Draw status pie chart.
 *
 * @param  string   $id
 * @param  string   $title
 * @param  array    $data
 * @param  function $callback
 * @access public
 * @return object
 */
function drawStatusPieChart(id, title, data, callback)
{
    var titleTextStyle = {
        color:'#fff',
        fontSize: 14
    };
    var tooltip = {
        trigger: 'item',
        backgroundColor: '#010419',
        textStyle: {color:'#fff'},
        formatter: '{a} <br/>{b}: {c} ({d}%)'
    };

    var legendLeft       = '0';
    var legendTop        = '25';
    var legendItemWidth  = 8;
    var legendItemHeight = 8;
    var legendTextStyle  = {
        color:'#fff',
        fontSize: 12
    };

    var seriesTop    = '50';
    var seriesRadius = ['40%', '70%'];
    var seriesLabel  = {
        color:'#fff',
        formatter: '{b}  {d}%'
    };

    var chart  = echarts.init(document.getElementById(id));
    var option = {
	    title: {
            text: title,
            textStyle: titleTextStyle,
        },
        tooltip: tooltip,
        legend: {
            left: legendLeft,
            top: legendTop,
            icon: 'circle',
            itemWidth: legendItemWidth,
            itemHeight: legendItemHeight,
            textStyle: legendTextStyle,
        },
        series: [
            {
                name: title,
                type: 'pie',
                top: seriesTop,
                radius: seriesRadius,
                avoidLabelOverlap: false,
                label: seriesLabel,
                data: data
            }
        ]
    }
    chart.setOption(option);
    if(typeof(callback) == 'function') chart.on('finished', callback);

    return chart;
}

/**
 * Draw months bar chart.
 *
 * @param  string $id
 * @param  string $title
 * @param  array  $legends
 * @param  array  $xAxis
 * @param  array  $data
 * @access public
 * @return object
 */
function drawMonthsBarChart(id, title, legends, xAxis, data)
{
    var titleTextStyle = {
        color:'#fff',
        fontSize: 14
    };
    var tooltip = {
        trigger: 'axis',
        axisPointer: {
          type: 'shadow'
        }
    };

    var legendRight      = '20';
    var legendTop        = '0';
    var legendItemWidth  = 10;
    var legendItemHeight = 10;
    var legendTextStyle  = {
        color:'#fff',
        fontSize: 12
    };

    var labelStyle = {color:'#fff'}

    var chart  = echarts.init(document.getElementById(id));
    var option = {
	    title: {
            text: title,
            textStyle: titleTextStyle,
        },
        tooltip: tooltip,
        legend: {
            right: legendRight,
            top: legendTop,
            itemWidth: legendItemWidth,
            itemHeight: legendItemHeight,
            textStyle: legendTextStyle,
            data: legends
        },
        grid: {
          left: '0%',
          right: '0%',
          bottom: '2%',
          containLabel: true
        },
        yAxis: {
          type: 'value',
          axisLine: {show: true },
          splitLine: {show: false},
          axisLabel: labelStyle
        },
        xAxis: {
          type: 'category',
          axisLabel: labelStyle,
          axisTick: {alignWithLabel: true},
          data: xAxis
        },
        series: data
    }
    chart.setOption(option);

    return chart;
}

/**
 * Export annual data to image file
 * @param {function} sucessCallback
 * @param {function} errorCallback
 * @return {void}
 */
function exportAnnualImage(sucessCallback, errorCallback)
{
    var $main = $('#main');
    if($main.hasClass('exporting')) return;
    var $loading = $('#loadIndicator');
    $loading.addClass('loading');
    var $container = $('#container');
    $main.addClass('exporting').css('background-color', $container.css('background-color'));
    var afterFinish = function(canvas)
    {
        $main.removeClass('exporting');
        $loading.removeClass('loading');
    };
    html2canvas($main[0], {logging: false}).then(function(canvas)
    {
        canvas.onerror = function()
        {
            afterFinish(canvas);
            if(errorCallback) errorCallback('Cannot convert image to blob.');
        };

        /* Watermark. */
        const ctx = canvas.getContext('2d');
        ctx.font = '12px serif';
        ctx.fillStyle = 'rgba(200,200,200, 0.8)';
        ctx.fillText(exportByZentao, 1220, 90);
        ctx.fillText(exportByZentao, 45, canvas.height - 10);

        canvas.toBlob(function(blob)
        {
            var imageUrl = URL.createObjectURL(blob);
            $('#imageDownloadBtn').attr({href: imageUrl})[0].click();
            if(sucessCallback) sucessCallback(imageUrl);
            afterFinish(canvas);
        });
    });
}

$(function()
{
    $('#exportBtn').on('click', function()
    {
        exportAnnualImage();
    });

    $('select#year, select#dept, select#account').change(function()
    {
        var year    = $('select#year').val();
        var dept    = $('select#dept').val();
        var account = $('select#account').val();
        if($(this).attr('id') == 'dept') account = '';
        location.href = createLink('screen', 'view', 'screenID=3&year=' + year + '&month=' + '&dept=' + dept + '&account=' + account);
    });

    $('#actionData > div > ul > li').mouseenter(function(e)
    {
        var width     = $(this).width();
        var maxOffset = width - 100;
        var offset    = e.pageX - $(this).offset().left + 10;
        if(offset > maxOffset) offset = maxOffset;
        $('#actionData > div > ul > li .dropdown-menu').css('left', offset);
    });

    $('section').mouseover(function(){$(this).addClass('active')});
    $('section').mouseout(function(){$(this).removeClass('active')});
});
