window.submitConfirm = function(event)
{
    $(event.target).addClass('disabled');
    zui.Modal.open({id: 'progress'});

    updateProgressInterval();
    updateProgress();
}

window.copyCommand = function()
{
    const command = $('#command').text();
    const $textArea = $('<textarea>', {
        css: {
            position: 'fixed',
            top: '0',
            left: '0',
            width: '2em',
            height: '2em',
            padding: '0',
            border: 'none',
            outline: 'none',
            boxShadow: 'none',
            background: 'transparent'
        },
        val: command
    });

    $('body').append($textArea);

    $textArea[0].focus();
    $textArea[0].select();

    try {
        const successful = document.execCommand('copy');
        if(successful)
        {
            zui.Messager.show({type: 'success', message: copySuccess, timeout: 1000});
        } else {
            zui.Messager.show({type: 'danger', message: copyFail, timeout: 1000});
        }
    } catch (err) {
        zui.Messager.show({type: 'danger', message: copyFail, timeout: 1000});
    }
    $textArea.remove();
}

$(document).ready(function()
{
    if(typeof result != 'undefined' && result == 'duckdbFail')
    {
        $('#duckdbInfo').append($('#installDuckdb'));
        initStatus();
        ajaxInstallDuckdb();
        setTimeout(() => {ajaxCheckDuckdb()}, 1000);
    }
});

/**
 * 初始化状态。
 * Init status.
 *
 * @access public
 * @return void
 */
function initStatus()
{
    $('#refreshBtn').addClass('hidden');
    $('#installDuckdb p').addClass('hidden');
    $('#installDuckdb p.duckdb-' + duckdb).removeClass('hidden');
    $('#installDuckdb p.ext_dm-' + ext_dm).removeClass('hidden');
    $('#installDuckdb p.ext_mysql-' + ext_mysql).removeClass('hidden');
}

/**
 * 安装duckdb。
 * Ajax install duckdb.
 *
 * @access public
 * @return void
 */
function ajaxInstallDuckdb()
{
    let url = $.createLink('upgrade', 'ajaxInstallDuckdb');
    $.get(url);
}

/**
 * 检查duckdb。
 * Ajax check duckdb.
 *
 * @access public
 * @return void
 */
function ajaxCheckDuckdb()
{
    let url = $.createLink('upgrade', 'ajaxCheckDuckdb');
    $.get(url, function(resp)
    {
        resp = JSON.parse(resp);
        const {loading, ok, fail, duckdb, ext_dm, ext_mysql} = resp;
        if(loading) setTimeout(() => {ajaxCheckDuckdb()}, 500);

        if(!loading)
        {
            $('#refreshBtn').removeClass('hidden');
            $('.after-duckdb').addClass('hidden');
        }

        $('#installDuckdb p').addClass('hidden');
        $('#installDuckdb p.duckdb-' + duckdb).removeClass('hidden');
        $('#installDuckdb p.ext_dm-' + ext_dm).removeClass('hidden');
        $('#installDuckdb p.ext_mysql-' + ext_mysql).removeClass('hidden');

        if(fail) $('#installDuckdb .help').removeClass('hidden');
    });
}
