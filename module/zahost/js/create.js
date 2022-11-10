$(document).ready(function()
{
    // $('#publicIP').on('blur', function()
    // {
    //     var url = createLink('zahost', 'ajaxCheckPublicIP', "IP=" + $('#publicIP').val());

    //     $.get(url, function(response)
    //     {
    //         $('#publicIPLabel').remove();
    //         $('#publicIP').removeClass('has-error');
    //         $('#registerCommand').remove();
    //         $('#Secret').remove();

    //         var resultData= JSON.parse(response);
    //         if(resultData.result == 'success')
    //         {
    //             $('input#type').after("<input type='hidden' name='Secret' id='Secret' value='" + resultData.data.secret + "' />");
    //             $('#publicIP').after("<div id='registerCommand' class='helper-text'>" + resultData.data.registerCommand + "</div>");
    //             return;
    //         }

    //         $('#publicIP').addClass('has-error');

    //         if(resultData.message.publicIP)
    //         {
    //             var errors = resultData.message.publicIP.join('');
    //             $('#publicIP').after("<div id='publicIPLabel' class='text-danger helper-text'>" + errors + "</div>");
    //         }
    //     });
    // });
});
