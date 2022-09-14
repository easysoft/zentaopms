$(document).ready(function()
{
    $('#publicIP').on('blur', function()
    {
        var url = createLink('zahost', 'ajaxPingPublicIP', "IP=" + $('#publicIP').val());
        $.get(url, function(response)
        {
            $('#publicIPLabel').remove();
            $('#publicIP').removeClass('has-error');

            let resultData= JSON.parse(response);
            if(resultData.result == 'success') return;

            $('#publicIP').addClass('has-error');

            if(resultData.message.publicIP)
            {
              let errors = resultData.message.publicIP.join('');
              $('#publicIP').after("<div id='publicIPLabel' class='text-danger helper-text'>" + errors + "</div>");
            }
        });
    });
});
