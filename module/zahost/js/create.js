$(document).ready(function()
{
    // $('#address').on('blur', function()
    // {
    //     var url = createLink('zahost', 'ajaxCheckaddress', "IP=" + $('#address').val());

    //     $.get(url, function(response)
    //     {
    //         $('#addressLabel').remove();
    //         $('#address').removeClass('has-error');
    //         $('#registerCommand').remove();
    //         $('#Secret').remove();

    //         var resultData= JSON.parse(response);
    //         if(resultData.result == 'success')
    //         {
    //             $('input#type').after("<input type='hidden' name='Secret' id='Secret' value='" + resultData.data.secret + "' />");
    //             $('#address').after("<div id='registerCommand' class='helper-text'>" + resultData.data.registerCommand + "</div>");
    //             return;
    //         }

    //         $('#address').addClass('has-error');

    //         if(resultData.message.address)
    //         {
    //             var errors = resultData.message.address.join('');
    //             $('#address').after("<div id='addressLabel' class='text-danger helper-text'>" + errors + "</div>");
    //         }
    //     });
    // });
});
