function updateProgressInterval()
{
    $.get($.createLink('upgrade', 'ajaxFixConsistency', 'version=' + version));
    logOffset = 0;
    interval  = setInterval(function()
    {
        var url = $.createLink('upgrade', 'ajaxGetFixLogs', 'offset=' + logOffset);
        $.getJSON(url, function(result)
        {
            logOffset = result.offset;

            if(result.log) $('#logBox').append(result.log);

            let element = document.getElementById('logBox');
            element.scrollTop = element.scrollHeight;

            if(result.finished)
            {
                clearInterval(interval);
                location.href=location.href;
            }
        })
    }, 500);
}

if(execFixSQL) updateProgressInterval()
