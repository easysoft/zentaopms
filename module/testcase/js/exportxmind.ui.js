$(function()
{
    new zui.Tooltip('#xmindSettingTip', {title: xmindSettingTip, trigger: 'hover', placement: 'right', type: 'white'});
})

function setDownloading()
{
    if(navigator.userAgent.toLowerCase().indexOf("opera") > -1) return true; // Opera don't support, omit it.

    $.cookie.set('downloading', 0);

    time = setInterval(function()
    {
        if($.cookie.get('downloading') == 1)
        {
            $('.modal').trigger('to-hide.modal.zui');

            $.cookie.set('downloading', null);

            clearInterval(time);
        }
    }, 300);

    return true;
}
