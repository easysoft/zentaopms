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
}

window.changeProduct = function(event)
{
    let productID = $(event.target).val();
    loadPage($.createLink('projectrelease', 'create', 'projectID=' + projectID + '&' + 'productID=' + productID));
}

window.changeStatus = function(e)
{
    const status = e.target.value;
    if(status == 'normal')
    {
        $('#releasedDate').closest('.form-row').removeClass('hidden');
        $('[data-name=date] .form-label').removeClass('required');
    }
    else
    {
        $('#releasedDate').closest('.form-row').addClass('hidden');
        $('[data-name=date] .form-label').addClass('required');
    }
}

window.setSystemBox = function(e)
{
    const newSystem = $(e.target).is(':checked') ? 1 : 0;
    $('#systemBox #systemName').addClass('hidden');
    $('#systemBox .picker-box').addClass('hidden');
    if(newSystem == 1)
    {
        $('#systemBox #systemName').removeClass('hidden');
    }
    else
    {
        $('#systemBox #systemName').val('');
        $('#systemBox .picker-box').removeClass('hidden');
    }
}

$(function()
{
    setTimeout(function()
    {
        $('[name=system]').trigger('change');
    }, 100);
})
