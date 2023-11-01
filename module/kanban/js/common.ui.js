window.changeType = function()
{
    const type = $('[name=type]:checked').val();
    $('.ownerBox').toggle(type != 'private');
    $('.teamBox').toggle(type != 'private');
    $('.whitelistBox').toggle(type == 'private');
}
