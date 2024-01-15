window.toggleWhitelist = function(e)
{
    const acl = $(e.target).val();
    if(acl == 'open')
    {
        $('.whitelistBox').addClass('hidden');
    }
    else
    {
        $('.whitelistBox').removeClass('hidden');
    }
}
