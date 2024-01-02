function changeParams(obj)
{
    var begin   = $('#conditions').find('#begin').val();
    var end     = $('#conditions').find('#end').val();
    var workday = $('#conditions').find('#workday').val();
    var dept    = $('#conditions').find('#dept').val();
    var assign  = $('#conditions').find('#assign').val();
    var days    = diffDate(begin, end);

    $('#days').val(days);

    if(begin.indexOf('-') != -1)
    {
        var beginarray = begin.split("-");
        var begin = '';
        for(i = 0; i < beginarray.length; i++) begin = begin + beginarray[i];
    }
    if(end.indexOf('-') != -1)
    {
        var endarray = end.split("-");
        var end = '';
        for(i = 0 ; i < endarray.length ; i++) end = end + endarray[i];
    }

    var params = window.btoa('begin=' + begin + '&end=' + end + '&days=' + days + '&workday=' + workday + '&dept=' + dept + '&assign=' + assign);
    var link = createLink('pivot', 'preview', 'dimension=' + dimension + '&group=' + groupID + '&module=pivot&method=workload&params=' + params);
    location.href = link;
}

/**
 * Convert a date string to date object in js.
 *
 * @param  string $date
 * @access public
 * @return date
 */
function convertStringToDate(dateString)
{
    dateString = dateString.split('-');
    return new Date(dateString[0], dateString[1] - 1, dateString[2]);
}

/**
 * Compute the diff days of two date.
 *
 * @param  string $date1
 * @param  string $date1
 * @access public
 * @return int
 */
function diffDate(date1, date2)
{
    date1 = convertStringToDate(date1);
    date2 = convertStringToDate(date2);
    delta = (date2 - date1) / (1000 * 60 * 60 * 24) + 1;

    weekEnds = 0;
    for(i = 0; i < delta; i++)
    {
        if((weekend == 2 && date1.getDay() == 6) || date1.getDay() == 0) weekEnds ++;
        date1 = date1.valueOf();
        date1 += 1000 * 60 * 60 * 24;
        date1 = new Date(date1);
    }
    return delta - weekEnds;
}

$(function()
{
    var options =
    {
        language: config.clientLang,
        weekStart: 1,
        todayBtn:  1,
        autoclose: 1,
        todayHighlight: 1,
        startView: 2,
        forceParse: 0,
        showMeridian: 1,
        minView: 2,
        format: 'yyyy-mm-dd',
    };
    $('input#begin,input#end').fixedDate().datetimepicker(options);
});
