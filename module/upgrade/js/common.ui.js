function updateProgressInterval() {
    interval = setInterval(function ()
    {
        updateProgress();
    }, 500);
}

let logOffset = 0;
function updateProgress()
{
    var url = $.createLink('upgrade', 'ajaxGetProgress', 'offset=' + logOffset);
    $.ajax(
    {
        url:url,
        success:function(result)
        {
            result    = JSON.parse(result);
            logOffset = result.offset;

            let progress = parseInt(result.progress);
            $("#progress .progress-bar").css('width', progress + '%');
            $('#progress .modal-title').text(progress + '%');

            if(result.log) $('#logBox').append(result.log);

            let element = document.getElementById('logBox');
            if(element.scrollHeight > 20000) element.innerHTML = element.innerHTML.substr(60000); // Remove old log.
            element.scrollTop = element.scrollHeight;

            if(progress == 100) clearInterval(interval);
        }
    });
}
