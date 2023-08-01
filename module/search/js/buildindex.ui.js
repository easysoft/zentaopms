function buildIndex(event, url)
{
    if(url === undefined)
    {
        url = $.createLink('search', 'buildIndex', 'mode=build');
        $(event.target).hide();
    }

    $.getJSON(url, function(response)
    {
        if(response.result == 'finished')
        {
            $('#buildResult').append("<div class='flex items-center text-success my-1 pl-5 h-5'><div class='rounded-full success mr-2 w-1 h-1'></div>" + response.message + '</div>');
            return false;
        }
        else
        {
            const className  = response.type + 'count';
            const $typeCount = $('#buildResult .' + className)
            if($typeCount.length == 0)
            {
                $('#buildResult').append("<div class='flex items-center text-success my-1 pl-5 h-5'><div class='rounded-full success mr-2 w-1 h-1'></div>" + response.message + '</div>');
            }
            else
            {
                const count = parseInt($typeCount.html()) + parseInt(response.count);
                $typeCount.html(count);
            }

            return buildIndex(event, response.next);
        }
    });
    return false;
};
