window.disableBranchType = function()
{
    const isAllBranchTypesDom = $('[name=isAllBranchTypes]');
    if(!isAllBranchTypesDom) return;

    const isAllBranchTypes = isAllBranchTypesDom.is(':checked');

    if(isAllBranchTypes)
    {
        $('#branchTypesBox').attr('disabled', 'disabled');
        disableBranchTypeMenu(true);
        $('[name^=branchType]').zui('picker').$.setValue([0]);
    }
    else
    {
        disableBranchTypeMenu(false);
        if(typeof editBranchTypes != 'undefined') $('[name^=branchType]').zui('picker').$.setValue(editBranchTypes);
    }
}
window.waitDom('[name^=branchType]', disableBranchType);

window.loadAiReviewScores = function()
{
    const aiReviewDom = $('[name=aiReview]:checked');
    if(!aiReviewDom) return;

    const aiReview = aiReviewDom.val();
    if(aiReview == 'enable') $('#aiReviewScores').removeClass('hidden');
    else $('#aiReviewScores').addClass('hidden');
}
window.waitDom('[name=aiReview]', loadAiReviewScores);

window.loadIssueType = function()
{
    const addressOptionDom = $('[name=addressOption]:checked');
    if(!addressOptionDom) return;

    const addressOption = addressOptionDom.val();
    if(addressOption == 'specificMustBeSolved') $('#issueType').removeClass('hidden');
    else $('#issueType').addClass('hidden');
}
window.waitDom('[name=addressOption]', loadIssueType);

window.addSpecifiedReviewers = function(values)
{
    if(!values || values.length == 0) return;
    const $specifiedReviewers = $('[name^=specifiedReviewers]');
    if(!$specifiedReviewers) return;
    const newSpecifiedReviewers = $specifiedReviewers.val().concat(values);
    $specifiedReviewers.zui('picker').$.setValue(newSpecifiedReviewers);
}

window.removeSpecifiedReviewers = function(values)
{
    if(!values || values.length == 0) return;
    const $specifiedReviewers = $('[name^=specifiedReviewers]');
    if(!$specifiedReviewers) return;

    const newSpecifiedReviewers = $specifiedReviewers.val().filter(v => !values.includes(v));
    $specifiedReviewers.zui('picker').$.setValue(newSpecifiedReviewers);
}

window.disableBranchTypeMenu = function(isDisable)
{
    const $branchType = $('[name^=branchType]');
    if(typeof $branchType == 'undefined') return;

    if(isDisable)
    {
        $('#branchTypesBox').attr('disabled', 'disabled');
    }
    else
    {
        $('#branchTypesBox').removeAttr('disabled');
    }
    const menus = $branchType.zui('picker').options.items;
    menus.forEach(menu => {
        menu.disabled = isDisable;
    })
}
