$.extend(
{
    ajaxForm: function(formID, callback)
    {
        form = $(formID); 
        var options = 
        {
            target  : null,
            timeout : config.timeout,
            dataType:'json',

            success:function(response)
            {
                $.enableForm(formID);

                /* try parse to json when response is json's string. */
                try{if(typeof(response) == 'string') response = JSON.parse(response);}catch(e){}

                /* The response is not an object, some error occers, alert it. */
                if(typeof(response) != 'object')
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
                    var triggered = new Array();
                    $.each(response.message, function(key, value)
                    {
                        /* Define the id of the error objecjt and it's label. */
                        var errorOBJ   = '#' + key;
                        var errorLabel =  key + 'Label';
                        var i          = triggered.push(false);

                        /* Create the error message. */
                        var errorMSG = '<div id="'  + errorLabel + '" class="text-danger red">';
                        errorMSG += $.type(value) == 'string' ? value : value.join(';');
                        errorMSG += '</div>';

                        /* Append error message, set style and set the focus events. */
                        $('#' + errorLabel).remove(); 
                        $(errorOBJ).parent().append(errorMSG);
                        $(errorOBJ).css('margin-bottom', 0);
                        $(errorOBJ).css('border-color','#D2322D');

                        $(errorOBJ).bind('keydown mousedown', function()
                        {
                            if(!triggered[i])
                            {
                                $(this).removeAttr('style')
                                $('#' + errorLabel).remove(); 
                                triggered[i] = true;
                            }
                        });

                        if(i == 1)$(errorOBJ).focus();

                        setTimeout(function(){$('#' + errorLabel).remove();}, 5000);
                    })
                }
            },

            /* When error occers, alert the response text, status and error. */
            error:function(jqXHR, textStatus, errorThrown)
            {
                $.enableForm(formID);
                if(textStatus == 'timeout' || textStatus == 'error') return alert(lang.timeout);
                alert(jqXHR.responseText + textStatus + errorThrown);
            }
        };

        /* Call ajaxSubmit to sumit the form. */
        form.submit(function()
        { 
            $(this).trigger('clearplaceholder');
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
    $.ajaxForm('.ajaxForm');
    $.ajaxForm("[data-type='ajax']");
})
