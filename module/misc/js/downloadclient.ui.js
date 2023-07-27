window.clickSubmit = function()
{
    const os = $('#os').zui('picker').$.state.value;
    loadPage({url : $.createLink('misc', 'downloadClient', "action=getPackage&os=" + os), selector : '#downloadClient', success: getClient});
}

function getClient()
{
    const os = $('#os').val();
    var link = $.createLink('misc', 'ajaxGetClientPackage', 'os=' + os);
    progress = setInterval("showPackageSize()", 1000);
    $.getJSON(link, function(response)
    {
        if(response.result == 'success')
        {
            clearInterval(progress);
            $('#downloading').addClass('hidden');
            $('#downloaded').removeClass('hidden');
            $('#setting').removeClass('hidden');

            var link = $.createLink('misc', 'ajaxSetClientConfig', 'os=' + os);
            $.getJSON(link, function(response)
            {
                if(response.result == 'success')
                {
                    $('#setted').removeClass('hidden');
                    var link = $.createLink('misc', 'downloadClient', "action=downloadPackage" + '&os=' + os);
                    $.closeModal();
                    location.href = link;
                }
                else
                {
                    $('#downloading').addClass('hidden');
                    $('#configError').removeClass('hidden');
                    $('#hasError').removeClass('hidden');
                    $('#clearTmp').removeClass('hidden');
                    $('#hasError').text(response.message);
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
            $('#hasError').text(response.message);
        }
    });
}

window.showPackageSize = function()
{
    const os = $('#os').val();
    var link = $.createLink('misc', 'ajaxGetPackageSize', 'os=' + os);
    $.getJSON(link, function(response)
    {
        if(response.result == 'success')
        {
            $('#downloading span').text(response.size);
        }
        else
        {
            $('#downloading span').text(0);
        }
    });
}
