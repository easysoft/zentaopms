$(document).ready(function()
{
    recalculate(startDate, endDate);
});

function recalculate(startDate, endDate)
{
    var dateRange = getDateRange(startDate, endDate);

    var calcLink = $.createLink('metric', 'saveClassifiedCalcGroup');
    $.get(calcLink, function(result){
        updateHistory(dateRange);
    });

    function updateHistory(dateRange, index = 0)
    {
        if(index >= dateRange.length) 
        {
            var deduplication = $.createLink('metric', 'deduplicateRecord');
            $.get(deduplication, function(result){});
            return;
        }
        
        var date = dateRange[index];

        var year = date.getFullYear();
        var month = (date.getMonth() + 1).toString().padStart(2, '0');
        var day = date.getDate().toString().padStart(2, '0');

        var date  = year + '_' + month + '_' + day;
        var $html = recalculateLog(date);

        var link = $.createLink('metric', 'updateHistoryMetricLib', 'date=' + date);
        $.get(link, function(result){
            $('.verify-content').append($html);
            updateHistory(dateRange, index + 1);
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

function getDateRange(startDate, endDate)
{
    var startDate = new Date(startDate);
    var endDate   = new Date(endDate);

    var dateRange = [];
    while(startDate <= endDate) 
    {
        dateRange.push(new Date(startDate));
        startDate.setDate(startDate.getDate() + 1);
    }

    return dateRange;
}
