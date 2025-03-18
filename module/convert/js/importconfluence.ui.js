let   dynamicImporting = langImporting;
const intervalId       = setInterval(function()
{
    const $dynamicImporting = $('#importResult .dynamicImporting');
    if($dynamicImporting.length == 0) return;

    let ellipsis = $dynamicImporting.html().replace(langImporting, '');
    ellipsis += '•';
    if(ellipsis.length > 12) ellipsis = '';
    $('#importResult .dynamicImporting').html(langImporting + ' ' + ellipsis);
}, 1000);

function importConfluence(event, url, hide)
{
    if(hide === true) $(event.target).hide();

    $('#importResult .importing').removeClass('hidden');
    if($('#importResult .dynamicImporting').length == 0) $('#importResult').append("<li class='dynamicImporting'>" + dynamicImporting + "</li>");
    $('#actionBar').hide();

    $.get(url, function(data)
    {
        try
        {
            let response = JSON.parse(data);
            if(response.result == 'finished')
            {
                clearInterval(intervalId);
                $('#importResult .dynamicImporting').remove();
                $('#importResult').append("<li class='text-success my-1'>" + response.message + '</li>');
                $('#importResult .importing').addClass('hidden');
                return false;
            }
            else
            {
                className  = response.type + 'count';
                $typeCount = $('#importResult .' + className)
                if($typeCount.length == 0)
                {
                    dynamicImporting = $('#importResult .dynamicImporting').html();
                    $('#importResult .dynamicImporting').remove();
                    $('#importResult').append("<li class='text-success my-1'>" + response.message + '</li>');
                }
                else
                {
                    count = parseInt($typeCount.html()) + parseInt(response.count);
                    $typeCount.html(count);
                }

                return importConfluence(event, response.next);
            }
        }
        catch(e)
        {
            clearInterval(intervalId);
            $('#importResult .dynamicImporting').remove();
            $('#importResult').append("<li class='text-danger my-1'>" + data + '</li>');
            $('#importResult').append("<li class='text-danger my-1'>" + langImportFailed + '</li>');
        }
    });
    return false;
};
