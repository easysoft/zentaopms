var ajaxFormUrl = '';
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

            beforeSubmit:function(arr, $form, options)
            {
                /* Check should use frame. */
                var feature = {};
                feature.fileapi = $("<input type='file'/>").get(0).files !== undefined;
                feature.formdata = window.FormData !== undefined;
                var fileInputs = $('input[type=file]:enabled', this).filter(function() { return $(this).val() !== ''; });
                var hasFileInputs = fileInputs.length > 0;
                var mp = 'multipart/form-data';
                var multipart = ($form.attr('enctype') == mp || $form.attr('encoding') == mp);

                var fileAPI = feature.fileapi && feature.formdata;
                var shouldUseFrame = (hasFileInputs || multipart) && !fileAPI;
                /* Append HTTP_X_REQUESTED_WITH on url when shouldUseFrame is true. */
                if(shouldUseFrame)
                {
                    if(ajaxFormUrl == '')ajaxFormUrl = options.url;
                    if(options.url != ajaxFormUrl) options.url = ajaxFormUrl;
                    options.url = options.url.indexOf('&') >= 0 ? options.url + '&HTTP_X_REQUESTED_WITH=true' : options.url + '?HTTP_X_REQUESTED_WITH=true';
                }
            },

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
                    var errorMSG  = '';
                    var firstOBJ  = '';
                    $.each(response.message, function(key, value)
                    {
                        /* Define the id of the error objecjt and it's label. */
                        var errorOBJ   = '#' + key;
                        var errorLabel = key + 'Label';
                        var i          = triggered.push(false);

                        /* Create the error message. */
                        errorMSG += '<div id="'  + errorLabel + '" class="text-danger red">';
                        errorMSG += $.type(value) == 'string' ? value : value.join(';');
                        errorMSG += '</div>';

                        if(i == 1)firstOBJ = errorOBJ;
                    })
                    if(errorMSG)bootbox.alert(errorMSG, function(){setTimeout(function(){$('body').click();if(firstOBJ)$(firstOBJ).focus();}, 100)});
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
