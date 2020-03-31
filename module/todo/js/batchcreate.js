function updateAction(date)
{
    if(date.indexOf('-') != -1)
    {
        var dateArray = date.split('-');
        date = '';
        for(i = 0; i < dateArray.length; i++)
        {
            date = date + dateArray[i];
        }
    }
    location.href = createLink('todo', 'batchCreate', 'date=' + date);
}

$(function()
{
    setBeginsAndEnds();
});
