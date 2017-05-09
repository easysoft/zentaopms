function setWhite(acl)
{
    acl == 'custom' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

$(document).ready(function()
{
    if(window.noProject) $('#aclprivate').parents('.radio').remove();
})
