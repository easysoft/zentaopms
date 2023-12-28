function onHttpsChange(event)
{
    const $https = $(event.target);

    if($https.prop('checked'))
    {
        $('.cert').removeClass('hidden');

        var pass = $('#validateCertBtn').attr('pass') == 'true';
        if(!pass) $('#domainForm button[type=submit]').prop('disabled', true);
    }
    else
    {
        $('.cert').addClass('hidden');
        $('#domainForm button[type=submit]').prop('disabled', false);
    }
}

function checkCert()
{
    var certData = {};
    certData.customDomain = $('#customDomain').val();
    certData.certPem      = $('#certPem').val();
    certData.certKey      = $('#certKey').val();

    $.ajaxSubmit(
    {
        url: $.createLink('system', 'ajaxValidateCert'),
        data: certData,
        onComplete: function(res)
        {
            if(res.result == 'success')
            {
                $('#validateCertBtn').attr('pass', 'true');
                $('#validateMsg').removeClass('text-danger').addClass('text-success');
                $('#validateMsg').html(res.message);
                $('#domainForm button[type=submit]').prop('disabled', false);
            }
            else
            {
                $('#validateCertBtn').attr('pass', 'false');
                $('#validateMsg').removeClass('text-success').addClass('text-danger');
                var errMessage = res.message;
                if(res.message instanceof Array) errMessage = res.message.join('&nbsp;');
                if(res.message instanceof Object) errMessage = Object.values(res.message).join('&nbsp;');

                $('#validateMsg').html(errMessage);
            }
        },
    });
}
