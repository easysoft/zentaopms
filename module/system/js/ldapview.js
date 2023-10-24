$(function()
{
    $('.btn-visit').on('click', function(event)
    {
        $.get(createLink('system', 'ajaxLdapInfo'), function(response)
        {
            let res = JSON.parse(response);
            if(res.result == 'success')
            {
                $('#ldapAccount').text(res.data.account);
                $('#ldapPassword').val(res.data.pass);
                $('#ldapVisitUrl').attr('href', '//' + res.data.domain);
                $('#ldapAccountModal').modal('show');
            }
        });
    });

    $('#ldapPassBtn').on('click', function(event){
        var inputTyep = $('#ldap_password').attr('type');
        if(inputTyep == 'text'){
            $('#ldap_password').attr('type', 'password');
            $('#ldapPassBtn').find('.icon').removeClass('icon-eye').addClass('icon-eye-off');
        }
        else
        {
            $('#ldap_password').attr('type', 'text');
            $('#ldapPassBtn').find('.icon').addClass('icon-eye').removeClass('icon-eye-off');
        }
    });

    $('#copyPassBtn').on('click', function(event)
    {
        $('#ldapPassword').select();
        document.execCommand('copy');
        bootbox.alert(copySuccess);
    });

    $('.btn-start').on('click', function(event)
    {
        bootbox.confirm(instanceNotices.confirmStart, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.starting + '</div>',
            });

            let id  = $(event.target).closest('button').attr('instance-id');
            let url = createLink('instance', 'ajaxStart', 'id=' + id, 'json');
            $.post(url).done(function(response)
            {
                loadingDialog.modal('hide');

                let res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });
        });
    });

    $('.btn-stop').on('click', function(event)
    {
        bootbox.confirm(instanceNotices.confirmStop, function(result)
        {
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + instanceNotices.stopping + '</div>',
            });

            let id  = $(event.target).closest('button').attr('instance-id');
            let url = createLink('instance', 'ajaxStop', 'id=' + id, 'json');
            $.post(url).done(function(response)
            {
                loadingDialog.modal('hide');

                let res = JSON.parse(response);
                if(res.result == 'success')
                {
                    window.location.reload();
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   instanceNotices.fail,
                        message: res.message,
                    });
                }
            });
        });
    });

    setInterval(function()
    {
        var mainMenu = parent.window.$.apps.getLastApp();
        if(mainMenu.code != 'system') return;

        var statusURL = createLink('instance', 'ajaxStatus');
        $.post(statusURL, {idList: instanceIdList}).done(function(response)
        {
            let res = JSON.parse(response);
            if(res.result == 'success' && res.data instanceof Array)
            {
                res.data.forEach(function(instance)
                {
                    if($(".instance-status[instance-id=" + instance.id + "]").data('status') != instance.status) window.location.reload();
                });
            }
            if(res.locate) window.parent.location.href = res.locate;
        });
    }, 1000 * 5);

    $('[data-toggle="tooltip"]').tooltip();
});
