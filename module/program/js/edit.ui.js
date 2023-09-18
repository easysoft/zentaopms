function onParentChange(event)
{
    const parentID = $(event.target).val();
    const url      = $.createLink('program', 'create', parentID ? ('parentProgramID=' + parentID) : '');
    loadPage(url, '#budgetRow>*, #acl');
}

window.onFutureChange = (event) =>
{
    $('#budget,#budgetUnit').attr('disabled', $(event.target).prop('checked') ? 'disabled' : null);
    $('#budgetTip').remove();
};

window.outOfDateTip = function()
{
    console.warn('The method outOfDateTip is not implemented.');
};

window.onAclChange = () =>
{
    $('#whitelistRow').toggleClass('hidden', $('#aclopen').prop('checked'));
};
onAclChange();
