$(document).off('click', '.tipBtn').on('click', '.tipBtn', function(event)
{
    const link = $(this).attr('href');
    if(typeof link == 'undefined') return true;

    event.preventDefault();
    $.get(link, function(response)
    {
        try
        {
            response = JSON.parse(response);
            loadPage(link);
        }
        catch(e)
        {
            window.open(link);
        }
    });
})
