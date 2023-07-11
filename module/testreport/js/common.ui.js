$().ready(function()
{
    if($('#goalTip').length)         new zui.Tooltip('#goalTip',         {title: goalTip,         trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    if($('#foundBugTip').length)     new zui.Tooltip('#foundBugTip',     {title: foundBugTip,     trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    if($('#legacyBugTip').length)    new zui.Tooltip('#legacyBugTip',    {title: legacyBugTip,    trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    if($('#activatedBugTip').length) new zui.Tooltip('#activatedBugTip', {title: activatedBugTip, trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    if($('#fromCaseBugTip').length)  new zui.Tooltip('#fromCaseBugTip',  {title: fromCaseBugTip,  trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
});
