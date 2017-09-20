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
                    options.url = options.url.indexOf('&') >= 0 ? options.url + '&HTTP_X_REQUESTED_WITH=XMLHttpRequest' : options.url + '?HTTP_X_REQUESTED_WITH=XMLHttpRequest';
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
                    if(response.message && response.message.length)
                    {
                        submitButton = $(formID).find(':submit');
                        var placement = response.placement ? response.placement : 'right';
                        submitButton.popover({trigger:'manual', content:response.message, placement:placement}).popover('show');
                        submitButton.next('.popover').addClass('popover-success');
                        function distroy(){submitButton.popover('destroy')}
                        setTimeout(distroy,2000);
                    }

                    if($.isFunction(callback)) return callback(response);

                    if($('#responser').length && response.message && response.message.length)
                    {
                        $('#responser').html(response.message).addClass('red f-12px').show().delay(3000).fadeOut(100);
                    }

                    if(response.closeModal) setTimeout($.zui.closeModal, 1200);

                    if(response.callback)
                    {
                        var rcall = window[response.callback];
                        if($.isFunction(rcall))
                        {
                            if(rcall() === false) return;
                        }
                    }

                    if(response.locate)
                    {
                        if(response.locate == 'loadInModal')
                        {
                            var modal = $('.modal');
                            setTimeout(function()
                            {
                                modal.load(modal.attr('ref'), function(){$(this).find('.modal-dialog').css('width', $(this).data('width')); $.zui.ajustModalPosition()})
                            }, 1000);
                        }
                        else
                        {
                            var reloadUrl = response.locate == 'reload' ? location.href : response.locate;
                            setTimeout(function(){location.href = reloadUrl;}, 1200);
                        }
                    }

                    if(response.ajaxReload)
                    {
                        var $target = $(response.ajaxReload);
                        if($target.length === 1)
                        {
                            $target.load(document.location.href + ' ' + response.ajaxReload, function()
                            {
                                $target.dataTable();
                                $target.find('[data-toggle="modal"]').modalTrigger();
                            });
                        }
                    }

                    return true;
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
                        var errorLabel = key + 'Label';
                        var i          = triggered.push(false);

                        /* Create the error message. */
                        var errorMSG = '<div id="'  + errorLabel + '" class="text-danger red">';
                        errorMSG += $.type(value) == 'string' ? value : value.join(';');
                        errorMSG += '</div>';

                        /* Append error message, set style and set the focus events. */
                        $('#' + errorLabel).remove(); 
                        var isInputGroup = $(errorOBJ).closest('.input-group').size();
                        var $showOBJ     = isInputGroup ? $(errorOBJ).closest('.input-group').parent() : $(errorOBJ).parent();
                        $showOBJ.append(errorMSG);
                        $(errorOBJ).css('margin-bottom', 0);
                        $(errorOBJ).css('border-color','#D2322D');

                        if($(errorOBJ + '_chosen').size() > 0)
                        {
                            $(errorOBJ + '_chosen .chosen-single').css('border-color','#D2322D');
                            $(errorOBJ + '_chosen .chosen-single').bind('keydown mousedown', function()
                            {
                                if(!triggered[i])
                                {
                                    $(this).removeAttr('style')
                                    $('#' + errorLabel).remove(); 
                                    triggered[i] = true;
                                }
                            });
                        }

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
