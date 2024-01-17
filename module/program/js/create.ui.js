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

window.onDateChange = () =>
{
    let programBegin = $('[name=begin]').val();
    let programEnd   = $('[name=end]').val();

    if(programBegin && parentBeginDate && new Date(parentBeginDate) > new Date(programBegin))
    {
        $('#beginTip').text(beginLessThanParent + parentBeginDate).removeClass('hidden');
    }

    if(programEnd && parentEndDate && new Date(parentEndDate) < new Date(programEnd))
    {
        $('#beginTip').text(endGreatThanParent + parentEndDate).removeClass('hidden');
    }
};
