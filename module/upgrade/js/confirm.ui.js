window.submitConfirm = function(event) {

    zui.Modal.open({id: 'progress'});
    $(event.target).addClass('disabled');
    $(event.target).css('pointer-events', 'none');
    $('#upgradingTips').removeClass('hidden');
    if(writable)
    {
        updateProgressInterval();
        updateProgress();
    }
}

function updateProgressInterval() {
    interval = setInterval(function ()
    {
        updateProgress();
    }, 100);
}

function updateProgress()
{
    var url = $.createLink('upgrade', 'ajaxGetProgress');
    $.ajax(
    {
        url: url,
        success: function(result)
        {
            var progress = parseInt(result);
            $("#progress .progress-bar").css('width', progress + '%');
            $('.modal-title').text(result + '%');
            if(result >= 100)
            {
                clearInterval(interval);
                $('#progress').hide();
            }
        }
    });
}
