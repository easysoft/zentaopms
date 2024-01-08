/**
 * 获取应用安装进度。
 * Get solution install progress.
 *
 * @access public
 * @return void
 */
function getInstallProgress()
{
    $.get($.createLink('install', 'ajaxProgress', 'id='+ solutionID)).done(function(response)
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
                if(!isModal) loadPage($.createLink('install', 'step6'));
            }
        }
        else
        {
            $('#retryInstallBtn').removeClass('hidden');
            $('#skipInstallBtn').removeClass('primary');
            $('#skipInstallBtn').text(skipLang);
            $('#cancelInstallBtn').addClass('hidden');

            var errMessage = res.message;
            if(res.message instanceof Array) errMessage = res.message.join('<br/>');
            if(res.message instanceof Object) errMessage = Object.values(res.message).join('<br/>');

            $('.error-message').text(errMessage);
        }
    });
}

window.retryInstall = function()
{
    $('#retryInstallBtn').attr('disabled', true);

    zui.Modal.confirm(notices.confirmReinstall).then((result) =>
    {
        $('#retryInstallBtn').removeAttr('disabled');
        if(!result) return;

        $('#retryInstallBtn').addClass('hidden');
        $('#skipInstallBtn').text(backgroundLang);
        $('#skipInstallBtn').addClass('primary');
        $('#cancelInstallBtn').removeClass('hidden');
        $('.error-message').text('');
        $.get($.createLink('install', 'ajaxInstall', 'id=' + solutionID));
    });
}

window.cancelInstall = function()
{
    $('#cancelInstallBtn').attr('disabled', true);

    zui.Modal.confirm(notices.cancelInstall).then((result) =>
    {
        $('#cancelInstallBtn').removeAttr('disabled');
        if(!result) return;

        toggleLoading('#mainContent', true);
        $.ajaxSubmit({
            url: $.createLink('install', 'ajaxUninstall', 'id=' + solutionID),
            onComplete: function(response)
            {
                toggleLoading('#mainContent', false);
            }
        });
    });
}

$(function()
{
    $.getLib(config.webRoot + 'js/xterm/xterm.js', {root: false}, function()
    {
        term = new Terminal({convertEol: true, rows: 20});
        term.open(document.getElementById('terminal'));
    });

    if(startInstall) $.get($.createLink('install', 'ajaxInstall', 'id=' + solutionID));

    setInterval(getInstallProgress, 4000);
});
