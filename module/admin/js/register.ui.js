$('#agreeUX').on('change', function(e)
{
    $.post($.createLink('admin', 'changeAgreeUX'),{agreeUX:e.target.checked},function(response)
    {
        response = JSON.parse(response);
        zui.Messager.show(response.message);
    });
});

$('#unBind').on('click', function(e)
{
    $.post($.createLink('admin', 'unBindCommunity'),{},function(response)
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
$().ready(function()
{
    getCaptchaContent($('.image-box'));
});
