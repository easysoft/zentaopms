$(function()
{
    if(appTab != 'devops' && !hasProduct) zui.Modal.alert(noProductTip);
});

window.importJob = function(repoID)
{
    var url = $.createLink('job', 'ajaxImportJobs', "repoID=" + repoID);
    $.getJSON(url);
}
