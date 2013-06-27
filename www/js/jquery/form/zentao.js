$.extend(
{
    ajaxForm: function(formID, callback)
    {
        form = $(formID); 
        var options = 
        {
            target  : null,
            timeout : 30000,
            dataType:'json',

            success:function(response)
            {
                $.enableForm(formID);

                /* The response is not an object, some error occers, alert it. */
                if($.type(response) != 'object')
                {
                    if(response) return alert(response);
                    return alert('No response.');
                }

                /* The response.result is success. */
                if(response.result == 'success')
                {
                    if(typeof(callback) == 'function') return callback(response);
                    if(response.message) alert(response.message);
                    if(response.locate) return location.href = response.locate;
                }

                /**
                 * The response.result is fail. 
                 */

                /* The result.message is just a string. */
                if($.type(response.message) == 'string')
                {
                    if($('#responser').length == 0) return alert(response.message);
                    return $('#responser').html(response.message).addClass('text-error').show().delay(3000).fadeOut(100);
                }

                /* The result.message is just a object. */
                if($.type(response.message) == 'object')
                {
                    $.each(response.message, function(key, value)
                    {
                        /* Define the id of the error objecjt and it's label. */
                        var errorOBJ   = '#' + key;
                        var errorLabel =  key + 'Label';

                        /* Create the error message. */
                        var errorMSG = '<label id="'  + errorLabel + '" class="text-error">';
                        errorMSG += $.type(value) == 'string' ? value : value.join(';');
                        errorMSG += '</label>';

                        /* Append error message, set style and set the focus events. */
                        $('#' + errorLabel).remove(); 
                        $(errorOBJ).after(errorMSG);
                        $(errorOBJ).css('margin-bottom', 0);
                        $(errorOBJ).css('border-color','#953B39')
                        $(errorOBJ).focus(function()
                        {
                            $(this).removeAttr('style')
                            $('#' + errorLabel).remove(); 
                        });
                    })
                }
            },

            /* When error occers, alert the response text, status and error. */
            error:function(jqXHR, textStatus, errorThrown)
            {
                $.enableForm(formID);
                alert(jqXHR.responseText + textStatus + errorThrown);
            }
        };

        /* Call ajaxSubmit to sumit the form. */
        form.submit(function()
        { 
             $(this).ajaxSubmit(options);
             return false;    // Prevent the submitting event of the browser.
         });
    },

    /* Disable a form. */
    disableForm:function(formID)
    {
        $(formID).find(':submit').attr('disabled', true);
    },
    
    /* Enable a form. */
    enableForm:function(formID)
    {
        $(formID).find(':submit').attr('disabled', false);
    }
});

$(document).ready(function()
{
    $.ajaxForm('.ajaxForm')
})
