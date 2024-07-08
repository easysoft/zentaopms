$(document).ready(function()
{
  ajaxInstallDuckdb();
  ajaxCheckDuckdb();
});

function ajaxInstallDuckdb()
{
  let url = $.createLink('install', 'ajaxInstallDuckdb');
  $.get(url);
}

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
