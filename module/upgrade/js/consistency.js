function updateProgressInterval()
{
    $.get($.createLink('upgrade', 'ajaxFixConsistency', 'version=' + version));
    showLogs(0);
}

function showLogs(logOffset)
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
            location.href=location.href;
            return;
        }

        showLogs(logOffset);
    })
}

if(execFixSQL) updateProgressInterval()
