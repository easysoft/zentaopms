window.changeUser = function()
{
    const picker = $('#user').zui('picker');
    const user   = picker ? picker.$.value : '';
    const type   = user ? 'account' : 'today';
    loadPage($.createLink('execution', 'dynamic', `productID=${executionID}&type=${type}&param=${user}`));
}

window.toggleCollapse = function()
{
    $(this).parent().toggleClass('collapsed');
}
