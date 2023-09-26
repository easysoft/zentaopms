$(function()
{
    setInterval(function()
    {
        $.get(createLink('install', 'ajaxProgress', 'id='+ solutionID)).done(function(response)
        {
            var res = JSON.parse(response);
            if(res.result == 'success')
            {
                var installed = true;
                for(var index in res.data)
                {
                    var cloudApp = res.data[index];
                    if(cloudApp.status == 'waiting')
                    {
                        $('.arrow.app-' + cloudApp.id).removeClass('active');
                        $('.step.app-' + cloudApp.id + ' .step-no').removeClass('active');
                    }
                    else
                    {
                        $('.arrow.app-' + cloudApp.id).addClass('active');
                        $('.step.app-' + cloudApp.id + ' .step-no').addClass('active');
                    }

                    if(cloudApp.status == 'installing' || cloudApp.status == 'installed')
                    {
                        $('#' + cloudApp.alias + '-status').text(installLabel);
                    }

                    if(cloudApp.status == 'configured')
                    {
                        $('#' + cloudApp.alias + '-status').text(configLabel);
                    }

                    if(cloudApp.status != 'installed' && cloudApp.status != 'configured')
                    {
                        installed = false;
                    }

                    if(cloudApp.status == 'error')
                    {
                        $('.error-message').text(res.message);
                        $('.progress.loading').hide();
                    }
                    if(res.logs.hasOwnProperty(cloudApp.chart))
                    {
                        if(!shownLogs.hasOwnProperty(cloudApp.chart)) shownLogs[cloudApp.chart] = [];
    
                        var logs = res.logs[cloudApp.chart]
                        for(var j in logs)
                        {
                            if(shownLogs[cloudApp.chart].indexOf(logs[j].content) == -1)
                            {
                                term.write(logs[j].content + '\n');
                                shownLogs[cloudApp.chart].push(logs[j].content);
                            }
                        }
                    }
                }

                if(installed)
                {
                    $('.progress-message').text(notices.installationSuccess);
                    window.location.href = createLink('install', 'step6');
                }
            }
            else
            {
                $('#retryInstallBtn').show();
                $('#skipInstallBtn').show();
                $('#cancelInstallBtn').hide();
                $('.progress.loading').hide();

                var errMessage = res.message;
                if(res.message instanceof Array) errMessage = res.message.join('<br/>');
                if(res.message instanceof Object) errMessage = Object.values(res.message).join('<br/>');

                $('.error-message').text(errMessage);
            }
        });
    }, 4000);

    $('#cancelInstallBtn').on('click', function()
    {
        $('#cancelInstallBtn').attr('disabled', true);

        bootbox.confirm(notices.cancelInstall, function(result)
        {
            $('#cancelInstallBtn').attr('disabled', false);
            if(!result) return;

            var loadingDialog = bootbox.dialog(
            {
                message: '<div class="text-center"><i class="icon icon-spinner-indicator icon-spin"></i>&nbsp;&nbsp;' + notices.uninstallingSolution + '</div>',
            });

            $.post(createLink('install', 'ajaxUninstall', 'id=' + solutionID), function(response)
            {
                loadingDialog.modal('hide');
                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    parent.window.location.href = res.locate;
                }
            });
        });
    });

    $('#retryInstallBtn').on('click', function()
    {
        $('#retryInstallBtn').attr('disabled', true);

        bootbox.confirm(notices.confirmReinstall, function(result)
        {
            $('#retryInstallBtn').attr('disabled', false);

            if(!result) return;

            $('#retryInstallBtn').hide();
            $('#skipInstallBtn').hide();
            $('#cancelInstallBtn').show();
            $('.error-message').text('');
            $('.progress.loading').show();

            $.post(createLink('install', 'ajaxInstall', 'id=' + solutionID), function(response)
            {
                var res = JSON.parse(response);
                if(res.result == 'success')
                {
                    parent.window.location.href = res.locate;
                }
                else
                {
                    bootbox.alert(
                    {
                        title:   errors.error,
                        message: res.message
                    });
                }
            });
        });
    });

    $('#retryInstallBtn').hide();
    $('#skipInstallBtn').hide();
    $('#cancelInstallBtn').show();

    if('true' === startInstall)
    {
        $.get(createLink('install', 'ajaxInstall', 'id=' + solutionID)).done(function(response){});
    }

    term = new Terminal({convertEol: true, rows: 20});
    term.open(document.getElementById('terminal'));
});

var shownLogs = [];