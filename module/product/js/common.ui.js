function setWhite(obj)
{
    var $obj = $(obj);
    var acl  = $obj.val();
    acl != 'open' ? $('#whitelistBox').removeClass('hidden') : $('#whitelistBox').addClass('hidden');
}

