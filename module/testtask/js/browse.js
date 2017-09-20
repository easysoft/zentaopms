$(document).ready(function()
{
    if(flow == 'onlyTest')
    {
        $('#modulemenu > .nav > li').removeClass('active');
        $('#modulemenu > .nav > li[data-id=' + status + ']').addClass('active');
    }
});

function changeDate(begin, end, condition)
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

    condition = condition + '&beginTime=' + begin + '&endTime=' + end;
    link = createLink('testtask', 'browse', condition);
    location.href=link;
}
