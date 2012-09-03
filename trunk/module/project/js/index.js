$().ready(function()
{
    $('.projectline').each(function()
    {
        $(this).sparkline('html', {height:'25px'});
    })
})
