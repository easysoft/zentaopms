window.changeUser = function()
{
    const picker = $('#user').zui('picker');
    const user   = picker ? picker.$.value : '';
    const type   = user ? 'account' : 'today';
    loadPage($.createLink('project', 'dynamic', `productID=${projectID}&type=${type}&param=${user}`));
}

window.toggleCollapse = function()
{
    $(this).parent().toggleClass('collapsed');
}
