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
                $('.image-status-' + imageID).text(statusList[imageID].statusName);
            }

        });
    }, 5000);
});
