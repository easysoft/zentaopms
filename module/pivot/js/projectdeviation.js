$(document).ready(function()
{
    initChart();

    $('.icon-exclamation-sign').hover(function(){
        $('#desc').removeClass('hidden');
    }, function(){
        $('#desc').addClass('hidden');
    });
});

function changeDate(begin, end)
{
    if(begin.indexOf('-') != -1)
    {
        var beginarray = begin.split("-");
        var begin = '';
        for(i=0 ; i < beginarray.length ; i++)
        {
            begin = begin + beginarray[i];
        }
    }
    if(end.indexOf('-') != -1)
    {
        var endarray = end.split("-");
        var end = '';
        for(i=0 ; i < endarray.length ; i++)
        {
            end = end + endarray[i];
        }
    }

    var params = window.btoa('begin=' + begin + '&end=' + end);
    var link = createLink('pivot', 'preview', 'dimension=' + dimension + '&group=' + groupID + '&module=pivot&method=projectdeviation&params=' + params);
    location.href = link;
}
