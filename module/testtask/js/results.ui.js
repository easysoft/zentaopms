$(function()
{
    if($('tr').length == 0) return false;

    if($('tr').first().data('status') == 'ready')
    {
        $('tr').first().trigger('click');
    }
    else
    {
        var times = 0;
        var id    = $('tr').first().data('id')
        var link  = $.createLink('testtask', 'ajaxGetResult', 'resultID=' + id);

        var resultInterval = setInterval(() => {
            times++;
            if(times > 600)
            {
                clearInterval(resultInterval);
            }

            $.getJSON(link, function(result)
            {
                task = result.data;
                if(task.ZTFResult != '')
                {
                    clearInterval(resultInterval);
                    loadPage();
                }
            });
        }, 1000);
    }
});
