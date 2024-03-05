window.clickSubmit = () =>
{
    const os = $('#os').zui('picker').$.state.value;
    loadPage({url : $.createLink('misc', 'downloadClient', "action=getPackage&os=" + os), selector : '#downloadClient', success: getClient});
};

function getClient()
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
                console.log(res);
                if(res.result == 'success')
                {
                    $('#setted').removeClass('hidden');
                    const link = $.createLink('misc', 'downloadClient', "action=downloadPackage" + '&os=' + os);
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
    const os = $('#os').val();
    const link = $.createLink('misc', 'ajaxGetPackageSize', 'os=' + os);
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
