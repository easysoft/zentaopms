let netConnct = 0;
$.ajax(
{
    url: $.createLink('misc', 'checkNetConnect'),
    timeout: 1000,
    success: function(data)
    {
        netConnect = +(data == 'success');
    },
    error: function(XMLHttpRequest, textStatus)
    {
        netConnect = 0;
    }
});

function confirmBackup(event)
{
    if($(event.target).prop('checked'))
    {
        $('#upgrade').removeClass('disabled').attr('href', $.createLink('upgrade', 'consistency', 'netConnect=' + netConnect));
    }
    else
    {
        $('#upgrade').addClass('disabled').removeAttr('href');
    }
}
