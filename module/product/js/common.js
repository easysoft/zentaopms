$(document).ready(function()
{
    if(window.noProject) $('#aclprivate').parents('.radio').remove();
})

function setWhite(acl)
{
    acl != 'open' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

