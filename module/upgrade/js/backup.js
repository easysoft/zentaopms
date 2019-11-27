$(function()
{
    $.ajax(
    {
        url: createLink('misc', 'checkNetConnect'),
        timeout: 3000,
        success: function(data)
        {
            if(data == 'fail') $('.btn-primary.disabled').attr('onclick', "self.location.href=\"" + createLink('upgrade', 'consistency', "netConnect=0") + "\"");
            $('.btn-primary.disabled').removeClass('disabled');
        },
        error: function(XMLHttpRequest, textStatus)
        {
            $('.btn-primary.disabled').attr('onclick', "self.location.href=\"" + createLink('upgrade', 'consistency', "netConnect=0") + "\"").removeClass('disabled');
        }
    });
});
