$(document).ready(function()
{
    $('#mergeButton,.mergeButton').click(function()
    {
        link = $(this).attr('href');
        $.getJSON(link, function(response)
        {
            if(response.result == 'success')
            {
                $.zui.messager.success(response.message);
                setTimeout(function(){ location.href=response.locate }, 2500);
            }
            if(response.result == 'fail') $.zui.messager.danger(response.message);
        });
        return false;
    });
});
