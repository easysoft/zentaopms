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
    $.get($.createLink('zahost', 'ajaxImageDownloadProgress', 'hostID=' + hostID)).done(function (response)
    {
        var result = JSON.parse(response);
        var statusList = result.data;

        var hasInprogress = false;
        for (var imageID in statusList) {
            if (statusList[imageID].statusCode) {
                imgProgress = $('.modal [data-col="progress"][data-row="' + imageID + '"] .dtable-cell-content')
                imgPath     = $('.modal [data-col="path"][data-row="' + imageID + '"] .dtable-cell-content')
                imgStatus   = $('.modal [data-col="status"][data-row="' + imageID + '"] .dtable-cell-content')
                imgDownload = $('.modal [data-col="actions"][data-row="' + imageID + '"]').find('a').first();
                imgCancel   = $('.modal [data-col="actions"][data-row="' + imageID + '"]').find('a').last();

                if (statusList[imageID].statusCode == 'inprogress' || statusList[imageID].statusCode == 'created' || statusList[imageID].statusCode == 'pending')
                {
                    hasInprogress = true;
                    imgDownload.addClass('disabled');
                    imgCancel.removeClass('disabled');
                }
                else if (statusList[imageID].statusCode == 'completed')
                {
                    imgPath.text(statusList[imageID].path);
                    imgPath.attr('title', statusList[imageID].path);
                    imgDownload.addClass('disabled');
                    imgCancel.addClass('disabled');
                    imgCancel.attr('href', '#');
                    imgProgress.text("100%");
                }
                else
                {
                    var link = $.createLink('zahost', 'downloadImage', "hostID="+hostID+"&imageID="+imageID);
                    imgDownload.removeClass('disabled');
                    imgDownload.attr('href', link);
                    imgCancel.addClass('disabled');
                    imgCancel.attr('href', '#');
                    imgProgress.text('');
                }
                imgStatus.text(statusList[imageID].status);
                if(statusList[imageID].progress != '' && statusList[imageID].statusCode != 'completed')
                {
                    imgProgress.text(statusList[imageID].progress);
                }
            }
        }
        if (!hasInprogress)
        {
            clearInterval(interval)
        }
    });
}
