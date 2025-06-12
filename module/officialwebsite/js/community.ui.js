$('#agreeUX').on('change', function(e)
{
    $.post($.createLink('officialwebsite', 'changeAgreeUX'),{agreeUX:e.target.checked},function(response)
    {
        response = JSON.parse(response);
        zui.Messager.show(response.message);
    });
});

$('#unBind').on('click', function(e)
{
    $.post($.createLink('officialwebsite', 'unBindCommunity'),{},function(response)
    {
        response = JSON.parse(response);
        zui.Messager.show(response.message);
        if(response.result == 'success')
        {
            setTimeout(function() {
                location.href = response.load;
            }, 1000);
        }
    });
});