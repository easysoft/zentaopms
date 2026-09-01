window.enqueueTargets = function(event, url)
{
    const $result = $('#enqueueResult');
    if(!$result.length) return false;

    if(url === undefined)
    {
        url = $.createLink('zai', 'ajaxEnqueueTargets');
        $('#continueEnqueueBtn').hide();
    }

    $.getJSON(url, function(response)
    {
        if(!response || response.result === 'fail')
        {
            const message = (response && response.message) ? response.message : '';
            $result.append("<div class='flex items-center text-danger my-1 pl-5 h-5'><div class='rounded-full danger mr-2 w-1 h-1'></div>" + message + '</div>');
            $('#continueEnqueueBtn').show();
            return false;
        }

        if(response.result == 'finished')
        {
            $result.append("<div class='flex items-center text-success my-1 pl-5 h-5'><div class='rounded-full success mr-2 w-1 h-1'></div>" + response.message + '</div>');
            $('#continueEnqueueBtn').hide();
            return false;
        }

        const className  = response.type + 'count';
        const $typeCount = $result.find('.' + className);
        if($typeCount.length == 0)
        {
            $result.append("<div class='flex items-center text-success my-1 pl-5 h-5'><div class='rounded-full success mr-2 w-1 h-1'></div>" + response.message + '</div>');
        }
        else
        {
            const count = parseInt($typeCount.html(), 10) + parseInt(response.count, 10);
            $typeCount.html(count);
        }

        return window.enqueueTargets(event, response.next);
    });

    return false;
};

$(function()
{
    const $result = $('#enqueueResult');
    if($result.length && $result.data('auto-enqueue') == '1')
    {
        window.enqueueTargets();
    }
});
