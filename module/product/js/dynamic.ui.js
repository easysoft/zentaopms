window.onChangeUser = function(event)
{
    const picker = $(event.target).zui('picker');
    const user   = picker ? picker.$.value : '';
    const type   = user ? 'account' : 'today';
    loadPage($.createLink('product', 'dynamic', `productID=${productID}&type=${type}&param=${user}`));
};

window.toggleCollapse = function(event)
{
    $(event.target).parent().parent().toggleClass('collapsed');
};
