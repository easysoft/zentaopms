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
