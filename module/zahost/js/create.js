$(document).ready(function()
{
    $('#publicIP').on('blur', function()
    {
        var url = createLink('zahost', 'ajaxPingPublicIP', "IP=" + $('#publicIP').val());
        $.get(url, function(response)
        {
            let resultData= JSON.parse(response);
            if(resultData.result == 'success')
            {
              $('#publicIP').removeClass('has-error');
              $('#publicIPLabel').remove();
              return;
            }

            $('#publicIP').addClass('has-error');

            if(resultData.message.publicIP)
            {
              let errors = resultData.message.publicIP.join('');
              $('#publicIP').after("<div id='publicIPLabel' class='text-danger helper-text'>" + errors + "</div>");
            }
        });
    });
});
