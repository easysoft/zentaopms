$(function()
{
    $('#submit').click(function()
    {
        $(this).addClass('disabled');
        $(this).css('pointer-events', 'none');
        $('#upgradingTips').removeClass('hidden');
        if(writable)
        {
            $('#progress').modal('show');
            updateProgressInterval();
            updateProgress();
        }
    });
})

function updateProgressInterval() {
    interval = setInterval(function ()
    {
        updateProgress();
    }, 100);
}

function updateProgress()
{
    var url = createLink('upgrade', 'ajaxGetProgress');
    $.ajax(
    {
        url:url,
        success:function(result)
        {
            var progress = parseInt(result);
            $("#progress .progress-bar").css('width', progress + '%');
            $('.title').text(result + '%');
            if(result >= 100) clearInterval(interval);
        }
    });
}
