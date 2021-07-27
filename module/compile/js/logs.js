$().ready(function()
{
    $('#refreshBtn').click(function()
    {
        $url = $(this).attr('href');
        $.get($url, function(response)
        {
            window.location.reload();
        });

        return false;
    });
});
