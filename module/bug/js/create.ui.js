window.reloadByProduct = function(e)
{
    let extra = 'bugID=' + copyBugID + ',projectID=' + projectID + ',executionID=' + executionID;
    if(typeof fromID != 'undefined') extra += ',fromID=' + fromID;
    if(typeof fromType != 'undefined') extra += ',fromType=' + fromType;
    loadPage($.createLink('bug', 'create', 'productID=' + $(e.target).val() + '&branch=0&extra=' + extra));
}
