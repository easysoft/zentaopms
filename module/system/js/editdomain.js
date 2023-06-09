$(function()
{
    /**
     * Fresh submitBtn status.
     *
     * @access public
     * @return void
     */
    function freshSubmitBtn()
    {
        if($('#httpstrue:checked').length == 0)
        {
            $('#submitBtn').attr('disabled', false);
            return;
        }
        var pass = $('#validateCertBtn').attr('pass') == 'true';
        if(pass)
        {
            $('#submitBtn').attr('disabled', false);
        }
        else
        {
            $('#submitBtn').attr('disabled', true);
        }
    }

    freshSubmitBtn();

    /**
     * Toggle certificate textarea.
     *
     * @access public
     * @return void
     */
    function toggleCertBox()
    {
        $showCert = $("#httpstrue[type=checkbox]:checked").length > 0;
        if($showCert)
        {
            $('#cert-box').show();
        }
        else
        {
            $('#cert-box').hide();
        }

        freshSubmitBtn();
    }

    toggleCertBox();

    $("#httpstrue[type=checkbox]").on('change', function()
    {
       toggleCertBox();
    });

    $('#validateCertBtn').on('click', function()
    {
        var certData = {};
        certData.customDomain = $('#customDomain').val();
        certData.certPem      = $('#certPem').val();
        certData.certKey      = $('#certKey').val();
        $.post(createLink('system', 'ajaxValidateCert'), certData).done(function(response)
        {
            var res = JSON.parse(response);
            if(res.result == 'success')
            {
                $('#validateCertBtn').attr('pass', 'true');
                $('#validateMsg').removeClass('text-red').addClass('text-green');
                $('#validateMsg').html(res.message);
            }
            else
            {
                $('#validateCertBtn').attr('pass', 'false');
                $('#validateMsg').removeClass('text-green').addClass('text-red');
                var errMessage = res.message;
                if(res.message instanceof Array) errMessage = res.message.join('&nbsp;');
                if(res.message instanceof Object) errMessage = Object.values(res.message).join('&nbsp;');

                $('#validateMsg').html(errMessage);
            }
            freshSubmitBtn();
        });
    });

    var timerID = 0;

    /**
     * Show progress modal and fresh progress of updating domain by ajax.
     *
     * @access public
     * @return void
     */
    function showProgressModal()
    {
        $('#waiting').modal('show');
        timerID = setInterval(function()
        {
            $.get(createLink('system', 'ajaxUpdatingDomainProgress'), function(data)
            {
                $('#waiting #message').html(data);
            });
        }, 1000);
    };

    $('#submitBtn').on('click', function()
    {
        bootbox.confirm(notices.confirmUpdateDomain, function(result)
        {
            if(!result) return;

            showProgressModal();
            $('#submitBtn').attr('disabled', true);

            var domainData = $('#domainForm').serializeArray();
            $.post(createLink('system', 'editDomain'), domainData).done(function(response)
            {
                $('#submitBtn').attr('disabled', false);

                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    parent.window.location.href = res.locate;
                }
                else
                {
                    $('#waiting').modal('hide');
                    clearInterval(timerID);

                    var errMessage = res.message;
                    if(res.message instanceof Array) errMessage = res.message.join('<br/>');
                    if(res.message instanceof Object) errMessage = Object.values(res.message).join('<br/>');

                    bootbox.alert(
                    {
                        title:   notices.fail,
                        message: errMessage,
                    });
                }
            });
        });
    });
});
