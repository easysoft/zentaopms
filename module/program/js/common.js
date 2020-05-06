$().ready(function()
{
    $('#navbar').find('li').removeClass('active');
    if(typeof(status) == 'undefined' || !status) status = 'all';
    $('#navbar').find('a[href*=' + status + ']').parent().addClass('active');
});
function setWhite(acl)
{
    acl == 'custom' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

function setProgramType(type)
{
    $.cookie('programType', type, {expires:config.cookieLife, path:config.webRoot});
    location.href = location.href;
}
