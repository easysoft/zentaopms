window.submitConfirm = function(event) {
    $(event.target).addClass('disabled');
    zui.Modal.open({id: 'progress'});

    updateProgressInterval();
    updateProgress();
}

$(document).ready(function()
{
    if(result == 'duckdbFail')
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
    $('#installDuckdb p').addClass('hidden');
    $('#installDuckdb p.duckdb-' + duckdb).removeClass('hidden');
    $('#installDuckdb p.extension-' + extension).removeClass('hidden');
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
    let url = $.createLink('bi', 'ajaxInstallDuckdb');
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
    let url = $.createLink('bi', 'ajaxCheckDuckdb');
    $.get(url, function(resp)
    {
        resp = JSON.parse(resp);
        if(resp.duckdb == 'loading' || resp.extension == 'loading')
        {
            setTimeout(() => {ajaxCheckDuckdb()}, 500);
        }

        $('#installDuckdb p').addClass('hidden');
        $('#installDuckdb p.duckdb-' + resp.duckdb).removeClass('hidden');
        $('#installDuckdb p.extension-' + resp.extension).removeClass('hidden');

        if(resp.duckdb == 'fail' || resp.extension == 'fail')
        {
            $('#installDuckdb .help').removeClass('hidden');
        }
    });
}
