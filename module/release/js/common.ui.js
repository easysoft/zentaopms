window.loadSystemBlock = function(e)
{
    const systemID = $(e.target).val();
    if(!systemID || !appList[systemID]) return;

    $('#systemBlock, #buildBox').addClass('hidden');
    if(appList[systemID].integrated == 1)
    {
        $('#systemBlock').removeClass('hidden');
        loadTarget($.createLink('release', 'ajaxLoadSystemBlock', 'systemID=' + systemID), 'systemItems');
    }
    else
    {
        $('#buildBox').removeClass('hidden');
    }
}
