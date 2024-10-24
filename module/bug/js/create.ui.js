window.reloadByProduct = function(e)
{
    loadPage($.createLink('bug', 'create', 'productID=' + $(e.target).val() + '&branch=0&extra=projectID=' + projectID + ',executionID=' + executionID));
}
