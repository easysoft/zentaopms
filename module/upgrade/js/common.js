$(function()
{
    setTimeout('setPing()', 1000 * 60 * 10);
});

function updateProgressInterval() {
    interval = setInterval(function ()
    {
        updateProgress();
    }, 500);
}

function updateProgress()
{
    var url = createLink('upgrade', 'ajaxGetProgress');
    $.ajax(
    {
        url:url,
        success:function(result)
        {
            result = JSON.parse(result);

            let progress = parseInt(result.progress);
            $("#progress .progress-bar").css('width', progress + '%');
            $('.title').text(progress + '%');

            if(result.log) $('#logBox').append(result.log);

            let element = document.getElementById('logBox');
            if(element.scrollHeight > 20000) element.innerHTML = element.innerHTML.substr(60000); // Remove old log.
            element.scrollTop = element.scrollHeight;

            if(result.offset == 'finished') clearInterval(interval);
        }
    });
}
