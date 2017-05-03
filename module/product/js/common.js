function setWhite(acl)
{
    acl == 'custom' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

$(document).ready(function()
{
    if(onlyStory) $('#aclprivate').parents('.radio').remove();
})
