$(function()
{
    setInterval(function()
    {
        $.get(createLink('zahost', 'ajaxImageDownloadProgress', 'hostID=' + hostID)).done(function(response)
        {
            var result     = JSON.parse(response);
            var statusList = result.data;

            console.log(statusList);
            for(var imageID in statusList)
            {
                if(statusList[imageID].statusCode)
                {
                  $('.image-status-' + imageID).text(statusList[imageID].status);
                }
            }
        });
    }, 5000);
});
