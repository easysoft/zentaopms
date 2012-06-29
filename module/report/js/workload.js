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
    link = createLink('report', 'workload', 'begin=' + begin + '&end=' + end);
    location.href=link;
}
