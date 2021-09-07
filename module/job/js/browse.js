$(document).ready(function()
{
    $('.icon-job-exec').parent().click(function()
    {
        link = $(this).attr('href');
        $.getJSON(link, function(response)
        {
            if(response.result == 'success') bootbox.alert(response.message);
            if(response.result != 'success') bootbox.alert(response.message, function()
            {
                if(typeof(response.locate) == 'string') location.href = response.locate;
            });
        });
        return false;
    });
});
