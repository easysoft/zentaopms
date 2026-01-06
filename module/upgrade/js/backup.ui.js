$.ajax(
{
    url: $.createLink('misc', 'checkNetConnect'),
    timeout: 1000,
    success: function(data)
    {
        $('#upgrade').attr('href', $.createLink('upgrade', 'consistency', 'netConnect=' + +(data == 'success'))).removeClass('disabled');
    },
    error: function(XMLHttpRequest, textStatus)
    {
        $('#upgrade').attr('href', $.createLink('upgrade', 'consistency', 'netConnect=0')).removeClass('disabled');
    }
});
