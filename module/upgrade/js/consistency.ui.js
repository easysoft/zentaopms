function updateProgressInterval()
{
    $.get($.createLink('upgrade', 'ajaxFixConsistency', 'version=' + version));
    logOffset = 0;
    prevLog   = '';
    interval  = setInterval(function()
    {
        var url = $.createLink('upgrade', 'ajaxGetFixLogs', 'offset=' + logOffset);
        $.getJSON(url, function(result)
        {
            logOffset = result.offset;

            if(result.log && prevLog != result.log)
            {
                $('#logBox').append(result.log);
                prevLog = result.log;
            }
            $('#progressBox').html(result.progress + '%');

            let element = document.getElementById('logBox');
            element.scrollTop = element.scrollHeight;

            if(result.finished)
            {
                clearInterval(interval);
                $('#continueBtn').removeClass('disabled').attr('onclick', 'loadCurrentPage()');
            }
        })
    }, 500);
}

if(execFixSQL) updateProgressInterval()
