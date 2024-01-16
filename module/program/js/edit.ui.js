window.onFutureChange = (event) =>
{
    let checked = $(event.target).prop('checked');

    $('[name=budget]').val('').attr('disabled', checked ? 'disabled' : null);
    $('[name=budgetUnit]').zui('picker').render({'disabled': checked});
    $('#budgetTip').remove();
};

window.outOfDateTip = function()
{
    console.warn('The method outOfDateTip is not implemented.');
};

window.onAclChange = () =>
{
    $('[data-name=whitelist]').toggleClass('hidden', $('#aclopen').prop('checked'));
};
