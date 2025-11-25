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

window.setSystemBox = function()
{
    const newSystem = $('[name="newSystem"]').is(':checked') ? 1 : 0;

    const systemTip     = $('#systemTip');
    const systemNameTip = $('#systemNameTip');
    if(typeof systemTip != 'undefined') systemTip.remove();
    if(typeof systemNameTip != 'undefined') systemNameTip.remove();

    $('#systemBox #systemName').addClass('hidden').removeClass('has-error');
    $('#systemBox .picker-box').addClass('hidden').find('.picker').removeClass('has-error');
    if(newSystem == 1)
    {
        const $buildPicker = $('select[name^=build]').zui('picker');
        $buildPicker.render({items: [], multiple: true});
        $buildPicker.$.setValue('');

        $('#systemBlock, #buildBox').addClass('hidden');
        $('#buildBox').removeClass('hidden');
        $('#systemBox #systemName').removeClass('hidden');
    }
    else
    {
        window.loadSystemBlock();

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
