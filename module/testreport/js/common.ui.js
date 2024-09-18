$().ready(function()
{
    if($('#goalTip').length)         new zui.Tooltip('#goalTip',         {title: goalTip,         trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    if($('#foundBugTip').length)     new zui.Tooltip('#foundBugTip',     {title: foundBugTip,     trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    if($('#legacyBugTip').length)    new zui.Tooltip('#legacyBugTip',    {title: legacyBugTip,    trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    if($('#activatedBugTip').length) new zui.Tooltip('#activatedBugTip', {title: activatedBugTip, trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
    if($('#fromCaseBugTip').length)  new zui.Tooltip('#fromCaseBugTip',  {title: fromCaseBugTip,  trigger: 'hover', placement: 'top', type: 'black', 'className': 'border border-light'});
});

/**
 * Refresh page.
 *
 * @access public
 * @return void
 */
window.refreshPage = function()
{
    var begin = $("[name=begin]").val();
    var end   = $("[name=end]").val();

    if(begin.indexOf('-') != -1)
    {
        var beginarray = begin.split("-");
        var begin = '';
        for(i = 0; i < beginarray.length; i++) begin = begin + beginarray[i];
    }
    if(end.indexOf('-') != -1)
    {
        var endarray = end.split("-");
        var end = '';
        for(i = 0 ; i < endarray.length ; i++) end = end + endarray[i];
    }

    if(config.currentMethod == 'create')
    {
        loadTarget($.createLink('testreport', 'create', "objectID=" + objectID + "&objectType=" + objectType + "&extra=" + extra + "&begin=" + begin + "&end=" + end), '#mainContainer');
    }
    else if(config.currentMethod == 'edit')
    {
        loadTarget($.createLink('testreport', 'edit', "reportID=" + reportID + "&begin=" + begin + "&end=" + end), '#mainContainer');
    }
}
