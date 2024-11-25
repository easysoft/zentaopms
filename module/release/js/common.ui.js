window.loadSystemBlock = function()
{
    const systemID = $('[name=system]').val();
    if(!systemID || !appList[systemID]) return;

    $('#systemBlock, #buildBox').addClass('hidden');
    if(appList[systemID].integrated == 1)
    {
        if(typeof linkedRelease == 'undefined') linkedRelease = '';

        $('#systemBlock').removeClass('hidden');
        loadTarget($.createLink('release', 'ajaxLoadSystemBlock', `systemID=${systemID}&release=${linkedRelease}`), 'systemItems');
    }
    else
    {
        $('#buildBox').removeClass('hidden');
    }
}
