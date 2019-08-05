function getStatus(method, params)
{
    $.get(createLink('story', 'ajaxGetStatus', "method=" + method + '&params=' + params), function(status)
    {
        if($('form #status').length == 0)
        {
            $('form').append("<input type='hidden' name='status' id='status' value='" + status + "' />");
        }
        else
        {
            $('form #status').val(status);
        }
    });
}
