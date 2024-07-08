$(document).ready(function()
{
  ajaxInstallDuckdb();
  ajaxCheckDuckdb();
});

/**
 * 安装duckdb。
 * Ajax install duckdb.
 *
 * @access public
 * @return void
 */
function ajaxInstallDuckdb()
{
  let url = $.createLink('install', 'ajaxInstallDuckdb');
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
  let url = $.createLink('install', 'ajaxCheckDuckdb');
  $.get(url, function(resp)
  {
    resp = JSON.parse(resp);
    if(resp.duckdb == 'loading' || resp.extension == 'loading') setTimeout(() => {ajaxCheckDuckdb()}, 500);

    $('#installDuckdb p').addClass('hidden');
    $('#installDuckdb p.duckdb-' + resp.duckdb).removeClass('hidden');
    $('#installDuckdb p.extension-' + resp.extension).removeClass('hidden');

    if(resp.duckdb == 'fail' || resp.extension == 'fail')
    {
      $('#installDuckdb .help').removeClass('hidden');
    }
  });
}
