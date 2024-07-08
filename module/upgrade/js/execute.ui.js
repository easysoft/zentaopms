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
