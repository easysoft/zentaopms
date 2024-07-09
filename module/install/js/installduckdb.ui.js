$(document).ready(function()
{
    ajaxInstallDuckdb();
    setTimeout(() => {ajaxCheckDuckdb()}, 1000);
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
        const {loading, ok, fail, duckdb, ext_dm, ext_mysql} = resp;
        if(loading) setTimeout(() => {ajaxCheckDuckdb()}, 500);

        $('#installDuckdb p').addClass('hidden');
        $('#installDuckdb p.duckdb-' + duckdb).removeClass('hidden');
        $('#installDuckdb p.ext_dm-' + ext_dm).removeClass('hidden');
        $('#installDuckdb p.ext_mysql-' + ext_mysql).removeClass('hidden');

        if(fail) $('#installDuckdb .help').removeClass('hidden');
    });
}
