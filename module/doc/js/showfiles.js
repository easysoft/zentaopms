$(function()
{
    $('#leftBar .searchBox #title').keypress(function(e)
    {
        if(e.which == 13)
        {
            $(this).closest('form').attr('action', searchLink.replace('%s', $(this).val()));
        }
    });

    $('#leftBar .searchBox #submit').click(function(e)
    {
        var searchTitle = $(this).prev('#title').val();
        $(this).closest('form').attr('action', searchLink.replace('%s', searchTitle));
    });
})
