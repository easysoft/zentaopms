$(document).ready(function()
{
    if(code == 'all') 
    {
        recalculateAll(startDate, endDate);
    }
    else
    {
        recalculateSingle(code, startDate, endDate);
    }
});

function recalculateAll(startDate, endDate)
{
    var dateRange = getDateRange(startDate, endDate);

    var calcLink = $.createLink('metric', 'saveClassifiedCalcGroup');
    $.get(calcLink, function(result){
        updateAllHistory(dateRange);
    });

    function updateAllHistory(dateRange, index = 0)
    {
        if(index >= dateRange.length) 
        {
            var deduplication = $.createLink('metric', 'deduplicateRecord');
            $.get(deduplication, function(result){});
            return;
        }
        
        var date = dateToString(dateRange[index]);
        var $html = recalculateLog(date);

        var link = $.createLink('metric', 'updateHistoryMetricLib', 'date=' + date);
        $.get(link, function(result){
            $('.verify-content').append($html);
            updateAllHistory(dateRange, index + 1);
        });
    }
}

function recalculateSingle(code, startDate, endDate)
{
    var dateRange = getDateRange(startDate, endDate, dateType);
    updateSingleHistory(dateRange);
    function updateSingleHistory(dateRange, index = 0)
    {
        if(index >= dateRange.length) 
        {
            var deduplication = $.createLink('metric', 'deduplicateRecord');
            $.get(deduplication, function(result){});
            return;
        }

        var date = dateToString(dateRange[index]);
        var $html = recalculateLog(date);

        var link = $.createLink('metric', 'updateSingleMetricLib', 'code=' + code + '&date=' + date);
        $.get(link, function(result){
            $('.verify-content').append($html);
            updateSingleHistory(dateRange, index + 1);
        });
    }
}

function recalculateLog(date)
{
    var html = '<p class="verify-sentence-pass">';
    html += recalculateLogText.replace('%s', date);
    html += '<i class="icon icon-pass"></i>';
    html += '</p>';

    return html;
}

function getDateRange(startDate, endDate, dateType = 'day')
{
    var start = new Date(startDate);
    var end   = new Date(endDate);

    var dateRange = [];

    if(dateType == 'day')
    {
        while(start <= end) 
        {
            dateRange.push(new Date(start));
            start.setDate(start.getDate() + 1);
        }
    }

    if(dateType == 'month')
    {
        start.setDate(1);
        while(start <= end) 
        {
            var nextMonth = new Date(start.getFullYear(), start.getMonth() + 1, 1);
            var lastDayOfMonth = new Date(nextMonth - 1);
            dateRange.push(lastDayOfMonth);

            start = nextMonth;
        }
        dateRange.pop();
        dateRange.push(end);
    }

    if(dateType == 'year')
    {
        startYear = start.getFullYear();
        endYear   = end.getFullYear();

        for(let year = startYear; year <= endYear; year++)
        {
            var lastDayOfYear = year + '-12-31';
            dateRange.push(new Date(lastDayOfYear));
        }
        dateRange.pop();
        dateRange.push(end);
    }

    return dateRange;
}

function dateToString(date)
{
    var year  = date.getFullYear();
    var month = (date.getMonth() + 1).toString().padStart(2, '0');
    var day   = date.getDate().toString().padStart(2, '0');

    return year + '_' + month + '_' + day;
}
