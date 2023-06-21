$().ready(function()
{
    if($('#goalTip').length) new zui.Tooltip('#goalTip', {title: goalTip, trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    new zui.Tooltip('#foundBugTip',     {title: foundBugTip,     trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    new zui.Tooltip('#legacyBugTip',    {title: legacyBugTip,    trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    new zui.Tooltip('#activatedBugTip', {title: activatedBugTip, trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    new zui.Tooltip('#fromCaseBugTip',  {title: fromCaseBugTip,  trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
});

function changeTesttask(event)
{
    const taskID = $(event.target).val();
    const url    = $.createLink('testreport', 'create', 'objectID=' + taskID + '&objectType=testtask');
    loadPage(url);
}
