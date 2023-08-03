$(function()
{
    sessionStorage.removeItem('TID');
    finishedShow();

    if(needProcess.updateFile != undefined) updateFile($.createLink('upgrade', 'ajaxUpdateFile'));
});

/**
 * Finished show message.
 *
 * @access public
 * @return void
 */
function finishedShow()
{
    var show = true;
    $.each(needProcess, function(processKey, processType)
    {
        if(processType == 'notice') return true;
        if(finish[processKey] == false) show = false;
    });

    if(show)
    {
        $.get(processLink);
        $('a#tohome').closest('.message').removeClass('hidden');
    }
}

/**
 * Update file.
 *
 * @param  string $link
 * @access public
 * @return void
 */
function updateFile(link)
{
    $.getJSON(link, function(response)
    {
        if(response == null)
        {
            finish['updateFile'] = true;
            finishedShow();
        }
        else if(response.result == 'finished')
        {
            $('#resultBox li span.' + response.type + '-num').html(num + response.count);
            finish['updateFile'] = true;
            $('#resultBox').prepend("<li class='text-success'>" + response.message + "</li>");
            finishedShow();
        }
        else
        {
            if($('#resultBox li span.' + response.type + '-num').length == 0 || response.type != response.nextType)
            {
                $('#resultBox').prepend("<li class='text-success'>" + response.message + "</li>");
            }
            var num = parseInt($('#resultBox li span.' + response.type + '-num').html());
            $('#resultBox li span.' + response.type + '-num').html(num + response.count);
            updateFile(response.next);
        }
    });
}
