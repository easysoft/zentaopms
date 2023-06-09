$(function()
{
    /**
     * Fresh submit btton by enable SMTP checkboc value and verified result of SMTP account.
     *
     * @access public
     * @return void
     */
    function freshSubmitBtn()
    {
        var enableSMTP   = $('#smtpForm input[type=checkbox]:checked').length > 0;
        var accountRight = $('#verifyAccountBtn').attr('pass') == 'true';

        if(enableSMTP && accountRight)
        {
          $('#smtpForm button[type=submit]').attr('disabled', false);
        }
        else
        {
          $('#smtpForm button[type=submit]').attr('disabled', true);
        }
    }

    $('#smtpForm input[type=checkbox]').on('change', function(event)
    {
        freshSubmitBtn();
    });

    $('#smtpForm input[type=checkbox]').change();

    $('#verifyAccountBtn').on('click', function(event)
    {
        var settings = {};
        settings.host     = $("input[name='host']").val();
        settings.port     = $("input[name='port']").val();
        settings.user     = $("input[name='user']").val();
        settings.pass     = $("input[name='pass']").val();
        if(!settings.host || !settings.port || !settings.user || !settings.pass)
        {
            bootbox.alert(
            {
                title:   notices.attention,
                message: notices.fillAllRequiredFields,
            });
            return;
        }

        $.post(createLink('system', 'ajaxVerifySMTPAccount'), settings).done(function(response)
        {
            try
            {
                var res = JSON.parse(response);
            }
            catch(error)
            {
                var res = {result: 'fail', message: errors.verifySMTPFailed,};
            }
            $('#verifyResult').html(res.message);
            if(res.result == 'success')
            {
                $('#verifyAccountBtn').attr('pass', 'true');
                $('#verifyResult').removeClass('text-red').addClass('text-success');
                freshSubmitBtn();
            }
            else
            {
                $('#verifyAccountBtn').attr('pass', 'false');
                $('#verifyResult').removeClass('text-success').addClass('text-red');
                freshSubmitBtn();
            }
        });
    });
});
