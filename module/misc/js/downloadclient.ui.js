window.clickSubmit = () =>
{
    const os = $('#os').zui('picker').$.state.value;
    loadPage({url : $.createLink('misc', rawMethod, "action=getPackage&os=" + os), selector : '#downloadClient', success: getClient});
};

window.getClient = function()
{
    const os = $('#os').val();
    const link = $.createLink('misc', 'ajaxGetClientPackage', 'os=' + os);
    progress = setInterval("showPackageSize()", 1000);
    $.getJSON(link, res =>
    {
        if(res.result == 'success')
        {
            clearInterval(progress);
            $('#downloading').addClass('hidden');
            $('#downloaded').removeClass('hidden');
            $('#setting').removeClass('hidden');

            const link = $.createLink('misc', 'ajaxSetClientConfig', 'os=' + os);
            $.getJSON(link, function(res)
            {
                if(res.result == 'success')
                {
                    $('#setted').removeClass('hidden');
                    const link = $.createLink('misc', rawMethod, "action=downloadPackage" + '&os=' + os);
                    zui.Modal.hide();
                    open(link, '_blank');
                }
                else
                {
                    $('#downloading').addClass('hidden');
                    $('#configError').removeClass('hidden');
                    $('#hasError').removeClass('hidden');
                    $('#clearTmp').removeClass('hidden');
                    $('#hasError').text(res.message);
                }
            });
        }
        else
        {
            clearInterval(progress);
            $('#downloading').addClass('hidden');
            $('#downloadError').removeClass('hidden');
            $('#hasError').removeClass('hidden');
            $('#clearTmp').removeClass('hidden');
            $('#hasError').text(res.message);
        }
    });
}

window.showPackageSize = () =>
{
    const link = $.createLink('misc', 'ajaxGetPackageSize');
    $.getJSON(link, res =>
    {
        if(res.result == 'success')
        {
            $('#downloading span').text(res.size);
        }
        else
        {
            $('#downloading span').text(0);
        }
    });
};

if(!$('#downloadClient input#os:not(.hidden)').length && !$('#downloadClient button.primary').length) getClient();
