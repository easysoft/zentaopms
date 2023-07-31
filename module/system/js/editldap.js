$(function()
{
    /**
     * Fresh submit button.
     *
     * @access public
     * @return void
     */
    function freshSubmitBtn()
    {
        var enableLDAP    = $('#LDAPForm input[type=checkbox]:checked').length > 0;
        var ldapCheckPass =  true;
        if($('#LDAPForm select[name=source]').val() == 'extra')
        {
            ldapCheckPass = $('#testConnectBtn').attr('pass') == 'true';
        }

        if(enableLDAP && ldapCheckPass)
        {
            $('#LDAPForm #submitBtn').attr('disabled', false);
        }
        else
        {
            $('#LDAPForm #submitBtn').attr('disabled', true);
        }
    }

    freshSubmitBtn();

    $('#LDAPForm input[type=checkbox]').on('change', function(event)
    {
        freshSubmitBtn();
    });

    $('#LDAPForm input[type=checkbox]').change();

    $('select[name=source]').on('change', function(event)
    {
        if($(event.target).val() == 'qucheng')
        {
            $('#quchengLDAP').show();
            $('#extraLDAP').hide();
        }
        else
        {
            $('#quchengLDAP').hide();
            $('#extraLDAP').show();
        }

        freshSubmitBtn();
    });
    $('select[name=source]').change();

    $('#testConnectBtn').on('click', function(event)
    {
        var settings = {};
        settings.host     = $('input[name="extra[host]"]').val();
        settings.port     = $('input[name="extra[port]"]').val();
        settings.bindDN   = $('input[name="extra[bindDN]"]').val();
        settings.bindPass = $('input[name="extra[bindPass]"]').val();
        settings.baseDN   = $('input[name="extra[baseDN]"]').val();
        if(!settings.host || !settings.port || !settings.bindDN || !settings.bindPass || !settings.baseDN)
        {
            bootbox.alert(
            {
                title:   notices.attention,
                message: notices.fillAllRequiredFields,
            });
            return;
        }

        $.post(createLink('system', 'testLDAPConnection'), settings).done(function(response)
        {
            try
            {
                var res = JSON.parse(response);
            }
            catch(error)
            {
                var res = {result: 'fail', message: errors.verifyLDAPFailed,};
            }
            $('#connectResult').html(res.message);
            if(res.result == 'success')
            {
                $('#testConnectBtn').attr('pass', 'true');
                $('#connectResult').removeClass('text-red').addClass('text-success');
                freshSubmitBtn();
            }
            else
            {
                $('#testConnectBtn').attr('pass', 'false');
                $('#connectResult').removeClass('text-success').addClass('text-red');
                freshSubmitBtn();
            }
        });
    });

    var timerID = 0;

    /**
     * Show updating LDAP progress modal.
     *
     * @access public
     * @return void
     */
    function showProgressModal()
    {
        $('#waiting').modal('show');
        timerID = setInterval(function()
        {
            $.get(createLink('system', 'ajaxUpdatingLDAPProgress'), function(data)
            {
                $('#waiting #message').html(data);
            });
        }, 1000);
    };

    $('#submitBtn').on('click', function()
    {
        bootbox.confirm(notices.confirmUpdateLDAP, function(result)
        {
            if(!result) return;

            showProgressModal();
            $('#submitBtn').attr('disabled', true);

            var ldapData = $('#LDAPForm').serializeArray();
            $.post(createLink('system', 'editLDAP'), ldapData).done(function(response)
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
