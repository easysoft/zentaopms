var interval;

$(function () {
    updateProgressInterval();
});

function updateProgressInterval() {
    updateProgress();
    interval = setInterval(function ()
    {
        updateProgress();
    }, 3000);
}

function updateProgress() {
    $.get(createLink('zahost', 'ajaxImageDownloadProgress', 'hostID=' + hostID)).done(function (response)
    {
        var result = JSON.parse(response);
        var statusList = result.data;

        var hasInprogress = false;
        for (var imageID in statusList) {
            if (statusList[imageID].statusCode) {
                if (statusList[imageID].statusCode == 'inprogress' || statusList[imageID].statusCode == 'created' || statusList[imageID].statusCode == 'pending')
                {
                    hasInprogress = true;
                    $('.image-download-' + imageID).addClass('disabled');
                    $('.image-cancel-' + imageID).removeClass('disabled');
                }
                else if (statusList[imageID].statusCode == 'completed')
                {
                    $('.image-path-' + imageID).text(statusList[imageID].path);
                    $('.image-path-' + imageID).attr('title', statusList[imageID].path);
                    $('.image-download-' + imageID).addClass('disabled');
                    $('.image-cancel-' + imageID).addClass('disabled');
                    $('.image-cancel-' + imageID).attr('href', '#');
                    $('.image-progress-' + imageID).text("100%");
                }
                else
                {
                    var link = createLink('zahost', 'downloadImage', "hostID="+hostID+"&imageID="+imageID);
                    $('.image-download-' + imageID).removeClass('disabled');
                    $('.image-download-' + imageID).attr('href', link);
                    $('.image-cancel-' + imageID).addClass('disabled');
                    $('.image-cancel-' + imageID).attr('href', '#');
                    $('.image-progress-' + imageID).text('');
                }
                $('.image-status-' + imageID).text(statusList[imageID].status);
                if(statusList[imageID].progress != '' && statusList[imageID].statusCode != 'completed')
                {
                    $('.image-progress-' + imageID).text(statusList[imageID].progress);
                }
            }
        }
        if (!hasInprogress)
        {
            clearInterval(interval)
        }
    });
}
