var interval;

window.createSortLink = function(col)
{
    var sort = col.name + '_asc';
    if(sort == orderBy) sort = col.name + '_desc';

    return sortLink.replace('{orderBy}', sort);
}

window.renderCell = function(result, {col, row})
{
    if(col.name === 'progress')
    {
        result[0] = {html: "<span class='image-progress-" + row.data.id + "'></span>"};
    }

    if(col.name === 'path')
    {
        result[0] = {html: "<span class='image-path-" + row.data.id + "'></span>"};
    }

    if(col.name === 'status')
    {
        result[0] = {html: "<span class='image-status-" + row.data.id + "'>" + result[0] + "</span>"};
    }

    return result;
};

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
                if (statusList[imageID].statusCode == 'inprogress' || statusList[imageID].statusCode == 'created' || statusList[imageID].statusCode == 'pending')
                {
                    hasInprogress = true;
                }
                else if (statusList[imageID].statusCode == 'completed')
                {
                    $('.image-path-' + imageID).text(statusList[imageID].path);
                    $('.image-path-' + imageID).attr('title', statusList[imageID].path);
                    $('.image-progress-' + imageID).text("100%");
                }
                else
                {
                    $('.image-progress-' + imageID).text('');
                }

                if(statusList[imageID].status != $('.image-status-' + imageID).text()) loadPage();

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
