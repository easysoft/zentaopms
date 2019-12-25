/**
 * Show or refresh annual data
 * @param {Object} data Annual data object
 * @return {void}
 */
function showAnnualData(data)
{
    // Block 1
    var $block1List = $('#block1List').empty();
    data.block1.data && $.each(data.block1.data, function(index)
    {
        var item = data.block1.data[index];
        $block1List.append('<li>' + item.title + ' <strong>' + item.value + '</strong></li>');
    });

    // Block 2
    data.block2.dataTotal = 0;
    var $block2List = $('#block2List').empty();
    var $block2Chart = $('#block2Chart');
    var pieChart = $block2Chart.data('pieChart');
    var pieChartColors = ['#0068B7', '#1aa1e6', '#81cef2', '#aee3fc', '#b1e6ff', '#ddeaf0'];
    while(pieChart.segments.length) pieChart.removeData();
    pieChart.options.tooltipTemplate = "<%=label%>: <%=value%>";
    data.block2.data && $.each(data.block2.data, function(index)
    {
        var item = data.block2.data[index];
        var color = pieChartColors[Math.min(pieChartColors.length - 1, index)];
        data.block2.dataTotal += item.value;
        $block2List.append('<li><span class="dot" style="background: ' + color + '"></span> ' + item.title + ' <div><span>' + item.value + '<small>' + data.block2.unit + '</small></span><span>' + (item.percent || '') + '</span></div></li>');
        pieChart.addData({color: color, label: item.title, value: item.value});
    });

    // Block3
    var $blcok3TableHeader = $('#block3TableHeader').find('tr').empty();
    data.block3.cols && $.each(data.block3.cols, function(index)
    {
        var col = data.block3.cols[index];
        var $th = $('<th></th>').text(col.title);
        $th.css({width: col.width, textAlign: col.align});
        $blcok3TableHeader.append($th);
    });
    var $block3TableRows = $('<tbody></tbody>');
    data.block3.rows && $.each(data.block3.rows, function(index)
    {
        var row = data.block3.rows[index];
        var $tr = $('<tr></tr>');
        $.each(data.block3.cols, function(colIndex)
        {
            var $td = $('<td></td>').text(row[colIndex]);
            var rowCol = data.block3.cols[colIndex];
            $td.css({width: rowCol.width, textAlign: rowCol.align});
            $tr.append($td);
        });
        $block3TableRows.append($tr);
    });
    $('#block3Table').empty().append($block3TableRows);

    // Block4
    var block4Colors = ['#CAAC32', '#0075A9', '#22AC38', '#2B4D6D', '#0071a4', '#00a0e9', '#7ecef4'];
    $.each(['chart1', 'chart2'], function(i, chartName)
    {
        var $chart = $('#block4' + chartName);
        var chart = $chart.data('pieChart');
        var chartData = data.block4[chartName];
        chartData.total = 0;
        while(chart.segments.length) chart.removeData();
        var dataLength = chartData.data && chartData.data.length;
        if(!dataLength) return;
        for(var i = 0; i < dataLength; ++i)
        {
            var item = chartData.data[i];
            chartData.total += item.value;
        }
        chart.addData({color: 'transparent', value: chartData.total / 5, label: ''});
        var $chartInfo = $('#block4' + chartName + 'Info').empty();
        for(var i = 0; i < dataLength; ++i)
        {
            var item = chartData.data[i];
            var color = block4Colors[Math.min(block4Colors.length - 1, i)];
            chart.addData({color: color, label: item.title, value: item.value, circleBeginEnd: true});
            $chartInfo.append('<li><span class="pri" style="background: ' + color + '"></span>  ' + (item.legend ? (item.legend + ' - ')  :'') + item.value + ' ' + chartData.unit + '</li>');
        };
    });

    // Block5
    var $block5Chart = $('#block5Chart');
    var block5Chart = $block5Chart.data('block5Chart');
    var block5Colors = ['#76FF03', '#2979FF', '#00B0FF', '#FFD740', '#B388FF'];
    var block5Datasets = [];
    var $block5Legend = $('#block5Legend').empty();
    $.each(data.block5.datasets, function(index)
    {
        var dataset = data.block5.datasets[index];
        var color = block5Colors[Math.min(block5Colors.length - 1, index)];
        block5Datasets.push({label: dataset.label, data: dataset.data, color: new $.zui.Color(color).fade(50).toCssStr()});
        $block5Legend.append('<span class="dot" style="background: ' + color + '"></span> ' + dataset.label);
    });
    if(!block5Chart)
    {
        block5Chart = $block5Chart.lineChart(
        {
            labels: data.block5.labels,
            datasets: block5Datasets
        }, {
            scaleFontColor: '#A0A0A0',
            datasetStrokeWidth: 1,
            pointDotRadius: 3,
            scaleShowVerticalLines: false,
            scaleGridLineColor: 'rgba(255,255,255,.3)'
        });
        $block5Chart.data('block5Chart', block5Chart);
    }
    else
    {
        $.each(block5Datasets, function(index)
        {
            var dataset = block5Datasets[index];
            var oldDataset = block5Chart.datasets[index];
            for(var i = 0; i < dataset.data.length; ++i)
            {
                oldDataset.points[i].value = dataset.data[i];
            }
        });
        block5Chart.update();
    }

    // Common text holders
    $('.text-holder').each(function()
    {
        var $this = $(this);
        var id = $this.data('id');
        var idPath = id.split('.');
        var dataProp = data;
        for(var i = 0; i < idPath.length; ++i)
        {
            dataProp = dataProp[idPath[i]];
        }
        $this.text(dataProp);
    });
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
    $main.addClass('exporting').css('backgroundImage', $container.css('backgroundImage'));
    var afterFinish = function(canvas)
    {
        $main.removeClass('exporting').css('backgroundImage', 'none');
        $loading.removeClass('loading');
    };
    html2canvas($main[0], {logging: false}).then(function(canvas)
    {
        canvas.onerror = function()
        {
            afterFinish(canvas);
            if(errorCallback) errorCallback('Cannot convert image to blob.');
        };
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
    var $main = $('#main');
    var ajustPosition = function()
    {
        $main.css(
        {
            posotion: 'absolute',
            top: Math.max(0, Math.floor(($(window).height() - $main.outerHeight()) / 2)),
            left: Math.max(0, Math.floor(($(window).width() - $main.outerWidth()) / 2)),
        });
    };
    ajustPosition();
    $(window).on('resize', ajustPosition);

    $('#exportBtn').on('click', function()
    {
        exportAnnualImage();
    });

    $('#toolbar #year').change(function()
    {
        location.href = createLink('report', 'annualData', 'year=' + $(this).val());
    });
});
