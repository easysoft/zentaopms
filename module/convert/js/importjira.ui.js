function importJira(event, url, hide)
{
    if(hide === true) $(event.target).hide();

    $('#importResult .importing').removeClass('hidden');

    $.getJSON(url, function(response)
    {
        if(response.result == 'finished')
        {
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
                $('#importResult').append("<li class='text-success my-1'>" + response.message + '</li>');
            }
            else
            {
                count = parseInt($typeCount.html()) + parseInt(response.count);
                $typeCount.html(count);
            }

            return importJira(event, response.next);
        }
    });
    return false;
};
