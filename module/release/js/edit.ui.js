window.loadSystemBlock = function()
{
    const systemID = $('[name=system]').val();
    if(!systemID || !appList[systemID]) return;

    $('#systemBlock, #buildBox').addClass('hidden');
    if(appList[systemID].integrated == 1)
    {
        if(typeof linkedRelease == 'undefined') linkedRelease = '';

        $('#systemBlock').removeClass('hidden');
        loadTarget($.createLink('release', 'ajaxLoadSystemBlock', `systemID=${systemID}&release=${linkedRelease}&releaseID=${releaseID}`), 'systemItems');
    }
    else
    {
        window.loadBuilds(productID);
        $('#buildBox').removeClass('hidden');
    }
};

$(function()
{
    setTimeout(function()
    {
        changeStatus();
        window.loadSystemBlock();
    }, 100);
})
