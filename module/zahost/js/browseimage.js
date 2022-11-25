$(function()
{
    updateProgress();
});

function updateProgress(){
    var interval = setInterval(function()
    {
        $.get(createLink('zahost', 'ajaxImageDownloadProgress', 'hostID=' + hostID)).done(function(response)
        {
            var result     = JSON.parse(response);
            var statusList = result.data;

            console.log(statusList);
            var hasInprogress = false;
            for(var imageID in statusList)
            {
                if(statusList[imageID].statusCode)
                {
                    if(statusList[imageID].statusCode == 'inprogress'){
                        hasInprogress = true;
                        $('.image-download-' + imageID).addClass('disabled');
                    }else if (statusList[imageID].statusCode == 'completed'){
                        $('.image-download-' + imageID).addClass('disabled');
                    }
                    $('.image-status-' + imageID).text(statusList[imageID].status);
                    $('.image-progress-' + imageID).text(statusList[imageID].progress);
                }
            }
            if(!hasInprogress){
                clearInterval(interval)
            }
        });
    }, 5000);
}