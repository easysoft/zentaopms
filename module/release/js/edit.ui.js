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
        window.loadBuilds(productID);
        $('#buildBox').removeClass('hidden');
    }
};

window.changeStatus = function(e)
{
    const status = e.target.value;
    if(status == 'wait')
    {
        $('#releasedDate').closest('.form-row').addClass('hidden');
        $('[data-name=date] .form-label').addClass('required');
    }
    else
    {
        $('#releasedDate').closest('.form-row').removeClass('hidden');
        $('[data-name=date] .form-label').removeClass('required');
    }
};


$(function()
{
    setTimeout(function()
    {
        changeStatus({target: {value: oldStatus}});
        window.loadSystemBlock();
    }, 100);
})
